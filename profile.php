<?php
require_once'includes/initialize.php'; 
    $user = User::find_by_id($session->id);
    if(filter_input(INPUT_POST, "submit")){
         
        if ($user->password == md5(trim(filter_input(INPUT_POST, "oldpassword",FILTER_DEFAULT)))){
            $user->password = md5(trim(filter_input(INPUT_POST, "password", FILTER_DEFAULT)));
            if (trim(filter_input(INPUT_POST, "username",FILTER_DEFAULT)) != ""){
                $user->username = trim(filter_input(INPUT_POST, "username", FILTER_DEFAULT));
            }
            if($user && $user->save()){
                $message = "Profile updated successfully";
            }else {
                $message = "<div class='alert-danger'>''An error occured!</div>";
            }
        } else {
           $message = "<div class='alert-danger'>Old Password Is Incorrect!</div>";
        } 

    }
require_once 'layouts/header.php';
require_once './layouts/sidepane.php'; ?>
       <div class="container1">
    <h3 style="text-align: center; ">Profile</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <div id="container2">
        <form method='post' action="">  
            <table class="form_table">
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Username</label></span></td>
                <td><input  class="input-xxlarge" type="text" id="username" oninput="" name="username" value="<?php echo $user->username ?>" placeholder="Enter Username..:" validation/></td>
            </tr>
             <tr>
                <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Current Password:</label></span></td>
                <td><input  class="input-xxlarge" type="text" id="oldpassword" name="oldpassword"  placeholder="Enter Current Password"  validation/></td>              
            </tr>        
             <tr>
                <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">New Password:</label></span></td>
                <td><input  class="input-xxlarge" type="text" id="password" name="password" placeholder="Enter New Password"  validation/></td>
            </tr>                       
            <tr>
                <td>&nbsp;</td>
                <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="reset" name="" value="RESET FORM" class="btn btn-danger" /></td>
                <td></td>
                <td></td>
            </tr>
            </table>
    </form>
  <?php
  /* for ($i = (48); $i >= 1; $i--){
              $date = new DateTime(date("Y-m-d"));    
              $date->sub(new DateInterval("P".$i. "M") );     
              echo $date->format('F, Y');
              $date->modify('first day of this month');
			  echo " - ". $date->format('d F, Y');
			  $date->modify('last day of this month');
			  echo " - ".  $date->format('d F, Y');
              echo " - <br />";
}*/
              ?>
    </div>
</div>
<?php require_once './layouts/footer.php'; 