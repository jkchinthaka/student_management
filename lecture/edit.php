<?php
require_once '../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit();
}

$stmt = $pdo->prepare("SELECT l.*, c.course_code, c.course_name FROM lectures l JOIN courses c ON l.course_id = c.id WHERE l.id = ?");
$stmt->execute([$id]);
$lecture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lecture) {
    header("Location: view.php");
    exit();
}

// Get all courses for dropdown
$stmt2 = $pdo->query("SELECT id, course_code, course_name FROM courses ORDER BY course_name");
$courses = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (empty($course_id) || empty($title) || empty($date) || empty($time)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE lectures SET course_id = ?, title = ?, description = ?, date = ?, time = ? WHERE id = ?");
            $stmt->execute([$course_id, $title, $desc, $date, $time, $id]);
            header("Location: view.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lecture</title>
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
        <h2>Edit Lecture</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="course_id">Select Course *</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $lecture['course_id'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['course_code']) ?> - <?= htmlspecialchars($c['course_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Lecture Title *</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($lecture['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($lecture['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($lecture['date']) ?>" required>
            </div>

            <div class="form-group">
                <label for="time">Time *</label>
                <input type="time" id="time" name="time" value="<?= htmlspecialchars($lecture['time']) ?>" required>
            </div>

            <button type="submit">Update Lecture</button>
            <a href="view.php">← Back to List</a>
        </form>
    </div>
</body>
</html>