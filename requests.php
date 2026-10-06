<?php
include "../includes/db.php";
$message = "";
if (isset($_POST['update_request'])) {

    $request_id = $_POST['request_id'];
    $status = $_POST['request_status'];

    $sql = "UPDATE rescue_requests 
            SET request_status = ? 
            WHERE request_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $status, $request_id);

    $stmt->execute();

    $message = "Request updated successfully!";
}
/* UPDATE REQUEST STATUS */

/* UPDATE REQUEST STATUS */

if (isset($_GET["status"])) {

    $request_id = $_GET["request_id"];

    $status = $_GET["status"];

    $sql = "UPDATE rescue_requests
            SET request_status = ?
            WHERE request_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "si",
        $status,
        $request_id
    );

    $stmt->execute();


    /* MAKE AMBULANCE AVAILABLE AFTER COMPLETION */

    if ($status == "Completed") {

        $sql2 = "SELECT unit_id
                 FROM rescue_requests
                 WHERE request_id = ?";

        $stmt2 = $conn->prepare($sql2);

        $stmt2->bind_param(
            "i",
            $request_id
        );

        $stmt2->execute();

        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {

            $row2 = $result2->fetch_assoc();

            $unit_id = $row2["unit_id"];

            if ($unit_id != NULL) {

                $sql3 = "UPDATE fleet_units
                         SET availability_status = 'Available'
                         WHERE unit_id = ?";

                $stmt3 = $conn->prepare($sql3);

                $stmt3->bind_param(
                    "i",
                    $unit_id
                );

                $stmt3->execute();
            }
        }
    }
}

/* Assign ambulance */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $request_id = $_POST["request_id"];
    $unit_id = $_POST["unit_id"];


    $sql = "UPDATE rescue_requests
            SET unit_id = ?,
                request_status = 'Assigned'
            WHERE request_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $unit_id,
        $request_id
    );


    if ($stmt->execute()) {

        /* Change ambulance status */

        $update_ambulance =
            "UPDATE fleet_units
             SET availability_status = 'Busy'
             WHERE unit_id = ?";

        $stmt2 =
            $conn->prepare($update_ambulance);

        $stmt2->bind_param(
            "i",
            $unit_id
        );

        $stmt2->execute();


        $message =
        "🚑 Ambulance assigned successfully!";

    } else {

        $message =
        "Assignment failed.";

    }

}


/* Get emergency requests */

$sql = "SELECT
            rescue_requests.*,
            app_users.full_name,
            app_users.mobile_number,
            medical_centers.hospital_name,
            fleet_units.vehicle_number,
            fleet_units.driver_name

        FROM rescue_requests

        LEFT JOIN app_users
        ON rescue_requests.user_id =
           app_users.user_id

        LEFT JOIN medical_centers
        ON rescue_requests.center_id =
           medical_centers.center_id

        LEFT JOIN fleet_units
        ON rescue_requests.unit_id =
           fleet_units.unit_id

        ORDER BY rescue_requests.request_id DESC";


$result = $conn->query($sql);


/* Get available ambulances */

$ambulances = $conn->query(
    "SELECT *
     FROM fleet_units
     WHERE availability_status = 'Available'"
);

?>

<!DOCTYPE html>

<html>

<head>

    <title>
        Emergency Requests - Admin
    </title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<nav class="navbar">

    <div class="logo">
        🚑 AmbuTrack Admin
    </div>


    <div class="nav-links">

        <a href="index.php">
            Admin Home
        </a>

        <a href="../dashboard.php">
            User Dashboard
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </div>

</nav>



<div class="dashboard">

    <h1>
        🚨 Emergency Requests
    </h1>


    <?php if ($message != "") { ?>

        <p class="message">

            <?php echo $message; ?>

        </p>

    <?php } ?>



    <div class="table-container">

        <table>

            <tr>

                <th>Request ID</th>

                <th>User</th>

                <th>Patient</th>

                <th>Emergency</th>

                <th>Pickup</th>

                <th>Hospital</th>

                <th>Status</th>

                <th>Ambulance</th>

                <th>Driver</th>

                <th>Action</th>

            </tr>


            <?php

            if ($result->num_rows > 0) {

                while (
                    $row =
                    $result->fetch_assoc()
                ) {

            ?>


            <tr>

                <td>
                    <?php
                    echo $row["request_id"];
                    ?>
                </td>


                <td>
                    <?php
                    echo $row["full_name"];
                    ?>
                </td>


                <td>
                    <?php
                    echo $row["patient_name"];
                    ?>
                </td>


                <td>
                    <?php
                    echo $row["emergency_category"];
                    ?>
                </td>


                <td>
                    <?php
                    echo $row["pickup_address"];
                    ?>
                </td>


                <td>
                    <?php
                    echo $row["hospital_name"];
                    ?>
                </td>

            
            </tr>
    <?php

if ($row["request_status"] == "Pending") {
?>

<form method="POST">

    <input
        type="hidden"
        name="request_id"
        value="<?php echo $row["request_id"]; ?>"
    >

    <select name="request_status">
        <option value="Accepted">Accept</option>
        <option value="Rejected">Reject</option>
    </select>

    <button type="submit" name="update_request">
        Update
    </button>

</form>

<?php
}
?>

    ?>

        <form method="POST">

            <input
                type="hidden"
                name="request_id"
                value="<?php
                echo $row["request_id"];
                ?>"
            >

            <select
                name="unit_id"
                required
            >

                <option value="">
                    Select Ambulance
                </option>

                <?php

                $available =
                    $conn->query(
                        "SELECT *
                         FROM fleet_units
                         WHERE availability_status
                         = 'Available'"
                    );

                while (
                    $ambulance =
                    $available->fetch_assoc()
                ) {

                ?>

                    <option
                        value="<?php
                        echo $ambulance["unit_id"];
                        ?>"
                    >

                        <?php
                        echo
                        $ambulance["vehicle_number"];
                        ?>

                        

                        <?php
                        echo
                        $ambulance["driver_name"];
                        ?>

                    </option>

                <?php } ?>

            </select>

            <button
                type="submit"
                class="btn"
            >
                Assign
            </button>

        </form>


   

        <a
            href="requests.php?request_id=<?php
            echo $row["request_id"];
            ?>&status=Dispatched"
            class="btn"
        >
            Dispatched
        </a>

        <br><br>

        <a
            href="requests.php?request_id=<?php
            echo $row["request_id"];
            ?>&status=On%20the%20Way"
            class="btn"
        >
            On the Way
        </a>

        <br><br>

        <a
            href="requests.php?request_id=<?php
            echo $row["request_id"];
            ?>&status=Completed"
            class="btn"
        >
            Completed
        </a>

    <?php } ?>

</td>


                <td>

                    <?php

                    if ($row["vehicle_number"]) {

                        echo
                        $row["vehicle_number"];

                    } 
                    else {

                        echo "Not Assigned";

                    }

                    ?>

                </td>


                <td>

                    <?php

                    if ($row["driver_name"]) {

                        echo
                        $row["driver_name"];

                    } 
                    else {

                        echo "Not Assigned";

                    }

                    ?>

                </td>


                <td>


                    <?php

                    if (
                        $row["request_status"]
                        == "Pending"
                    ) {

                    ?>


                    <form method="POST">

                        <input
                            type="hidden"
                            name="request_id"
                            value="<?php
                            echo $row["request_id"];
                            ?>"
                        >


                        <select
                            name="unit_id"
                            required
                        >

                            <option value="">
                                Select Ambulance
                            </option>


                            <?php

                            $available =
                                $conn->query(
                                    "SELECT *
                                     FROM fleet_units
                                     WHERE availability_status
                                     = 'Available'"
                                );


                            while (
                                $ambulance =
                                $available->fetch_assoc()
                            ) {

                            ?>

                                <option
                                    value="<?php
                                    echo
                                    $ambulance["unit_id"];
                                    ?>"
                                >

                                    <?php
                                    echo
                                    $ambulance["vehicle_number"];
                                    ?>

                                    -

                                    <?php
                                    echo
                                    $ambulance["driver_name"];
                                    ?>

                                </option>

                            <?php

                            }

                            ?>

                        </select>


                        <button
                            type="submit"
                            class="btn"
                        >

                            Assign

                        </button>

                    </form>


                    <?php

                    } else {

                        echo "Assigned";

                    }

                    ?>


                </td>

            </tr>




            <tr>

                <td colspan="10">

                    No emergency requests found.

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
