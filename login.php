<?php

session_start();

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Find user using email
    $sql = "SELECT *
            FROM app_users
            WHERE email_address = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    // Check whether user exists
    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Check password
        if (password_verify(
            $password,
            $user["login_password"]
        )) {

            // Store user information in session
            $_SESSION["user_id"] =
                $user["user_id"];

            $_SESSION["user_name"] =
                $user["full_name"];

            // Go to dashboard
            header("Location: dashboard.php");

            exit();

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "User not found.";

    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Login - AmbuTrack</title>

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

        <a href="index.php">
            Home
        </a>

        <a href="register.php">
            Register
        </a>

    </div>

</nav>


<div class="form-container">

    <h2>🔐 Login</h2>


    <?php

    if ($message != "") {

        echo "<p class='message'>$message</p>";

    }

    ?>


    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        </br>
        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        </br>
        <button
            type="submit"
            class="btn"
        >
            Login
        </button>

    </form>


    <p>

        Don't have an account?

        <a href="register.php">
            Register here
        </a>

    </p>

</div>

</body>

</html>
