<?php
    require_once 'includes/initialize.php'; 
        $user = User::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

    if(filter_input(INPUT_POST, "submit")){
        $user->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        $user->username = trim(filter_input(INPUT_POST, "username", FILTER_DEFAULT));
        if(trim(filter_input(INPUT_POST, "password", FILTER_DEFAULT)) != ""){
            $user->password = md5(trim(filter_input(INPUT_POST, "password", FILTER_DEFAULT)));
            $user->login = "0000-00-00 00:00:00";
        }
        $user->role = trim(filter_input(INPUT_POST, "role", FILTER_DEFAULT));
        if($user && $user->save()){
            $session->message("User saved successfully");
            redirect_to("settings-users.php");
        }else {
            $message = "An error occured";
        }
    }
        if(filter_input(INPUT_POST, "cancel")){
            redirect_to("settings-users.php");
        }
    require_once 'layouts/header.php';
    require_once './layouts/sidepane.php';
?>
    <div class="container1">
    <h3 style="text-align: center; ">Users</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-users-add.php"><h4>Add User</h4></a></li>
        <li><a href="settings-users.php"><h4>All Users</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
                                        <table class="form_table">
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Name:</label></span></td>
                                            <td><input autofocus class="input-xxlarge" type="text" id="name"  name="name"placeholder="Enter Name..:" value="<?php echo $user->name;  ?>" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">User Name:</label></span></td>
                                            <td><input class="input-xxlarge" type="text" id="username" oninput="" name="username" value="<?php echo $user->username ?>" placeholder="Enter User Name..:" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Password:</label></span></td>
                                            <td><input class="input-xxlarge" type="password" id="password" oninput="" name="password" value="" placeholder="Type to change existing password..:" /></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Role:</label></span></td>
                                            <td>
                                                <select name="role" class="input-xxlarge" required>
                                                    <option value="">Please Select</option>
                                                    <option value="1" <?php if ($user->role ==1){ echo "selected"; }  ?>>Administrator</option>
                                                    <option value="2" <?php if ($user->role ==2){ echo "selected"; }  ?>>User</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="submit" name="cancel" value="CANCEL" class="btn btn-danger" /></td>
                                        </tr>
                                        </table>
                                </form>
    </div>
</div>
<?php require_once './layouts/footer.php'; 