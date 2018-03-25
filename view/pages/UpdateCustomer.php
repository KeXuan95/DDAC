<?php
//Access Control
if ($_SESSION['position'] != "member") {
    header('Location: Error403');
    exit();
}

date_default_timezone_set("Asia/Kuala_Lumpur");
$minTime = date('Y-m-d', time());

$update = Param::get('update');
$username = Param::get('username');
$contact = Param::get('contact');
$address = Param::get('address');
$password = Param::get('password');
$username = strtoupper($username);
$password = hash('sha256', $password);

$ID = $_SESSION['ID'];
$data_name = $_SESSION['fullname'];
$data_username = $_SESSION['username'];

$data_contact = '';
$data_address = '';

//update profile
if ($data_username == $username && $contact != '' && $address != '' && $password != '') {

    //update data here
    $sqlUpdate = "UPDATE Customer SET CustomerContact = '$contact',CustomerAddress = '$address', CustomerPassword = '$password' WHERE CustomerID = '$ID'";
    $saveData = sqlsrv_query( $conn, $sqlUpdate);
    if( $saveData === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    ?>
    <script>
        alert('Update successful!!!');
    </script>
    <?php
} elseif ($username != '' && $data_username != $username) {
    $error = true;
}

//get data
$sql = "SELECT CustomerContact,CustomerAddress FROM Customer WHERE CustomerID = '" . $ID . "'";
//  $stmt = sqlsrv_query($conn, $sql);
$stmt = sqlsrv_query($conn, $sql, array(), array("Scrollable" => "buffered"));

if (sqlsrv_fetch($stmt)) {

    $data_contact = sqlsrv_get_field($stmt, 0);
    $data_address = sqlsrv_get_field($stmt, 1);

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

<script>
    window.onload = function () {
        $('.ui.form').form({
            fields: {
                username: {
                    identifier: 'username',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter existing username'
                        }
                    ]
                },
                contact: {
                    identifier: 'contact',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter contact number'
                        }
                    ]
                },
                address: {
                    identifier: 'address',
                    rules: [
                        {
                            type: 'empty',
                            prompt: 'Please enter address'
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


<div class="ui vertical stripe segment">
    <div class="ui top aligned stackable grid container">
        <div class="row">
            <div class="ten wide column">
                <h1 class="ui header">Member Profile</h1>
            </div>
        </div>
        <div class="row">
            <div class="seven wide column">
                <div class="ui sticky">
                    <form class="ui form segment">
                        <div class="field">
                            <label>Member ID</label>
                            <div class="ui left input">
                                <label><?php echo $ID; ?></label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Name</label>
                            <div class="ui left input">
                                <label><?php echo $data_name; ?></label>
                            </div>
                        </div>
                        <div class="field">
                            <label>Username</label>
                            <div class="ui left input">
                                <input type="text" name="username" placeholder="Your Existing Username"/>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <br>
                        <div class="field">
                            <label>Contact</label>
                            <div class="ui left input">
                                <input type="text" name="contact" placeholder="Contact Number"
                                       value="<?php if ($data_contact != '') {
                                           echo $data_contact;
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>Address</label>
                            <div class="ui left input">
                                <input type="text" name="address" placeholder="Address"
                                       value="<?php if ($data_address != '') {
                                           echo $data_address;
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <div class="ui left input">
                                <input name="password" class="form-control" type="password" placeholder="Password" />
                            </div>
                        </div>
                        <button type="submit" class="ui fluid large primary submit button">Update</button>
                        <div class="ui message error" <?php if($error == true) echo 'style="display:block"'; ?>>
                            <?php
                            if($error == true){
                                echo "<li>Sorry, the username is incorrect</li>";
                                $error=false;
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>