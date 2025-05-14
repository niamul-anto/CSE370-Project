<?php
session_start();
$guest_id = $_SESSION['guest_id'] ?? 1; // fallback for testing
?>
<!DOCTYPE html>
<html>
<head>
    <title>Choose Your Desired Room</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('images/hotel.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 500px;
        }
        h1 {
            margin-bottom: 30px;
            font-size: 30px;
        }
        label {
            font-size: 18px;
        }
        select, button {
            width: 80%;
            padding: 10px;
            margin: 10px auto;
            font-size: 16px;
            border-radius: 5px;
        }
        #availableRooms {
            margin-top: 20px;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function fetchRooms() {
            const floor = document.getElementById('floor').value;
            const type = document.getElementById('type').value;
            if (floor && type) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'get_available_rooms.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    document.getElementById('availableRooms').style.display = 'block';
                    document.getElementById('availableRooms').innerHTML = this.responseText;
                };
                xhr.send(`floor=${floor}&type=${type}`);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Choose Your Desired Room</h1>
        <label for="floor">Choose Floor:</label><br>
        <select id="floor" onchange="fetchRooms()">
            <option value="">--Select--</option>
            <option value="Ground">Ground</option>
            <option value="1st">1st</option>
            <option value="2nd">2nd</option>
        </select><br>

        <label for="type">Choose Room Type:</label><br>
        <select id="type" onchange="fetchRooms()">
            <option value="">--Select--</option>
            <option value="single">Single</option>
            <option value="double">Double</option>
            <option value="deluxe">Deluxe</option>
        </select>

        <div id="availableRooms" style="display:none;"></div>
    </div>
</body>
</html>
