<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM student WHERE id = ?");
stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found!");
}

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
            if ($student['image']) {
                unlink('uploads/' . $student['image']);
            }
        }
    } else {
        $imagePath = $student['image'];
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $email, $address, $class_id, basename($imagePath), $id]);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>"><br>

        <label>Address:</label>
        <textarea name="address"><?= htmlspecialchars($student['address']) ?></textarea><br>

        <label>Class:</label>
        <select name="class_id">
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class_id'] ?>" <?= $class['class_id'] == $student['class_id'] ? 'selected' : '' ?>><?= htmlspecialchars($class['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Image:</label>
        <input type="file" name="image"><br>
        <?php if ($student['image']): ?>
            <img src="uploads/<?= htmlspecialchars($student['image']) ?>" width="100">
        <?php endif; ?><br>

        <button type="submit">Update</button>
    </form>

    <a href="index.php">Back to list</a>
</body>
</html>
