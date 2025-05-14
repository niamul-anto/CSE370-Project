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

// Aesthetic descriptions for each hall
$hall_descriptions = [
    "Emerald Hall" => "A luxurious emerald-themed hall with ambient lighting and elegance in every corner.",
    "Royal Dome" => "Majestic dome with chandeliers and royal decor for unforgettable events.",
    "Sunset Lounge" => "Cozy sunset-facing lounge with soft lighting and a scenic vibe.",
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_type'], $_POST['hall_name'], $_POST['event_date'])) {
    $event_type = $_POST['event_type'];
    $hall_name = $_POST['hall_name'];
    $event_date = $_POST['event_date'];

    $check = $conn->prepare("SELECT * FROM event_details WHERE hall_name = ? AND status = 'available'");
    $check->bind_param("s", $hall_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update hall to unavailable
        $update = $conn->prepare("UPDATE event_details SET status = 'unavailable' WHERE hall_name = ?");
        $update->bind_param("s", $hall_name);
        $update->execute();
        $update->close();

        echo "<div style='max-width:600px;margin:50px auto;padding:30px;border-radius:15px;background:#e6ffee;border:1px solid #99ffcc;box-shadow:0 0 10px rgba(0,0,0,0.1);'>";
        echo "<h2 style='color:#006600;'>🎉 Booking Confirmed!</h2>";
        echo "<p><strong>Event Type:</strong> " . htmlspecialchars($event_type) . "</p>";
        echo "<p><strong>Hall:</strong> " . htmlspecialchars($hall_name) . "</p>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($event_date) . "</p>";
        echo "<p style='color:#006600;font-weight:bold;'>Thank you for choosing our service. We'll be in touch shortly!</p>";
        echo "<a href='index.php' style='text-decoration:none;color:white;background:#00994d;padding:10px 20px;border-radius:5px;'>Back to Home</a>";
        echo "</div>";
    } else {
        echo "<p style='color:red;'>Selected hall is no longer available. Please try another one.</p>";
    }
    $check->close();
} else {
    // Fetch halls
    $sql = "SELECT * FROM event_details WHERE status = 'available'";
    $result = $conn->query($sql);

    echo "<div style='max-width:600px;margin:30px auto;padding:30px;border-radius:15px;background:#f9f9f9;border:1px solid #ccc;box-shadow:0 0 10px rgba(0,0,0,0.1);'>";
    echo "<h2 style='text-align:center;color:#333;'>Book Your Event</h2>";
    echo "<form method='POST' style='display:flex;flex-direction:column;gap:15px;'>";

    // Event Type
    echo "<label><strong>Event Type:</strong></label>";
    echo "<input type='text' name='event_type' placeholder='e.g. Wedding, Seminar' required style='padding:8px;border:1px solid #aaa;border-radius:5px;'>";

    // Hall Selection
    if ($result->num_rows > 0) {
        echo "<label><strong>Select Hall:</strong></label>";
        echo "<select name='hall_name' required style='padding:8px;border-radius:5px;border:1px solid #aaa;' onchange='this.form.submit()'>";
        echo "<option disabled selected>-- Choose a Hall --</option>";

        while ($row = $result->fetch_assoc()) {
            $hall = $row['hall_name'];
            $guest_limit = $row['guest_limit'];
            echo "<option value='" . htmlspecialchars($hall) . "'>" . htmlspecialchars($hall) . " — Limit: $guest_limit</option>";
        }
        echo "</select>";
    } else {
        echo "<p>No available halls at the moment.</p>";
    }

    // Show hall description and guest limit if hall selected
    if (isset($_POST['hall_name']) || isset($_GET['hall_name'])) {
        $selected_hall = $_POST['hall_name'] ?? $_GET['hall_name'];

        $desc_query = $conn->prepare("SELECT * FROM event_details WHERE hall_name = ?");
        $desc_query->bind_param("s", $selected_hall);
        $desc_query->execute();
        $hall_data = $desc_query->get_result()->fetch_assoc();
        $desc_query->close();

        $description = $hall_descriptions[$selected_hall] ?? "Beautiful hall for hosting special events.";
        $guest_limit = "<strong>" . htmlspecialchars($hall_data['guest_limit']) . "</strong>";

        echo "<div style='background:#fff0e6;padding:15px;border-left:5px solid #ff9900;margin-top:10px;border-radius:5px;'>";
        echo "<p><strong>Description:</strong> $description</p>";
        echo "<p><strong>Guest Limit:</strong> $guest_limit guests</p>";
        echo "</div>";
    }

    // Event date and submit
    echo "<label><strong>Select Event Date:</strong></label>";
    echo "<input type='date' name='event_date' required style='padding:8px;border:1px solid #aaa;border-radius:5px;'>";

    echo "<button type='submit' style='background:#00994d;color:white;padding:10px;border:none;border-radius:5px;font-size:16px;'>Confirm Booking</button>";
    echo "</form>";
    echo "</div>";
}

$conn->close();
?>
