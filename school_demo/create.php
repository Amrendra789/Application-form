<?php
include 'db.php';

$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image'];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if ($image['error'] == 0) {
        $imagePath = 'uploads/' . uniqid() . '-' . basename($image['name']);
        $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            $errors[] = "Invalid image format. Only JPG, JPEG, and PNG are allowed.";
        } else {
            move_uploaded_file($image['tmp_name'], $imagePath);
        }
    } else {
        $imagePath = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $address, $class_id, basename($imagePath)]);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Create Student</title>
</head>
<body>
    <h1>Create Student</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name"><br>

        <label>Email:</label>
        <input type="email" name="email"><br>

        <label>Address:</label>
        <textarea name="address"></textarea><br>

        <label>Class:</label>
        <select name="class_id">
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class_id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Image:</label>
        <input type="file" name="image"><br>

        <button type="submit">Create</button>
    </form>

    <a href="index.php">Back to list</a>
</body>
</html>
