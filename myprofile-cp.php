<?php
    require_once'includes/initialize.php'; 
//        $user = User::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));
        $user = User::find_by_id($session->id);
    if(filter_input(INPUT_POST, "submit")){
        if ($user->password == md5(trim(filter_input(INPUT_POST, "oldpassword",FILTER_DEFAULT)))){
            $user->password = md5(trim(filter_input(INPUT_POST, "password", FILTER_DEFAULT)));
                    if($user && $user->save()){
                     $message = "Profile updated successfully";
                }else {
           $message = "<div class='alert-danger'>''An error occured!</div>";
                }
        } else {
           $message = "<div class='alert-danger'>''Old Password Is Incorrect!</div>";
        } 


    
    }
    require_once 'layouts/header.php';
?>
      <div class="row-fluid">
   <?php require_once './layouts/sidepane.php'; ?>
        <script type="text/javascript" src="assets/js/lib.js"></script><script type="text/javascript" src="assets/js/raphael-min.js"></script>
<script type="text/javascript" src="assets/js/graffle.js"></script>
<div class="span9">
        <ul class="nav nav-tabs" id="" style="margin-bottom:0px;margin-left:5px;border-bottom: none;">
            <li class=""><a id="tabCompanyStructure" href="myprofile.php">Edit Your Profile</a></li>
            <li class="active"><a id="tabCompanyStructure" href="myprofile-cp.php">Change Password</a></li>
        </ul>
        <div class="tab-content">
                <div class="tab-pane active" id="tabPageCompanyStructure">
                        <div id="CompanyStructure" class="reviewBlock" data-content="List" style="padding-left:5px;">                            
                            <div id="grid_wrapper" class="dataTables_wrapper" role="grid">
                                    <?php  if(isset($message)){ echo "<div class='alert-info'>".$message."</div>" ;}  ?>
                                <h3>Change Password </h3>
                                <div id="msg"></div>
                                <form method='post' action="" enctype="multipart/form-data">
                                        <table class="table table-bordered">
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Od Password :</label></span></td>
                                            <td><input class="input-xxlarge" type="password" id="" oninput="" name="oldpassword" value="" placeholder="Enter Old Password..:"  validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">New Password:</label></span></td>
                                            <td><input class="input-xxlarge" type="password" id="" oninput="" name="password" value="" placeholder="Enter New Password..:"  validation/></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn btn-success" type="submit"  id="sub" name="submit" value="SAVE" /></td>
                                        </tr>
                                        </table>
                                </form>
                            </div>
                        </div>
                </div>
    </div><!--/.fluid-container-->
<?php require_once './layouts/footer.php'; 