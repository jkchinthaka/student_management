<?php
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header("Location: view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['course_code']);
    $name = trim($_POST['course_name']);
    $desc = trim($_POST['description']);

    if (empty($code) || empty($name)) {
        $error = "Course Code and Name are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, description = ? WHERE id = ?");
            $stmt->execute([$code, $name, $desc, $id]);
            header("Location: view.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Course Code already exists.";
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
    <title>Edit Course</title>
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
        <h2>Edit Course</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="course_code">Course Code *</label>
                <input type="text" id="course_code" name="course_code" value="<?= htmlspecialchars($course['course_code']) ?>" required>
            </div>

            <div class="form-group">
                <label for="course_name">Course Name *</label>
                <input type="text" id="course_name" name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($course['description']) ?></textarea>
            </div>

            <button type="submit">Update Course</button>
            <a href="view.php">← Back to List</a>
        </form>
    </div>
</body>
</html>