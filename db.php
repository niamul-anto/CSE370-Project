<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "hotel_system";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function updateGuestBill($conn, $guest_id, $amount) {
    $stmt = $conn->prepare("UPDATE guest SET bill = bill + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $guest_id);
    $stmt->execute();
    $stmt->close();
}

?>
