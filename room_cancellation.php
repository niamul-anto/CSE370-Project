<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_system";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date
$today = date("Y-m-d");

// Step 1: Guest selection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $guest_id = $_POST['id'];

    // Get guest's checkout date
    $stmt = $conn->prepare("SELECT name, checkout FROM guest WHERE id = ?");
    $stmt->bind_param("i", $guest_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $guest = $result->fetch_assoc();
    $stmt->close();

    if ($guest) {
        $check_out = $guest['checkout'];
        $guest_name = htmlspecialchars($guest['name']);

        if ($check_out == $today) {
            echo "<h3>Sorry $guest_name, cancellation is not allowed on your check-out day ($check_out).</h3>";
        } elseif (isset($_POST['confirm']) && $_POST['confirm'] == "yes") {
            // Update checkout date to today
            $update = $conn->prepare("UPDATE guest SET checkout = ? WHERE id = ?");
            $update->bind_param("si", $today, $guest_id);
            $update->execute();
            $update->close();

            echo "<h3>Dear $guest_name, we're sorry to see you go. 💔</h3>";
            echo "<p>Your booking has been cancelled successfully. We hope to serve you again in the future.</p>";
            echo "<a href='index.php'>Back to Home</a>";
        } elseif (!isset($_POST['confirm'])) {
            // Ask for confirmation
            echo "<h3>$guest_name, are you sure you want to cancel your booking?</h3>";
            echo "<form method='POST'>";
            echo "<input type='hidden' name='id' value='$guest_id'>";
            echo "<button type='submit' name='confirm' value='yes'>Yes, Cancel Booking</button> ";
            echo "<button type='submit' name='confirm' value='no'>No, Go Back</button>";
            echo "</form>";
        } else {
            echo "<h3>Cancellation aborted. Thank you for staying with us, $guest_name!</h3>";
        }
    } else {
        echo "<h3>Guest not found. Please check your ID and try again.</h3>";
    }
} else {
    // Form to enter guest ID
    echo "<h2>Room Cancellation</h2>";
    echo "<form method='POST'>";
    echo "Enter Your Guest ID: <input type='number' name='id' required>";
    echo "<input type='submit' value='Proceed'>";
    echo "</form>";
}

$conn->close();
?>
