<?php
//Access Control
if (isset($_SESSION['username'])) {
    header('Location: ViewShipmentBooking');
    exit();
}
?>
<?php
//Validate login
$username = Param::get('username');
$password = Param::get('password');
$position = Param::get('position');

if ($username != '' & $password != '') {
    include 'database.php';
    $password = hash('sha256', $password);
    $username = strtoupper($username);

    //member login
    if ($position == "member"){
        //Check DB
        $sql = "SELECT CustomerID,CustomerName,CustomerUsername FROM Customer WHERE CustomerUsername = '" . $username . "' AND CustomerPassword = '" . $password . "'";
        //  $stmt = sqlsrv_query($conn, $sql);
        $stmt = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));

        if (sqlsrv_fetch($stmt)) {
            $try = sqlsrv_num_rows($stmt);
            if ($try == 1) {
                $_SESSION['ID'] = sqlsrv_get_field($stmt, 0);
                $_SESSION['fullname'] = sqlsrv_get_field($stmt, 1);
                $_SESSION['username'] = sqlsrv_get_field($stmt, 2);
                $_SESSION['position'] = "member";
                header('Location: ViewShipmentBooking');
                exit();
            } else {
                ?>
                <script>
                    alert('Error occured!!!');
                </script>
                <?php
            }
        } else {
            $error = true;
        }
    }

    //staff login
    if ($position == "staff"){
        //Check DB
        $sql = "SELECT StaffID,StaffName,StaffUsername,position FROM staff WHERE StaffUsername = '" . $username . "' AND StaffPassword = '" . $password . "'";
        //  $stmt = sqlsrv_query($conn, $sql);
        $stmt = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));

        if (sqlsrv_fetch($stmt)) {
            $try = sqlsrv_num_rows($stmt);
            if ($try == 1) {
                $_SESSION['StaffID'] = sqlsrv_get_field($stmt, 0);
                $_SESSION['fullname'] = sqlsrv_get_field($stmt, 1);
                $_SESSION['username'] = sqlsrv_get_field($stmt, 2);
                $_SESSION['position'] = sqlsrv_get_field($stmt, 3);
                if ($_SESSION['position'] == "admin") {
                    header('Location: AddShipmentSchedule');
                    exit();
                } else {
                    header('Location: UpdateShipmentSchedule');
                    exit();
                }

            } else {
                ?>
                <script>
                    alert('Error occured!!!');
                </script>
                <?php
            }
        } else {
            $error = true;
        }
    }

}
?>

<script>
    window.onload = function () {
        $('.ui.form').form({
            fields: {
                username: {
                    identifier: 'username',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter a username'
                        }
                    ]
                },
                password: {
                    identifier: 'password',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter a password'
                        }
                    ]
                }
            }
        });
    }
</script>

<div class="ui middle center aligned stackable grid">
    <div class="column five wide">
        <h2 class="ui primary image header">
            <img src="/img/logo.png" class="image"/>
            <div class="content">
                Login to your account
            </div>
        </h2>
        <form class="ui form segment">
            <div class="field">
                <select name="position" class="ui search dropdown fluid">
                    <option value="member">Customer</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <div class="field">
                <div class="ui left input">
                    <input type="text" name="username" placeholder="Username"/>
                </div>
            </div>
            <div class="field">
                <div class="ui left input">
                    <input type="password" name="password" placeholder="Password"/>
                </div>
            </div>
            <button type="submit" class="ui fluid large primary submit button">Log In</button>
            <div class="ui message error" <?php if ($error == true) echo 'style="display:block"'; ?>>
                <?php
                if ($error == true) {
                    echo "<li>Invalid username or password</li>";
                    $error = false;
                }
                ?>
            </div>
        </form>
        <div class="ui message">
            New to us? <a href="/signup"> Sign Up</a>
        </div>
        <br>
    </div>
</div>