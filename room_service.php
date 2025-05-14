<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_system";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_name'])) {
    $selected_service = $_POST['service_name'];

    // Get service details
    $stmt = $conn->prepare("SELECT service_name FROM room_service WHERE service_name = ?");
    $stmt->bind_param("s", $selected_service);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if ($service) {
        echo "<h3>Thank you for choosing the service: <b>" . htmlspecialchars($service['service_name']) . "</b>. We'll be shortly available with you.</h3>";
    } else {
        echo "<h3>Invalid selection. Please try again.</h3>";
    }
    $stmt->close();
} else {
    // Fetch all room services
    $sql = "SELECT * FROM room_service";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Choose a Room Service</h2>";
        echo "<form method='POST' action='room_service.php'>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Select</th><th>Service Name</th><th>Time Slot</th><th>Extra Charge</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='radio' name='service_name' value='" . htmlspecialchars($row['service_name']) . "' required></td>";
            echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['time_slot']) . "</td>";
            echo "<td>" . htmlspecialchars($row['extra_charge']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        echo "<input type='submit' value='Confirm Service'>";
        echo "</form>";
    } else {
        echo "No room services found.";
    }
}

$conn->close();
?>
