<?php
require 'db.php';

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    echo "Adding employee..."; // Debugging line

    $stmt = $pdo->prepare("INSERT INTO employees (name, email, position, salary) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['position'], $_POST['salary']]);
    echo "Employee added successfully.";
}

// Read
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM employees");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($employees);
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $stmt = $pdo->prepare("UPDATE employees SET name = ?, email = ?, position = ?, salary = ? WHERE id = ?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['position'], $_POST['salary'], $_POST['id']]);
    echo "Employee updated successfully.";
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_employee'])) {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    echo "Employee deleted.";
}
?>
