<?php
//Access Control
if ($_SESSION['position'] != "admin") {
    header('Location: Error403');
    exit();
}

date_default_timezone_set("Asia/Kuala_Lumpur");
include 'database.php';
$TodayDate = date('Y-m-d', time());

//for showing schedule
$upcomingSchedule = true;
$sqlSchedule = "SELECT * FROM shipmentSchedule where shippingDate >= '" . $TodayDate . "'";
$stmtSchedule = sqlsrv_query($conn, $sqlSchedule, array(), array( "Scrollable" => 'static' ));
$haveSchedule = sqlsrv_num_rows($stmtSchedule);

if ($haveSchedule == 0) {
    $upcomingSchedule = false;
}

// for saving
$minTime = date('Y-m-d', time());
$source = Param::get('source');
$destination = Param::get('destination');
$date = Param::get('date');
$warehouse = Param::get('warehouse');

if ($source != $destination && $date != '') {


    //Check DB for existing user
    $sql = "SELECT * FROM shipmentSchedule WHERE shippingDate = '" . $date . "'";
    //  $stmt = sqlsrv_query($conn, $sql);
    $stmt = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));
    $total = sqlsrv_num_rows($stmt);
    $dateShip = date("Ymd", strtotime($date));

    if ($total == 0) {
        $ShipID = 1;
    } else {
        $ShipID = $total + 1;
    }

    $ShipID = sprintf("%02d", $ShipID);
    $ShipID = $dateShip . $ShipID;
    $sqlInsert = "INSERT into shipmentSchedule(shipmentID,source,destination,shippingDate,warehouse,status) values ('" . $ShipID . "','" . $source . "','" . $destination . "','" . $date . "','" . $warehouse . "','Pending')";

    $saveData = sqlsrv_query($conn, $sqlInsert);
    if ($saveData === false) {
        ?>
        <script>
            alert('Error adding schedule!!!');
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Schedule added successful!!!');
            window.location = 'AddShipmentSchedule';
        </script>
        <?php
    }

} elseif ($source != '' && $source == $destination) {
    //Check for input and redirect
    ?>
    <script>
        alert('Destination need to be different from Source!!!');
        window.location = 'AddShipmentSchedule';
    </script>
    <?php
}

?>

<style>
    #container {
        width: 100%;
        display: inline-block;
        position: relative;
    }

    #container:after {
        /* Maintaining aspect ratio */
        padding-top: 206.26%;
        display: block;
        content: '';
    }

    #container > div {
        position: absolute !important;
    }
</style>


<div class="ui vertical stripe segment">
    <div class="ui top aligned stackable grid container">
        <div class="row">
            <div class="five wide column">
                <h1 class="ui header">Schedule</h1>
            </div>
            <?php
            if ($upcomingSchedule == true) {
                ?>
                <div class="five wide column">
                    <h1 class="ui header">Upcoming Schedule</h1>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="five wide column">
                <div class="ui sticky">
                    <form class="ui form segment">
                        <div class="field">
                            <label>Shipping Date</label>
                            <input type="date" name="date" class="ui input fluid"
                                   min="<?php echo htmlspecialchars($minTime); ?>"
                                   value="<?php echo htmlspecialchars($minTime); ?>"/>
                        </div>

                        <div class="field">
                            <label>Shipping Source</label>
                            <select name="source" class="ui search dropdown fluid">
                                <option value="Austin">Austin - US (AUS)</option>
                                <option value="Bangkok">Bangkok (BKK)</option>
                                <option value="Kuala Lumpur">Kuala Lumpur (KUL)</option>
                                <option value="Haneda">Tokyo - Haneda (HND)</option>
                                <option value="Sydney">Sydney (SYD)</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Shipping Destination</label>
                            <select name="destination" class="ui search dropdown fluid">
                                <option value="Sydney">Sydney (SYD)</option>
                                <option value="Haneda">Tokyo - Haneda (HND)</option>
                                <option value="Kuala Lumpur">Kuala Lumpur (KUL)</option>
                                <option value="Bangkok">Bangkok (BKK)</option>
                                <option value="Austin">Austin - US (AUS)</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Warehouse</label>
                            <select name="warehouse" class="ui search dropdown fluid">
                                <option value="A1">A 1</option>
                                <option value="A2">A 2</option>
                                <option value="B1">B 1</option>
                                <option value="B2">B 2</option>
                                <option value="C1">C 1</option>
                                <option value="C2">C 2</option>
                            </select>
                        </div>
                        <button type="submit" class="ui fluid large primary submit button">Add</button>
                    </form>
                </div>
            </div>
            <?php
            if ($upcomingSchedule == true) {
                ?>
                <div class="ten wide column">
                    <table class="ui celled table selectable">
                        <thead>
                        <tr>
                            <th> Shipment ID</th>
                            <th> Source</th>
                            <th> Destination</th>
                            <th> Warehouse</th>
                            <th> Shipping Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = sqlsrv_fetch_array($stmtSchedule, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $row['shipmentID']; ?></td>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['destination']; ?></td>
                                <td><?php echo $row['warehouse']; ?></td>
                                <td><?php echo $row['Shippingdate']; ?></td>

                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>