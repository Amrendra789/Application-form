<?php
include 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    
    if (empty($name)) {
        $errors[] = "Class name is required";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->execute([$name]);
        header('Location: classes.php');
        exit;
    }
}

$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Manage Classes</title>
</head>
<body>
    <h1>Manage Classes</h1>

    <form method="POST">
        <label>Class Name:</label>
        <input type="text" name="name"><br>
        <button type="submit">Add Class</button>
    </form>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Class Name</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($classes as $class): ?>
        <tr>
            <td><?= htmlspecialchars($class['name']) ?></td>
            <td><?= htmlspecialchars($class['created_at']) ?></td>
            <td>
                <a href="edit_class.php?id=<?= $class['class_id'] ?>">Edit</a> |
                <a href="delete_class.php?id=<?= $class['class_id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php">Back to list</a>
</body>
</html>
