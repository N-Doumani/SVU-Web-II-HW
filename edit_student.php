<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$student_id = $_GET['id'] ?? null;

if (!$student_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch existing student details
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $profile_picture = $student['profile_picture']; // Keep old picture by default

    // Handle new picture upload if provided
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($fileext, $allowed)) {
            $new_filename = uniqid('student_', true) . '.' . $fileext;
            $upload_path = 'uploads/' . $new_filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                // Delete old image file if exists
                if ($student['profile_picture'] && file_exists('uploads/' . $student['profile_picture'])) {
                    unlink('uploads/' . $student['profile_picture']);
                }
                $profile_picture = $new_filename;
            }
        }
    }

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($address)) {
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ?, address = ?, profile_picture = ? WHERE id = ?");
        if ($stmt->execute([$name, $email, $phone, $address, $profile_picture, $student_id])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Failed to update student record.";
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
</head>
<body>
    <h2>Edit Student Record</h2>
    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
    <form method="POST" action="edit_student.php?id=<?php echo $student_id; ?>" enctype="multipart/form-data">
        <label>Full Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br><br>

        <label>Email Address:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required><br><br>

        <label>Address:</label><br>
        <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea><br><br>

        <label>Current Profile Picture:</label><br>
        <?php if ($student['profile_picture']): ?>
            <img src="uploads/<?php echo $student['profile_picture']; ?>" width="60" height="60"><br>
        <?php else: ?>
            No Picture Uploaded<br>
        <?php endif; ?>
        
        <label>Change Profile Picture:</label><br>
        <input type="file" name="profile_picture" accept="image/*"><br><br>

        <button type="submit">Update Student</button>
        <a href="dashboard.php">Cancel</a>
    </form>
</body>
</html>