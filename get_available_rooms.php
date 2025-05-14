<?php
$floor = $_POST['floor'];
$type = $_POST['type'];

$conn = new mysqli('localhost', 'root', '', 'hotel_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT room_no FROM room WHERE floor = ? AND type = ? AND status = 'available'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $floor, $type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<form method='POST' action='booking_confirmed.php'>";
    echo "<label>Select Available Room:</label>";
    echo "<select name='room_no'>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['room_no'] . "'>" . $row['room_no'] . "</option>";
    }
    echo "</select><br><br>";
    echo "<button type='submit'>Confirm Booking</button>";
    echo "</form>";
} else {
    echo "No rooms available for selected options.";
}

$stmt->close();
$conn->close();
?>
