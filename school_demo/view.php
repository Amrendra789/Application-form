<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT student.*, classes.name AS class_name 
                       FROM student 
                       JOIN classes ON student.class_id = classes.class_id 
                       WHERE student.id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>View Student</title>
</head>
<body>
    <h1>View Student</h1>

    <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
    <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name']) ?></p>
    <p><strong>Image:</strong><br> <img src="uploads/<?= htmlspecialchars($student['image']) ?>" width="100"></p>
    <p><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>

    <a href="index.php">Back to list</a>
</body>
</html>
