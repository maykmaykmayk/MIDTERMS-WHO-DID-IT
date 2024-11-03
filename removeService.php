<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/functions.php'; ?>

<html>
    <head>
        <title>Remove Service Confirmation</title>
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <h3>Are you sure you want to remove this service?</h3>

        <?php
        $serviceData = getServiceByID($pdo, $_GET['service_id']);
        if (!$serviceData) {
            echo "<h2>Service not found.</h2>";
            exit; 
        }
        ?>

        <b>Currently viewing:</b> <br>
        <b>Service ID:</b> <?php echo $serviceData['service_id']; ?> <br>
        <b>Service Name:</b> <?php echo $serviceData['service_name']; ?> <br><br>

        <form action="core/handleForms.php?service_id=<?php echo $_GET['service_id']; ?>" method="POST">
            <table>
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Service Description</th>
                    <th>Date Added</th>
                </tr>
                <tr>
                    <td><?php echo $serviceData['service_id']; ?></td>
                    <td><?php echo $serviceData['service_name']; ?></td>
                    <td><?php echo isset($serviceData['service_description']) ? $serviceData['service_description'] : 'N/A'; ?></td>
                    <td><?php echo isset($serviceData['date_added']) ? $serviceData['date_added'] : 'N/A'; ?></td>
                </tr>
            </table>

            <input type="submit" name="removeServiceButton" value="Remove">
        </form>

        <input type="button" value="Cancel" onclick="window.location.href='viewServices.php?customers_id=<?php echo $_GET['customers_id']; ?>';">
    </body>
</html>
