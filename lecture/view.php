<?php
require_once '../config.php';

$stmt = $pdo->query("
    SELECT l.*, c.course_code, c.course_name 
    FROM lectures l 
    JOIN courses c ON l.course_id = c.id 
    ORDER BY l.date DESC, l.time DESC
");
$lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Lectures</title>
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
        <h2>Lectures List</h2>
        <a href="add.php" style="margin-bottom: 20px; display: inline-block;">➕ Add New Lecture</a>

        <?php if (empty($lectures)): ?>
            <p>No lectures found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lectures as $lec): ?>
                        <tr>
                            <td><?= htmlspecialchars($lec['id']) ?></td>
                            <td><?= htmlspecialchars($lec['course_code']) ?> - <?= htmlspecialchars($lec['course_name']) ?></td>
                            <td><?= htmlspecialchars($lec['title']) ?></td>
                            <td><?= htmlspecialchars($lec['date']) ?></td>
                            <td><?= htmlspecialchars($lec['time']) ?></td>
                            <td><?= htmlspecialchars($lec['description']) ?: 'N/A' ?></td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $lec['id'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $lec['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>