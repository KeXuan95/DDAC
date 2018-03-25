<script>
    $(document).ready(function () {
        $('.ui.dropdown').dropdown({
            'forceSelection': false
        });
        $('.ui.form').form();
    });
</script>

<?php
//Access Control
if ($_SESSION['position'] != "staff") {
    header('Location: Error403');
    exit();
}

date_default_timezone_set("Asia/Kuala_Lumpur");
$date = date('Y-m-d', time());
include 'database.php';
$sql = "SELECT shippingDate,source,destination,warehouse,shipmentID,status FROM shipmentSchedule where shippingDate >= '" . $date . "'";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    ?>
    <div class="ui vertical stripe segment">
        <div class="ui top aligned stackable grid container">
            <div class="row">
                <div class="ten wide column">
                    <h1 class="ui header">No Schedule</h1>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="ui vertical stripe segment">
        <div class="ui top aligned stackable grid container">
            <div class="row">
                <div class="ten wide column">
                    <h1 class="ui header">Shipping Details</h1>
                </div>
            </div>
            <div class="row">
                <div class="eleven wide column">
                    <table class="ui celled table selectable">
                        <thead>
                        <tr>
                            <th> Shipping ID</th>
                            <th> Source</th>
                            <th> Destination</th>
                            <th> Warehouse</th>
                            <th> Shipping Date</th>
                            <th> Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $row['shipmentID']; ?></td>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['destination']; ?></td>
                                <td><?php echo $row['warehouse']; ?></td>
                                <td><?php echo $row['shippingDate']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
}