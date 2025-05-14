<?php
include 'db.php';

$employees = [
    ['E101', 'Manager', '35', '111-111-1111'],
    ['E102', 'Receptionist', '28', '222-222-2222'],
    ['E103', 'Housekeeping', '31', '333-333-3333'],
];

$customers = $conn->query("SELECT * FROM guest");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <h3>1. Employee Details:</h3>
    <ul>
        <?php foreach ($employees as $emp): ?>
            <li>ID: <?= $emp[0] ?> | Position: <?= $emp[1] ?> | Age: <?= $emp[2] ?> | Contact: <?= $emp[3] ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>2. Customer Details:</h3>
    <ul>
        <?php while ($row = $customers->fetch_assoc()): ?>
            <li>ID: <?= $row['id'] ?> | Name: <?= $row['name'] ?> | Age: <?= $row['age'] ?> | Guests: <?= $row['total_guests'] ?></li>
        <?php endwhile; ?>
    </ul>

    <a href="index.php">Back to Home</a>
</div>
</body>
</html>
