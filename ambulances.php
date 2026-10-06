<?php

include "includes/db.php";
include "includes/auth.php";

$sql = "SELECT * FROM fleet_units
        ORDER BY unit_id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Ambulances - AmbuTrack</title>

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

        <a href="request_ambulance.php">
            Emergency
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

    <h1>🚑 Available Ambulances</h1>

    <div class="table-container">

        <table>

            <tr>

                <th>Vehicle No.</th>

                <th>Driver Name</th>

                <th>Driver Mobile</th>

                <th>Vehicle Type</th>

                <th>Status</th>

            </tr>


            <?php while ($row = $result->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $row["vehicle_number"]; ?>
                </td>
                
                <td>
                    <?php echo $row["driver_name"]; ?>
                </td>
                
                <td>
                    <?php echo $row["driver_mobile"]; ?>
                </td>
                
                <td>
                    <?php echo $row["vehicle_type"]; ?>
                </td>
                
                <td>
                    <?php echo $row["availability_status"]; ?>
                </td>
                
            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>
