<?php
session_start();
require_once 'db.php';

// 1. حماية الصفحة وإعادة التوجيه إذا لم يتم تسجيل الدخول
if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

// 2. إعداد البحث والتصفية والترقيم من قاعدة البيانات
$search  = trim($_GET['search'] ?? '');
$sort    = $_GET['sort'] ?? 'newest';
$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;

$sortOrder = 'id DESC';
if ($sort === 'oldest')    { $sortOrder = 'id ASC'; }
if ($sort === 'name_asc')  { $sortOrder = 'name ASC'; }
if ($sort === 'name_desc') { $sortOrder = 'name DESC'; }

$offset = ($page - 1) * $perPage;

if (!empty($search)) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?");
    $countStmt->execute(["%$search%", "%$search%", "%$search%"]);
    $totalStudents = (int) $countStmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY $sortOrder LIMIT ? OFFSET ?");
    $stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(2, "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(3, "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(4, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(5, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll();
} else {
    $totalStudents = (int) $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM students ORDER BY $sortOrder LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll();
}

// 3. إعدادات الواجهة والصفحة
$pageTitle  = 'View Students';
$activePage = 'view';

$students      = $students ?? [];
$search        = $search   ?? trim($_GET['search'] ?? '');
$sort          = $sort     ?? ($_GET['sort'] ?? 'newest');
$perPage       = $perPage  ?? 10;
$totalStudents = (int) ($totalStudents ?? count($students));
$totalPages    = max(1, (int) ceil($totalStudents / $perPage));
$page          = min(max(1, (int) ($page ?? 1)), $totalPages);

$from = $totalStudents > 0 ? ($page - 1) * $perPage + 1 : 0;
$to   = $totalStudents > 0 ? $from + count($students) - 1 : 0;

$sortOptions = [
    'newest'    => 'Newest first',
    'oldest'    => 'Oldest first',
    'name_asc'  => 'Name (A–Z)',
    'name_desc' => 'Name (Z–A)',
];

$pageUrl = function (int $p) use ($search, $sort): string {
    $query = [];
    if ($search !== '')     { $query['search'] = $search; }
    if ($sort !== 'newest') { $query['sort']   = $sort; }
    $query['page'] = $p;
    return 'view_students.php?' . http_build_query($query);
};

$startPage = max(1, min($page - 2, $totalPages - 4));
$endPage   = min($totalPages, $startPage + 4);

require __DIR__ . '/includes/header.php';
?>

<!-- Page header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Students</h1>
        <p class="page-subtitle">
            <?= $totalStudents ?> <?= $totalStudents === 1 ? 'student' : 'students' ?><?= $search !== '' ? ' match your search' : ' registered' ?>
        </p>
    </div>
    <div class="page-actions">
        <a href="add_student.php" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add student</a>
    </div>
</div>

<!-- Search & sort toolbar -->
<div class="card toolbar mb-3" id="search">
    <div class="card-body p-3">
        <form method="get" action="view_students.php" class="row g-2 align-items-center" role="search">
            <input type="hidden" name="page" value="1">

            <div class="col-12 col-md">
                <label for="searchInput" class="visually-hidden">Search students</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" class="form-control" id="searchInput" name="search"
                           value="<?= e($search) ?>" placeholder="Search by name, email or phone…" autocomplete="off">
                </div>
            </div>

            <div class="col-7 col-md-auto">
                <label for="sortSelect" class="visually-hidden">Sort by</label>
                <select class="form-select" id="sortSelect" name="sort" data-autosubmit>
                    <?php foreach ($sortOptions as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= $sort === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-5 col-md-auto d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Result info -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <span class="results-meta">
        <?php if ($totalStudents > 0): ?>
            Showing <strong><?= $from ?>–<?= $to ?></strong> of <strong><?= $totalStudents ?></strong>
        <?php else: ?>
            No results
        <?php endif; ?>
    </span>
    <?php if ($search !== ''): ?>
        <span class="search-chip">
            Results for “<?= e($search) ?>”
            <a href="view_students.php<?= $sort !== 'newest' ? '?sort=' . urlencode($sort) : '' ?>" aria-label="Clear search"><i class="bi bi-x"></i></a>
        </span>
    <?php endif; ?>
</div>

<?php if (empty($students)): ?>

    <div class="card">
        <div class="empty-state">
            <span class="icon-badge"><i class="bi <?= $search !== '' ? 'bi-search' : 'bi-people' ?>"></i></span>
            <?php if ($search !== ''): ?>
                <h2 class="h5">No students match “<?= e($search) ?>”</h2>
                <p>Try a different name, email or phone number.</p>
                <a href="view_students.php" class="btn btn-light">Clear search</a>
            <?php else: ?>
                <h2 class="h5">No students yet</h2>
                <p>Start by adding your first student record.</p>
                <a href="add_student.php" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add student</a>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>

    <div class="card card-table">
        <div class="table-responsive">
            <table class="table table-hover table-modern table-stack align-middle">
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col" class="d-none d-xl-table-cell">Address</th>
                        <th scope="col" class="d-none d-lg-table-cell">Added</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td class="cell-student" data-label="Student">
                            <div class="d-flex align-items-center gap-3">
                                <?= avatar_html($s['profile_picture'] ?? '', $s['name']) ?>
                                <div class="min-w-0">
                                    <div class="student-name"><?= e($s['name']) ?></div>
                                    <div class="student-meta">ID #<?= e($s['id']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Email" class="text-break">
                            <a href="mailto:<?= e($s['email']) ?>" class="text-reset text-decoration-none"><?= e($s['email']) ?></a>
                        </td>
                        <td data-label="Phone" class="text-nowrap">
                            <a href="tel:<?= e($s['phone']) ?>" class="text-reset text-decoration-none"><?= e($s['phone']) ?></a>
                        </td>
                        <td data-label="Address" class="d-none d-xl-table-cell">
                            <div class="cell-truncate" title="<?= e($s['address']) ?>"><?= e($s['address']) ?></div>
                        </td>
                        <td data-label="Added" class="d-none d-lg-table-cell text-nowrap text-muted-soft">
                            <?= e(date('M j, Y', strtotime($s['created_at']))) ?>
                        </td>
                        <td class="cell-actions text-end" data-label="Actions">
                            <div class="d-inline-flex gap-2">
                                <a href="update_student.php?id=<?= urlencode((string) $s['id']) ?>"
                                   class="btn btn-icon btn-soft-primary"
                                   data-tooltip="Edit" aria-label="Edit <?= e($s['name']) ?>">
                                    <i class="bi bi-pencil"></i><span class="d-md-none">Edit</span>
                                </a>
                                <button type="button"
                                        class="btn btn-icon btn-soft-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="<?= e($s['id']) ?>" data-name="<?= e($s['name']) ?>"
                                        data-tooltip="Delete" aria-label="Delete <?= e($s['name']) ?>">
                                    <i class="bi bi-trash"></i><span class="d-md-none">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <span class="results-meta">Page <?= $page ?> of <?= $totalPages ?></span>

            <nav aria-label="Students pages">
                <ul class="pagination">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <?php if ($page > 1): ?>
                            <a class="page-link" href="<?= e($pageUrl($page - 1)) ?>" aria-label="Previous page">
                                <i class="bi bi-chevron-left"></i><span class="d-none d-sm-inline">Previous</span>
                            </a>
                        <?php else: ?>
                            <span class="page-link" aria-disabled="true"><i class="bi bi-chevron-left"></i><span class="d-none d-sm-inline">Previous</span></span>
                        <?php endif; ?>
                    </li>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : 'd-none d-sm-block' ?>">
                            <?php if ($i === $page): ?>
                                <span class="page-link" aria-current="page"><?= $i ?></span>
                            <?php else: ?>
                                <a class="page-link" href="<?= e($pageUrl($i)) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <?php if ($page < $totalPages): ?>
                            <a class="page-link" href="<?= e($pageUrl($page + 1)) ?>" aria-label="Next page">
                                <span class="d-none d-sm-inline">Next</span><i class="bi bi-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="page-link" aria-disabled="true"><span class="d-none d-sm-inline">Next</span><i class="bi bi-chevron-right"></i></span>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>

<?php endif; ?>

<!-- Delete confirmation dialog -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="post" action="delete_student.php" data-loading>
            <input type="hidden" name="id" value="">
            <div class="modal-body text-center p-4 pb-3">
                <span class="modal-icon-danger mb-3"><i class="bi bi-trash3"></i></span>
                <h2 class="h5 mb-2" id="deleteModalTitle">Delete student?</h2>
                <p class="text-muted-soft mb-0">
                    You are about to delete <strong class="text-body" data-delete-name>this student</strong>.
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" data-loading-text="Deleting…"><i class="bi bi-trash"></i> Yes, delete</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>