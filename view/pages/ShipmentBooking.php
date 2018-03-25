<?php
//Access Control
if ($_SESSION['position'] != "member") {
    header('Location: Error403');
    exit();
}

date_default_timezone_set("Asia/Kuala_Lumpur");
include 'database.php';
$now = date('Y-m-d', time());

$noBook = false;
$availableBooking = false;
// for saving
$source = Param::get('source');
$destination = Param::get('destination');

if ($source != '' && $source != $destination) {
   // echo date('d/m/Y', strtotime('+2 months'));
   // date_modify($now, '+1 month');
   // date('d M Y H:i:s', strtotime('+1 month', strtotime('Thu Mar 31 19:50:41 IST 2011')))
    $final = date('Y-m-d',strtotime('+1 month', strtotime($now)));
   // $final = date("Y-m-d", strtotime($now)) . " +1 month";
    //Check DB for shipping
    $sql = "SELECT * FROM shipmentSchedule WHERE source = '" . $source . "' AND destination = '" . $destination . "' AND shippingDate >= '". $now ."' AND shippingDate <= '" . $final . "'";
    $stmtBooking = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));

    $haveBooking = sqlsrv_num_rows($stmtBooking);
    if ($haveBooking != 0) {
        $availableBooking = true;
    } else {
        $noBook = true;
    }
} elseif ($source != '' && $source == $destination) {
    //Check for input and redirect
    ?>
    <script>
        alert('Destination need to be different from Source!!!');
        window.location = 'ShipmentBooking';
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
                <h1 class="ui header">Booking Date</h1>
            </div>
            <?php
            if ($availableBooking == true) {
                ?>
                <div class="ten wide column">
                    <h1 class="ui header">Available</h1>
                </div>
                <?php
            }
            if ($noBook == true) {
                ?>
                <div class="ten wide column">
                    <h1 class="ui header">No Schedule Yet Until <?php echo $final; ?></h1>
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
                        <button type="submit" class="ui fluid large primary submit button">Search</button>
                    </form>
                </div>
            </div>
            <?php
            if ($availableBooking == true) {
                $num = 1;
                ?>
                <div class="ten wide column">
                    <table class="ui celled table selectable">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> Shipping Date</th>
                            <th> Source</th>
                            <th> Destination</th>
                            <th> Warehouse</th>
                            <th> Selection</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = sqlsrv_fetch_array($stmtBooking, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $row['Shippingdate']; ?></td>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['destination']; ?></td>
                                <td><?php echo $row['warehouse']; ?></td>
                                <td>
                                    <form action='AddShipmentBooking?shippingID=<?php echo $row['shipmentID']; ?>' method="post">
                                        <input type="submit" name="submit" value="Select">
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $num = $num + 1;
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