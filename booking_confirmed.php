<?php
session_start();
$room_no = $_POST['room_no'];
$guest_id = $_SESSION['guest_id'];

$conn = new mysqli('localhost', 'root', '', 'hotel_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mark room as unavailable
$update = $conn->prepare("UPDATE room SET status = 'unavailable' WHERE room_no = ?");
$update->bind_param("s", $room_no);
$update->execute();
$event_charge = 300; // Example charge for booking event
updateGuestBill($conn, $_SESSION['guest_id'], $event_charge);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: Verdana;
            text-align: center;
            margin-top: 100px;
        }
        a {
            color: #ffd700;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Your booking is confirmed!</h1>
    <p>Your ID is: <?php echo $guest_id; ?></p>
    <p>Your room number is: <?php echo $room_no; ?></p>
    <a href="index.php">Back to Home</a>
</body>
</html>

$event_charge = 300; // Example charge for booking event
updateGuestBill($conn, $_SESSION['guest_id'], $event_charge);
