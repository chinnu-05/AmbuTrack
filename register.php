<?php

include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];

    // Convert password into a secure format
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert user into app_users table
    $sql = "INSERT INTO app_users
            (full_name, email_address, mobile_number, login_password)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $name,
        $email,
        $phone,
        $hashed_password
    );

    if ($stmt->execute()) {

        $message = "Registration successful!";

    } else {

        $message = "Registration failed. Email may already exist.";

    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Register - AmbuTrack</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar">

    <div class="logo">
        🚑 AmbuTrack
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="login.php">Login</a>

        <a href="register.php">Register</a>

    </div>

</nav>


<div class="form-container">

    <h2>Create Account</h2>

    <?php

    if ($message != "") {

        echo "<p class='message'>$message</p>";

    }

    ?>


    <form method="POST">

        <label>Full Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter your full name"
            required
        >
       </br>

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >
        </br>

        <label>Mobile Number</label>

        <input
            type="text"
            name="phone"
            placeholder="Enter your mobile number"
            required
        >

        </br>
        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Create a password"
            required
        >
        </br>
        <button type="submit" class="btn">
            Register
        </button>

    </form>


    <p>

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </p>

</div>

</body>

</html>
