<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/functions.php'; ?>

<html>
<head>
    <title>Fast Cars Autoshop</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <h3>Are you sure you want to remove this customer and their associated services?</h3> <br>

    <?php
  
    $customerData = getCustomerByID($pdo, $_GET['customers_id']);
    
    $customerServicesData = getServicesByCustomersID($pdo, $_GET['customers_id']);
    ?>

    <form action="core/handleForms.php?customers_id=<?php echo $_GET['customers_id']; ?>" method="POST">
        
        <table>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Date Registered</th>
            </tr>
            <tr>
                <td><?php echo $customerData['customers_id']; ?></td>
                <td><?php echo $customerData['first_name']; ?></td>
                <td><?php echo $customerData['last_name']; ?></td>
                <td><?php echo $customerData['age']; ?></td>
                <td><?php echo $customerData['gender']; ?></td>
                <td><?php echo $customerData['date_registered']; ?></td>
            </tr>
        </table> <br>

    
        <h3>Associated Services:</h3>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Date</th>
            </tr>

            <?php if (!empty($customerServicesData)) { ?>
                <?php foreach ($customerServicesData as $row) { ?>
                <tr>
                    <td><?php echo $row['service_id']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['service_date']; ?></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="3">No services found for this customer.</td></tr>
            <?php } ?>
        </table>

        <input type="submit" name="removeCustomerButton" value="Remove Customer">
    </form>

    <input type="button" value="Cancel" onclick="window.location.href='index.php'">
</body>
</html>
