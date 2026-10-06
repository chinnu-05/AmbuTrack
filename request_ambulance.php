<?php

include "includes/db.php";
include "includes/auth.php";

$message = "";

/* Get hospitals from database */
$hospitals = $conn->query(
    "SELECT * FROM medical_centers"
);


/* When user submits the form */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION["user_id"];

    $patient_name = $_POST["patient_name"];

    $emergency_category =
        $_POST["emergency_category"];

    $pickup_address =
        $_POST["pickup_address"];

    $center_id = $_POST["center_id"];


    /* Insert emergency request */

    $sql = "INSERT INTO rescue_requests
            (
                user_id,
                patient_name,
                emergency_category,
                pickup_address,
                center_id
            )
            VALUES (?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "isssi",
        $user_id,
        $patient_name,
        $emergency_category,
        $pickup_address,
        $center_id
    );


    if ($stmt->execute()) {

        $message =
        "🚑 Ambulance request submitted successfully!";

    } else {

        $message =
        "Request failed. Please try again.";

    }

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Request Ambulance - AmbuTrack</title>

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



<div class="form-container">

    <h2>🚨 Request Ambulance</h2>


    <?php if ($message != "") { ?>

        <p class="message">

            <?php echo $message; ?>

        </p>

    <?php } ?>



    <form method="POST">


        <label>
            Patient Name
        </label>


        <input
            type="text"
            name="patient_name"
            placeholder="Enter patient name"
            required
        >

        </br>

        <label>
            Emergency Type
        </label>


        <select
            name="emergency_category"
            required
        >

            <option value="">
                Select Emergency
            </option>

            <option value="Accident">
                Accident
            </option>

            <option value="Heart Problem">
                Heart Problem
            </option>

            <option value="Breathing Problem">
                Breathing Problem
            </option>

            <option value="Pregnancy">
                Pregnancy
            </option>

            <option value="Other">
                Other
            </option>

        </select>

         </br>

        <label>
            Pickup Location
        </label>


        <textarea
            name="pickup_address"
            placeholder="Enter pickup location"
            required
        ></textarea>

         </br>

        <label>
            Select Hospital
        </label>


        <select
            name="center_id"
            required
        >

            <option value="">
                Select Hospital
            </option>


            <?php while (
                $hospital =
                $hospitals->fetch_assoc()
            ) { ?>

                <option
                    value="<?php
                    echo $hospital["center_id"];
                    ?>"
                >

                    <?php
                    echo $hospital["hospital_name"];
                    ?>

                </option>

            <?php } ?>

        </select>

         </br>

        <button
            type="submit"
            class="btn"
        >

            🚑 Request Ambulance

        </button>
        <br><br>

<a href="tel:108" class="btn">
    📞 Call Emergency - 108
</a>


    </form>


</div>


</body>

</html>
