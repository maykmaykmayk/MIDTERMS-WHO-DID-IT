<?php
session_start(); 
require_once 'core/dbConfig.php'; 
require_once 'core/functions.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<html>
    <head>
        <title>Fast Cars Autoshop</title>
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <h2>Welcome to Fast Cars Autoshop! Enter your Details to proceed for our Car services.</h2>
        
        <form action="core/handleForms.php" method="POST">
            <label for="first_name">First name</label>
            <input type="text" name="first_name" id="first_name" required> <br>

            <label for="last_name">Last name</label>
            <input type="text" name="last_name" id="last_name" required> <br>

            <label for="age">Age</label>
            <input type="number" name="age" id="age" min="0" required> <br>

            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Prefer Not To Say">Prefer Not To Say</option>
            </select> <br>

            <input type="submit" name="addCustomerButton" value="Submit">
        </form> <br>

        <h3>Our offered Services!</h3>
        <table>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Date Registered</th>
                <th>Done By</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>

            <?php 
            $allCustomersData = getAllCustomers($pdo); 
            foreach ($allCustomersData as $row) { ?>
            <tr>
                <td><?php echo $row['customers_id']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['date_registered']; ?></td>
                <td><?php echo $row['done_by']; ?></td>
                <td><?php echo $row['date_logged']; ?></td>
          
                <td style="max-width: 350px;">
                    <input type="button" value="View Services" onclick="window.location.href='viewServices.php?customers_id=<?php echo $row['customers_id']; ?>';">
                    <input type="button" value="Edit Customer" onclick="window.location.href='editCustomer.php?customers_id=<?php echo $row['customers_id']; ?>';">
                    <input type="button" value="Remove Customer" onclick="window.location.href='removeCustomer.php?customers_id=<?php echo $row['customers_id']; ?>';">
                </td>
            </tr>
            <?php } ?>
        </table>

        <form action="../login.php" method="POST">
            <input type="submit" name="logoutButton" value="Logout">
        </form>
    </body>
</html>
