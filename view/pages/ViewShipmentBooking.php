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
if ($_SESSION['position'] != "member") {
    header('Location: Error403');
    exit();
}

$ID = $_SESSION['ID'];
include 'database.php';
//Get Bookings

$sql = "SELECT ShipmentBooking.BookingID,ShipmentBooking . CustomerID,ShipmentBooking . shipmentID, ShipmentSchedule .source , ShipmentSchedule .destination , ShipmentSchedule . status FROM ShipmentBooking JOIN ShipmentSchedule ON (ShipmentSchedule . shipmentID = ShipmentBooking . shipmentID) WHERE ShipmentBooking.CustomerID = '" . $ID . "'";
$stmt = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static' ));
$haveBooked = sqlsrv_num_rows($stmt);

if ($haveBooked == 0) {
    ?>
    <div class="ui vertical stripe segment">
        <div class="ui top aligned stackable grid container">
            <div class="row">
                <div class="ten wide column">
                    <h1 class="ui header">No Booking Yet</h1>
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
                    <h1 class="ui header">Booking</h1>
                </div>
            </div>
            <div class="row">
                <div class="eleven wide column">
                    <table class="ui celled table selectable">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> Booking ID</th>
                            <th> Shipping ID</th>
                            <th> Source</th>
                            <th> Destination</th>
                            <th> Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $num = 1;
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $row['BookingID']; ?></td>
                                <td><?php echo $row['shipmentID']; ?></td>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['destination']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                            <?php
                            $num = $num + 1;
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