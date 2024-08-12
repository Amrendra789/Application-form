<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM student WHERE id = ?");
stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($student['image']) {
        unlink('uploads/' . $student['image']);
    }
    $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Delete Student</title>
</head>
<body>
    <h1>Delete Student</h1>

    <p>Are you sure you want to delete <?= htmlspecialchars($student['name']) ?>?</p>

    <form method="POST">
        <button type="submit">Yes, delete</button>
    </form>

    <a href="index.php">No, go back</a>
</body>
</html>
