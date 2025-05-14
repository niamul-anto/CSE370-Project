<?php
if (isset($_POST['cancel'])) {
    header("Location: index.php");
    exit();
}

$message = "";
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $nid = $_POST['nid'];
    $total_guests = $_POST['total_guests'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    if ($age < 18) {
        $message = "Sorry, you can't book a room if you're under 18 years old.";
    } else {
        include 'db.php';

        $sql = "INSERT INTO guest (name, age, nid, total_guests, checkin, checkout)
                VALUES ('$name', '$age', '$nid', '$total_guests', '$checkin', '$checkout')";

        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            session_start();
            $_SESSION['guest_id'] = $last_id;
            header("Location: choose_room.php");
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Please Fill-up the Below Form</title>
    <style>
        body {
            background-image: url('images/hotel.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 15px;
            box-shadow: 0 0 10px #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
        }

        input[type="submit"] {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        input[name="submit"] {
            background-color: #28a745;
            color: white;
        }

        input[name="cancel"] {
            background-color: #dc3545;
            color: white;
            float: right;
        }

        .message {
            text-align: center;
            color: yellow;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Please Fill-up the Below Form</h2>
    <form method="post" action="">
        Name: <input type="text" name="name" required><br>
        Age: <input type="number" name="age" required><br>
        NID: <input type="text" name="nid" required><br>
        Total Guests: <input type="number" name="total_guests" required><br>
        Check-in Date: <input type="date" name="checkin" required><br>
        Check-out Date: <input type="date" name="checkout" required><br>
        <input type="submit" name="submit" value="Book Your Room">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <div class="message"><?php echo $message; ?></div>
</div>

</body>
</html>
