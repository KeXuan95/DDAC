<div class="ui container">
    <a href="/ViewShipmentBooking" class="item">Shipment Booking</a>
    <a href="/UpdateCustomer" class="item">Personal Profile</a>
    <a href="/ShipmentBooking" class="item">Add Shipment Booking</a>
    <a href="/TrackShipmentBooking" class="item">Track Shipment Booking</a>
    <div class="right menu">
        <div class="item">
            <h4><?php echo $_SESSION['fullname']?></h4>
        </div>
        <div class="item">
            <a href="/logout" class="ui button">Log Out</a>
        </div>
    </div>
</div>