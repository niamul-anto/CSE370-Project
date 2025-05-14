<?php
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $name = strtoupper(trim($_POST['name']));
    $id = $_POST['id'];

    if ($user_type == 'guest') {
        $query = "SELECT * FROM guest WHERE id = ? AND UPPER(name) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $id, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            header("Location: guest_dashboard.php?id=$id");
            exit();
        } else {
            $error = "Invalid guest credentials.";
        }
    } else {
        $query = "SELECT * FROM admin WHERE id = ? AND UPPER(name) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $id, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            header("Location: admin_dashboard.php?id=$id");
            exit();
        } else {
            $error = "Invalid admin credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="post">
        <label>User Type:</label><br>
        <select name="user_type" required>
            <option value="guest">Guest</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input type="text" name="name" placeholder="Name (IN CAPITAL)" required><br>
        <input type="number" name="id" placeholder="ID" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p style="color:red;"><?= $error ?></p>
    <a href="index.php">Back to Home</a>
</div>
</body>
</html>
