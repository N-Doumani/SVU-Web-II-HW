<?php
/**
 * Shared page footer (Section 5 — Styling & Responsiveness)
 * ------------------------------------------------------------------
 * Extra scripts for one page (e.g. AJAX validation) — set before including:
 *
 *     $pageScripts = ['assets/js/validation.js'];
 *     require __DIR__ . '/includes/footer.php';
 */
$pageScripts = $pageScripts ?? [];
?>
    </div><!-- /.container -->
</main>

<!-- ===================== Footer ===================== -->
<footer class="app-footer py-4 border-top">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
        <div>
            <div class="fw-bold">&copy; 2026 Syrian Virtual University (SVU)</div>
            <small class="text-muted-soft">BWP501 · Web Programming II | Supervised by: <strong>Dr. Bassel Al-Khatib</strong></small>
        </div>
        <nav class="d-flex gap-3" aria-label="Footer">
            <a href="index.html">Home</a>
            <a href="view_students.php">Students</a>
            <a href="add_student.php">Add Student</a>
        </nav>
    </div>
</footer>

<!-- Toast notifications appear here (used by UI.toast) -->
<div id="toastArea" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

<!-- Scripts: Bootstrap → ui.js → page scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/ui.js"></script>
<?php foreach ($pageScripts as $src): ?>
<script src="<?= e($src) ?>"></script>
<?php endforeach; ?>
</body>
</html>
