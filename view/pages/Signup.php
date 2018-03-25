<?php
//Access Control
if (isset($_SESSION['username'])) {
    header('Location: ViewShipmentBooking');
    exit();
}
?>
<?php
//Validate signup
$username = Param::get('username');
$fullname = Param::get('fullname');
$password = Param::get('password');

if ($username != '' & $fullname != '' & $password != '') {
    $username = strtoupper($username);
    $fullname = strtoupper($fullname);
    $password = hash('sha256', $password);

    include 'database.php';

    //Check DB for existing user
    $sql = "SELECT CustomerUsername FROM Customer WHERE CustomerUsername = '" . $username . "'";
    //  $stmt = sqlsrv_query($conn, $sql);
    $stmt = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));

    if (sqlsrv_fetch($stmt)) {
        $error = true;
    } else {
        $sqlInsert = "INSERT into Customer(CustomerName,CustomerUsername,CustomerPassword) values ('" . $fullname . "','" . $username . "','" . $password . "')";

        $saveData = sqlsrv_query($conn, $sqlInsert);
        if ($saveData === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        ?>
        <script>
            alert('Signup successful!!!');
        </script>
        <?php
        header('Location: Login');
        exit();
    }
}
?>

<script>
    window.onload = function () {
        $('.ui.form').form({
            fields: {
                name: {
                    identifier: 'fullname',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter your full name'
                        }
                    ]
                },
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
                        },
                        {
                            type: 'minLength[6]',
                            prompt: 'Your password must be at least {ruleValue} characters'
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
                Create New Account
            </div>
        </h2>
        <form class="ui form segment">
            <div class="field">
                <div class="ui left input">
                    <input type="text" name="fullname" placeholder="Full Name"/>
                </div>
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
            <button type="submit" class="ui fluid large primary submit button">Sign Up</button>
            <div class="ui message error" <?php if ($error == true) echo 'style="display:block"'; ?>>
                <?php
                if ($error == true) {
                    echo "<li>Sorry, the username has already been taken</li>";
                    $error = false;
                }
                ?>
            </div>
        </form>
        <div class="ui message">
            Already an existing user? <a href="/Login"> Log In</a>
        </div>
        <br>
    </div>
</div>