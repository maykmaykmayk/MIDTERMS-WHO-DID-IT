<?php
require_once 'dbConfig.php';
require_once 'functions.php';
session_start(); 

if (isset($_POST['registerButton'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

    try {
        $checkUsernameStmt = $pdo->prepare("SELECT * FROM customers_accounts WHERE username = ?");
        $checkUsernameStmt->execute([$username]);
        if ($checkUsernameStmt->rowCount() > 0) {
            $_SESSION['message'] = "Username already exists!";
            header("Location: ../register.php");
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO customers_accounts (username, user_password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]); 

        $customers_id = $pdo->lastInsertId();

        $customerStmt = $pdo->prepare("INSERT INTO customers (customers_id, first_name, last_name, age, gender) VALUES (?, ?, ?, ?, ?)");
        $customerStmt->execute([$customers_id, $first_name, $last_name, $age, $gender]);

        $_SESSION['message'] = "Registration successful!";
        header("Location: ../login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header("Location: ../register.php");
        exit();
    }
}

if (isset($_POST['loginButton'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM customers_accounts WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user_id'] = $user['customers_id']; 
        $_SESSION['username'] = $username; 
        header("Location: ../index.php"); 
        exit(); 
    } else {
        $_SESSION['message'] = "Invalid username or password."; 
        header("Location: ../login.php"); 
        exit(); 
    }
}

if (isset($_POST['addCustomerButton'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $function = addCustomer($pdo, $first_name, $last_name, $age, $gender);
    if ($function) {
        header("Location: ../index.php");
    } else {
        echo "<h2>Customer addition failed.</h2>";
        echo '<a href="../index.php">';
        echo '<input type="button" id="returnHomeButton" value="Return to home page" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}

if (isset($_POST['editCustomerButton'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $customer_id = $_GET['customers_id'];

    $function = updateCustomer($pdo, $first_name, $last_name, $age, $gender, $customer_id);
    if ($function) {
        header("Location: ../index.php");
    } else {
        echo "<h2>Customer editing failed.</h2>";
        echo '<a href="../index.php">';
        echo '<input type="button" id="returnHomeButton" value="Return to home page" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}

if (isset($_POST['removeCustomerButton'])) {
    $customer_id = $_GET['customers_id'];

    $function = removeCustomer($pdo, $customer_id);
    if ($function) {
        header("Location: ../index.php");
    } else {
        echo "<h2>Customer removal failed.</h2>";
        echo '<a href="../index.php">';
        echo '<input type="button" id="returnHomeButton" value="Return to home page" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}

if (isset($_POST['editServiceButton'])) {
    $service_name = trim($_POST['service_name']);
    $service_date = $_POST['service_date'];
    $service_id = $_GET['service_id'];

    $function = updateService($pdo, $service_name, $service_date, $service_id);
    if ($function) {
        header("Location: ../viewServices.php?customers_id=" . $_GET['customers_id']);
    } else {
        echo "<h2>Service editing failed.</h2>";
        echo '<a href="../viewServices.php?customers_id=' . $_GET['customers_id'] . '">';
        echo '<input type="button" id="returnHomeButton" value="Return to services list" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}

if (isset($_POST['addServiceButton'])) {
    $customers_id = $_GET['customers_id'];
    $service_name = trim($_POST['services']);
    $service_date = $_POST['service_date'];
    $doneBy = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO services (customers_id, service_name, done_by, service_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$customers_id, $service_name, $doneBy, $service_date]); 

    if ($stmt) {
        header("Location: ../viewServices.php?customers_id=" . $customers_id);
    } else {
        echo "<h2>Service addition failed.</h2>";
        echo '<a href="../viewServices.php?customers_id=' . $customers_id . '">';
        echo '<input type="button" id="returnHomeButton" value="Return to services list" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}

if (isset($_POST['removeServiceButton'])) {
    $service_id = $_GET['service_id']; 

    $function = removeService($pdo, $service_id);
    if ($function) {
        header("Location: ../viewServices.php?customers_id=" . $_GET['customers_id']); 
    } else {
        echo "<h2>Service removal failed.</h2>";
        echo '<a href="../viewServices.php?customers_id=' . $_GET['customers_id'] . '">';
        echo '<input type="button" id="returnHomeButton" value="Return to services list" style="padding: 6px 8px; margin: 8px 2px;">';
        echo '</a>';
    }
}
?>
