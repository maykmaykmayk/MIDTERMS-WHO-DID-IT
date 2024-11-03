<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/functions.php'; ?>

<html>
    <head>
        <title>Edit Customer</title>
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <h3>Please edit the customer details as needed.</h3>
        
        <?php
        
        $customerData = getCustomerByID($pdo, $_GET['customers_id']);
        ?>

        <form action="core/handleForms.php?customers_id=<?php echo $_GET['customers_id']; ?>" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $customerData['first_name']; ?>" required> <br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $customerData['last_name']; ?>" required> <br>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?php echo $customerData['age']; ?>" required> <br>
            
            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="Male" <?php echo ($customerData['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($customerData['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Prefer Not To Say" <?php echo ($customerData['gender'] == 'Prefer Not To Say') ? 'selected' : ''; ?>>Prefer Not To Say</option>
            </select> <br>

            <input type="submit" name="editCustomerButton" value="Apply Changes">
        </form>

        <input type="button" value="Cancel" onclick="window.location.href='index.php'">
    </body>
</html>
