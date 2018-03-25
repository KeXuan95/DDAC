<?php

$ID = $_SESSION['ID'];
$shippingID = Param::get('shippingID');
date_default_timezone_set("Asia/Kuala_Lumpur");
$now = date('Y-m-d', time());
include 'database.php';



//check booking.shippingID booking.userID to verify
$sql = "SELECT * FROM ShipmentBooking WHERE  CustomerID= '" . $ID . "' AND shipmentID = '" . $shippingID . "'";
$stmtBook = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));
$haveBooked = sqlsrv_num_rows($stmtBook);

if ($haveBooked == 0) {
    $sqlInsert = "INSERT into ShipmentBooking(CustomerID,bookingDate,shipmentID) values ('" . $ID . "','" . $now . "','" . $shippingID . "')";
    $saveData = sqlsrv_query($conn, $sqlInsert, array(), array("Scrollable" => "buffered"));
    if ($saveData === false) {
        ?>
        <script>
            alert('Book unsuccessful!!!');
            window.location = 'AddShipmentBooking';
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Book successful!!!');
            window.location = 'ViewShipmentBooking';
        </script>
        <?php
    }
} else {
    ?>
    <script>
        alert('Already Booked!!!');
        window.location = 'ViewShipmentBooking';
    </script>
    <?php
}

