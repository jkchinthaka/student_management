<?php
require_once '../config.php';

// Fetch all courses for dropdown
$stmt = $pdo->query("SELECT id, course_code, course_name FROM courses ORDER BY course_name");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            $stmt = $pdo->prepare("INSERT INTO lectures (course_id, title, description, date, time) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$course_id, $title, $desc, $date, $time]);
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
    <title>Add Lecture</title>
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
        <h2>Add New Lecture</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="course_id">Select Course *</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= isset($_POST['course_id']) && $_POST['course_id'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['course_code']) ?> - <?= htmlspecialchars($c['course_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Lecture Title *</label>
                <input type="text" id="title" name="title" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" value="<?= isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="time">Time *</label>
                <input type="time" id="time" name="time" value="<?= isset($_POST['time']) ? htmlspecialchars($_POST['time']) : '' ?>" required>
            </div>

            <button type="submit">Add Lecture</button>
            <a href="view.php">← Back to List</a>
        </form>
    </div>
</body>
</html>