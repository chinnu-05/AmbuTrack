<?php

include "includes/auth.php";
include "includes/db.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT *
        FROM rescue_requests
        WHERE user_id = ?
        ORDER BY request_id DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$latest_request = null;

if ($result->num_rows > 0) {
    $latest_request = $result->fetch_assoc();
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Dashboard - AmbuTrack</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

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

        <a href="request_ambulance.php">
            Emergency
        </a>

        <a href="ambulances.php">
            Ambulances
        </a>

        <a href="hospitals.php">
            Hospitals
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

    <?php if ($latest_request != null) { ?>

    <div class="feature-card">

        <h2>🚑 Latest Ambulance Request</h2>

        <p>
            <strong>Patient:</strong>
            <?php echo $latest_request["patient_name"]; ?>
        </p>

        <p>
            <strong>Emergency:</strong>
            <?php echo $latest_request["emergency_category"]; ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?php echo $latest_request["request_status"]; ?>
        </p>

        <a href="track.php" class="btn">
            📍 Track Request
        </a>

    </div>

<?php } ?>

    <p>
        Welcome to Ambulance Tracking
        & Emergency System
    </p>


    <div class="dashboard-cards">


        <!-- Emergency Card -->

        <div class="feature-card">

            <h2>🚨 Emergency</h2>

            <p>
                Request an ambulance
                during an emergency.
            </p>

            <a
                href="request_ambulance.php"
                class="btn"
            >
                Request Ambulance
            </a>

        </div>


        <!-- Ambulance Card -->

        <div class="feature-card">

            <h2>🚑 Ambulances</h2>

            <p>
                View available ambulances
                and driver details.
            </p>

            <a
                href="ambulances.php"
                class="btn"
            >
                View Ambulances
            </a>

        </div>


        <!-- Hospital Card -->

        <div class="feature-card">

            <h2>🏥 Hospitals</h2>

            <p>
                View hospitals and
                emergency facilities.
            </p>

            <a
                href="hospitals.php"
                class="btn"
            >
                View Hospitals
            </a>

        </div>


        <!-- Tracking Card -->

        <div class="feature-card">

            <h2>📍 Track</h2>
<div class="feature-card">

    <h2>🗺️ Ambulance Location</h2>

    <p>
        This section displays the current ambulance location.
    </p>

    <iframe
        src="https://www.openstreetmap.org/export/embed.html?bbox=78.45%2C17.35%2C78.55%2C17.45&layer=mapnik"
        width="100%"
        height="400"
        style="border:1px solid black;"
        loading="lazy">
    </iframe>

</div>
            <p>
                Check your ambulance
                request status.
            </p>

            <a
                href="track.php"
                class="btn"
            >
                Track Ambulance
            </a>

        </div>


    </div>

</div>

</body>

</html>
