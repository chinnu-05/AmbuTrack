<?php

include "includes/db.php";
include "includes/auth.php";

$user_id = $_SESSION["user_id"];


$sql = "SELECT
            rescue_requests.*,
            fleet_units.vehicle_number,
            fleet_units.driver_name,
            medical_centers.hospital_name

        FROM rescue_requests

        LEFT JOIN fleet_units
        ON rescue_requests.unit_id =
           fleet_units.unit_id

        LEFT JOIN medical_centers
        ON rescue_requests.center_id =
           medical_centers.center_id

        WHERE rescue_requests.user_id = ?

        ORDER BY rescue_requests.request_id DESC";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

    <title>Track Ambulance - AmbuTrack</title>

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

        <a href="logout.php">
            Logout
        </a>

    </div>

</nav>



<div class="dashboard">

    <h1>
        📍 Track Ambulance
    </h1>


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


            <?php

            if ($result->num_rows > 0) {

                while (
                    $row =
                    $result->fetch_assoc()
                ) {

            ?>


            <tr>

                <td>
    <?php echo $row["patient_name"]; ?>
</td>

<td>
    <?php echo $row["emergency_category"]; ?>
</td>

<td>
    <?php echo $row["pickup_address"]; ?>
</td>

<td>
    <?php echo $row["hospital_name"]; ?>
</td>

<td>
    <?php echo $row["request_status"]; ?>
</td>


                <td>

                    <?php

                    if (
                        $row["vehicle_number"]
                    ) {

                        echo
                        $row["vehicle_number"];

                    } else {

                        echo "Not Assigned";

                    }

                    ?>

                </td>


                <td>
                  <?php echo $row["requested_at"]; ?>
               </td>

            </tr>


            <?php

                }

            } else {

            ?>


            <tr>

                <td
                    colspan="8"
                >

                    No ambulance requests found.

                </td>

            </tr>


            <?php

            }

            ?>

        </table>

    </div>

</div>


</body>

</html>
