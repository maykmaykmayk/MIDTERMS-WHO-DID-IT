<?php
session_start(); 
require_once 'core/dbConfig.php';
require_once 'core/functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
?>

<html>
<head>
    <title>Fast Cars Autoshop </title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h2>Service Logs</h2>

        <input type="button" value="Return To Your Profile" onclick="window.location.href='index.php'" style="margin-bottom: 20px;">

        <table>
            <tr>
                <th>Log ID</th>
                <th>Log Description</th>
                <th>Service ID</th>
                <th>Customer ID</th>
                <th>Action Done By</th>
                <th>Date Logged</th>
            </tr>

            <?php $serviceLogs = getServiceLogs($pdo); ?>
            <?php foreach ($serviceLogs as $row) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['log_id']); ?></td>
                <td><?php echo htmlspecialchars($row['log_desc']); ?></td>
                <td><?php echo htmlspecialchars($row['service_id']); ?></td>
                <td><?php echo htmlspecialchars($row['customers_id']); ?></td>
                <td><?php echo htmlspecialchars($row['done_by']); ?></td>
                <td><?php echo htmlspecialchars($row['date_logged']); ?></td>
            </tr>
            <?php } ?>
        </table>  
    </div>
</body>
</html>
