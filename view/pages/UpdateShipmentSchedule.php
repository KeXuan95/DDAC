<?php
if ($_SESSION['position'] != "staff") {
    header('Location: Error403');
    exit();
}
date_default_timezone_set("Asia/Kuala_Lumpur");

$shippingID = Param::get('shippingID');
$stat = Param::get('stat');
if ($shippingID != '' && $stat != '') {
    //update data here
    $sqlUpdate = "UPDATE shipmentSchedule SET status = '$stat' WHERE shipmentID = '$shippingID'";
    $saveData = sqlsrv_query($conn, $sqlUpdate);
    if ($saveData === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        ?>
        <script>
            alert('Status Updated Successfully!!!');
        </script>
        <?php
    }
}

?>
    <style>
        .ui.header {
            padding-top: 1em;
        }
    </style>
    <script>
        window.onload = function () {
            $('.ui.form').form({
                fields: {
                    username: {
                        identifier: 'searchNumber',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Please enter tracking number'
                            }
                        ]
                    }
                }
            });
        }
    </script>

<?php
$searchNumber = Param::get('searchNumber');
if ($searchNumber != '') {

    //Check DB
    include 'database.php';
    //Get Bookings
    $sql = "SELECT source,destination,shippingDate,status FROM shipmentSchedule WHERE shipmentID = '" . $searchNumber . "'";
    $stmt = sqlsrv_query($conn, $sql);
    if (sqlsrv_fetch($stmt)) {
        $source = sqlsrv_get_field($stmt, 0);
        $destination = sqlsrv_get_field($stmt, 1);
        $date = sqlsrv_get_field($stmt, 2);
        $status = sqlsrv_get_field($stmt, 3);
    } else {
        ?>
        <script>
            alert('No record found!!!\nEnter a valid tracking number');
        </script>
        <?php
    }
}
?>

    <hr>
    <hr>
    <hr>
    <hr>
    <hr>
    <hr>

    <div class="ui vertical stripe quote segment">
        <div class="ui equal width stackable internally celled grid">
            <div class="ui middle aligned stackable grid container">
                <div class="row">
                    <div class="five wide column">
                        <h1 class="ui header">Shipping</h1>
                    </div>
                    <?php
                    if ($source != '') {
                        ?>
                        <div class="five wide column">
                            <h1 class="ui header">Update Status</h1>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="five wide column">
                        <h1>Track Shipment</h1>
                        <div class="internal">
                            <form class="ui form segment">
                                <div class="field">
                                    <div class="ui input">
                                        <input id="shipmentId" name="searchNumber"
                                               placeholder="Shipment ID" maxlength="10"
                                               class="form-control" type="text" required>
                                    </div>
                                    <button type="submit"
                                            class="ui button button--large button--full-width button--large">
                                        Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
                    if ($source != '') {
                        $today = date('Y-m-d', time());
                        ?>
                        <div class="five wide column">
                            <div class="ui sticky">
                                <form class="ui form segment">
                                    <div class="field">
                                        <label>Shipping ID</label>
                                        <div class="ui left input">
                                            <input id="ShipID" type="text" name="shippingID" readonly="readonly"
                                                   value="<?php echo $searchNumber; ?>"/>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Source</label>
                                        <div class="ui left input">
                                            <label><?php echo $source; ?></label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Destination</label>
                                        <div class="ui left input">
                                            <label><?php echo $destination; ?></label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Date</label>
                                        <div class="ui left input">
                                            <label><?php
                                                if ($date == $today) {
                                                    echo "Today";
                                                } else {
                                                    echo $date;
                                                }
                                                ?></label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Status</label>
                                        <?php
                                        if ($status == "Pending") {
                                            ?>
                                            <select name="stat" class="ui search dropdown fluid">
                                                <option value="Transit">Transit</option>
                                                <option value="Delivered">Delivered</option>
                                            </select>
                                            <?php
                                        } elseif ($status == "Transit") {
                                            ?>
                                            <select name="stat" class="ui search dropdown fluid">
                                                <option value="Delivered">Delivered</option>
                                            </select>
                                            <?php
                                        } else {
                                            ?>
                                            <h2>Delivered</h2>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if ($status != "Delivered") {
                                        ?>
                                        <button type="submit" class="ui fluid large primary submit button">Update</button>
                                        <?php
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($saveData);
sqlsrv_close($conn);