<?php

include "includes/db.php";
include "includes/auth.php";

$sql = "SELECT * FROM medical_centers
        ORDER BY center_id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Hospitals - AmbuTrack</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        🚑 AmbuTrack
    </div>

    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="ambulances.php">
            Ambulances
        </a>

        <a href="request_ambulance.php">
            Emergency
        </a>

        <a href="track.php">
            Track
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>


<div class="dashboard">

    <h1>🏥 Hospitals</h1>

    <div class="hospital-container">

        <?php while ($row = $result->fetch_assoc()) { ?>

        <div class="feature-card">

            <h2>
                <?php echo $row["hospital_name"]; ?>
            </h2>

            <p>
                📍
                <?php echo $row["hospital_address"]; ?>
            </p>

            <p>
                📞
                <?php echo $row["contact_number"]; ?>
            </p>

            <p>
                🚨 Emergency:
                <?php echo $row["emergency_service"]; ?>
            </p>

        </div>

        <?php } ?>

    </div>

</div>

</body>

</html>
