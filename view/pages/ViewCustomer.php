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

include 'database.php';
//Get Member
$sql = "SELECT * FROM Customer";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    ?>
    <div class="ui vertical stripe segment">
        <div class="ui top aligned stackable grid container">
            <div class="row">
                <div class="ten wide column">
                    <h1 class="ui header">No Customer Yet</h1>
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
                    <h1 class="ui header">Customer Details</h1>
                </div>
            </div>
            <div class="row">
                <div class="eleven wide column">
                    <table class="ui celled table selectable">
                        <thead>
                        <tr>
                            <th> Customer ID</th>
                            <th> Name</th>
                            <th> Contact</th>
                            <th> Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $row['customerID']; ?></td>
                                <td><?php echo $row['CustomerName']; ?></td>
                                <td><?php echo $row['CustomerContact']; ?></td>
                                <td><?php echo $row['CustomerAddress']; ?></td>
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