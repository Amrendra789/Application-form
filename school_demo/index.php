<?php
include 'db.php';

$query = "SELECT student.*, classes.name AS class_name FROM student 
          JOIN classes ON student.class_id = classes.class_id";
$students = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Student List</title>
</head>
<body>
    <h1>Student List</h1>
    <a href="create.php">Create Student</a>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['class_name']) ?></td>
            <td><img src="uploads/<?= htmlspecialchars($student['image']) ?>" width="50"></td>
            <td><?= htmlspecialchars($student['created_at']) ?></td>
            <td>
                <a href="view.php?id=<?= $student['id'] ?>">View</a> |
                <a href="edit.php?id=<?= $student['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $student['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
