<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/functions.php'; ?>

<html>
    <head>
        <title>Fast Cars Autoshop Services</title>
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <input type="submit" value="Return to home page" onclick="window.location.href='index.php'">

        <?php 
    
        if (isset($_GET['customers_id']) && is_numeric($_GET['customers_id'])) {
            $customers_id = (int)$_GET['customers_id']; 
            $CustomersIDData = getCustomerByID($pdo, $customers_id); 
        ?> 
        <br><br>
        <b>Customer ID:</b> <?php echo $CustomersIDData['customers_id']; ?> <br>
        <b>First Name:</b> <?php echo $CustomersIDData['first_name'] . ' ' . $CustomersIDData['last_name']; ?>

        <h3>ADD A NEW SERVICE:</h3>
        <form action="core/handleForms.php?customers_id=<?php echo $customers_id; ?>" method="POST">
            <label for="service_name">Service Name</label>
            <select name="services" id="services">
                <option value="Oil Change">Oil Change</option>
                <option value="Tire Rotation">Tire Rotation</option>
                <option value="Brake Repair">Brake Repair</option>
                <option value="Battery Replacement">Battery Replacement</option>
                <option value="Transmission Service">Transmission Service</option>
                <option value="Alignment">Wheel Alignment</option>
                <option value="Engine Diagnostics">Engine Diagnostics</option>
                <option value="Car Wash" selected>Car Wash</option>
            </select>
            <br>

            <label for="service_date">Service Date</label>
            <input type="date" name="service_date" id="service_date" required> <br>

            <input type="submit" name="addServiceButton" value="Add Service">
        </form> <br>

        <h3>Customer <?php echo $CustomersIDData['first_name']; ?>'s On-going Services:</h3>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Date</th>
                <th>Actions</th>
            </tr>

            <?php 
        
            $CustomerServicesData = getServicesByCustomersID($pdo, $customers_id); 

            if (!empty($CustomerServicesData)) {
                foreach ($CustomerServicesData as $row) { ?>
                <tr>
                    <td><?php echo $row['service_id']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['service_date']; ?></td>
                    <td>
                        <input type="button" value="Edit Service" onclick="window.location.href='editService.php?service_id=<?php echo $row['service_id']; ?>&customers_id=<?php echo $customers_id; ?>';">
                        <input type="button" value="Remove Service" onclick="window.location.href='removeService.php?service_id=<?php echo $row['service_id']; ?>&customers_id=<?php echo $customers_id; ?>';">
                    </td>
                </tr>
                <?php } 
            } else {
                echo "<tr><td colspan='4'>No services found for this customer.</td></tr>";
            } ?>
        </table>
        <?php 
        } else {
            echo "<h2>Service successfully removed.</h2>";
        } 
        ?>
    </body>
</html>
