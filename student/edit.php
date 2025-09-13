<?php
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $id]);
            header("Location: view.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already exists.";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="../student/view.php">Students</a></li>
            <li><a href="../course/view.php">Courses</a></li>
            <li><a href="../lecture/view.php">Lectures</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Edit Student</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($student['phone']) ?>">
            </div>

            <button type="submit">Update Student</button>
            <a href="view.php">← Back to List</a>
        </form>
    </div>
</body>
</html>