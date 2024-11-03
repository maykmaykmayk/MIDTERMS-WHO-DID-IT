<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/functions.php'; ?>

<html>
    <head>
        <title>Edit Service</title>
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <h3>Please edit the service details as needed.</h3>

        <?php
        if (!isset($_GET['customers_id']) || !isset($_GET['service_id'])) {
            echo "<h2>Invalid request. Missing service or customer ID.</h2>";
            exit;
        }

        $customerData = getCustomerByID($pdo, $_GET['customers_id']);
        if (!$customerData) {
            echo "<h2>Customer not found.</h2>";
            exit;
        }
        ?>
        <b>Currently viewing:</b> <br>
        <b>Customer ID:</b> <?php echo $customerData['customers_id']; ?> <br>
        <b>Customer Name:</b> <?php echo $customerData['first_name'] . ' ' . $customerData['last_name']; ?> <br><br>

        <?php
        $serviceData = getServiceByID($pdo, $_GET['service_id']);
        if (!$serviceData) {
            echo "<h2>Service not found.</h2>";
            exit;
        }
        ?>

        <form action="core/handleForms.php?service_id=<?php echo $_GET['service_id']; ?>&customers_id=<?php echo $_GET['customers_id']; ?>" method="POST">
            <label for="service_name">Service Name:</label>
            <select name="service_name" id="service_name">
                <option value="Oil Change" <?php echo ($serviceData['service_name'] == 'Oil Change') ? 'selected' : ''; ?>>Oil Change</option>
                <option value="Tire Rotation" <?php echo ($serviceData['service_name'] == 'Tire Rotation') ? 'selected' : ''; ?>>Tire Rotation</option>
                <option value="Brake Repair" <?php echo ($serviceData['service_name'] == 'Brake Repair') ? 'selected' : ''; ?>>Brake Repair</option>
                <option value="Battery Replacement" <?php echo ($serviceData['service_name'] == 'Battery Replacement') ? 'selected' : ''; ?>>Battery Replacement</option>
                <option value="Transmission Service" <?php echo ($serviceData['service_name'] == 'Transmission Service') ? 'selected' : ''; ?>>Transmission Service</option>
                <option value="Wheel Alignment" <?php echo ($serviceData['service_name'] == 'Wheel Alignment') ? 'selected' : ''; ?>>Wheel Alignment</option>
                <option value="Engine Diagnostics" <?php echo ($serviceData['service_name'] == 'Engine Diagnostics') ? 'selected' : ''; ?>>Engine Diagnostics</option>
                <option value="Car Wash" <?php echo ($serviceData['service_name'] == 'Car Wash') ? 'selected' : ''; ?>>Car Wash</option>
            </select>
            <br>

            <label for="service_date">Service Date:</label>
            <input type="date" name="service_date" id="service_date" value="<?php echo $serviceData['service_date']; ?>" required> <br>

            <input type="submit" name="editServiceButton" value="Apply Changes">
        </form>

        <input type="button" value="Cancel" onclick="window.location.href='viewCustomerServices.php?customers_id=<?php echo $_GET['customers_id']; ?>';">
    </body>
</html>
