<?php 
session_start(); 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 
?>

<html>
<head>
    <title>Fast Cars Autoshop</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <h2>Welcome to Fast Cars Autoshop! Please log in below:</h2>

    <?php if (isset($_SESSION['message'])) { ?>
        <h1 style="color: red;"><?php echo $_SESSION['message']; ?></h1>
    <?php } unset($_SESSION['message']); ?>

    <form action="core/handleForms.php" method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" required> <br>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required> <br>

    <input type="submit" name="loginButton" value="Login">
</form>

    <input type="button" value="Register" onclick="window.location.href='register.php'">
</body>
</html>
