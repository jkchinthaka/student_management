<?php
require_once '../config.php';

$stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Courses</title>
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
        <h2>Courses List</h2>
        <a href="add.php" style="margin-bottom: 20px; display: inline-block;">➕ Add New Course</a>

        <?php if (empty($courses)): ?>
            <p>No courses found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['id']) ?></td>
                            <td><?= htmlspecialchars($course['course_code']) ?></td>
                            <td><?= htmlspecialchars($course['course_name']) ?></td>
                            <td><?= htmlspecialchars($course['description']) ?: 'N/A' ?></td>
                            <td><?= htmlspecialchars($course['created_at']) ?></td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $course['id'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $course['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>