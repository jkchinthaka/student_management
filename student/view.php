<?php
require_once '../config.php';

$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
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
        <h2>Students List</h2>
        <a href="add.php" style="margin-bottom: 20px; display: inline-block;">➕ Add New Student</a>

        <?php if (empty($students)): ?>
            <p>No students found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['phone']) ?></td>
                            <td><?= htmlspecialchars($student['created_at']) ?></td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $student['id'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $student['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>