<?php require_once './includes/initialize.php'; 
if (!$session->is_logged_in()){
    $session->message("<div class='alert-danger'>You must be logged in!</div>");
    redirect_to("index.php");
   
}
    $user = User::find_by_id($session->id);
    if(filter_input(INPUT_POST, "submit")){        
        if ($user->password == md5(trim(filter_input(INPUT_POST, "oldpassword")))){
            $user->password = md5(trim(filter_input(INPUT_POST, "password", FILTER_DEFAULT)));
               $user->login = strftime("%Y-%m-%d %H:%M:%S", time());
               $user->ip = $_SERVER['REMOTE_ADDR'];
                    if($user && $user->save()){
                    $session->message("<div class='alert-info'>PROFILE UPDATED SUCCESSFULLY.</div>");
                    redirect_to("dashboard.php");
            }else {
           $message = "<div class='alert-danger'>''An error occured!</div>";
                }
        } else {
           $message = "<div class='alert-danger'>Old Password Is Incorrect!</div>";
        }    
    }
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>YOKS Fleet Management System Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery.placeholder.js"></script>
    <script src="assets/js/jquery.dataTables.js"></script>
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/DT_bootstrap.css?v=0.4" rel="stylesheet">
    <link href="assets/css/datepicker.css" rel="stylesheet">
    <link href="assets/css/style.css?v=2.4" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #0077b3;
      }
      body {
        padding-top: 40px; 
      }
      .container {
        width: 300px;
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; 
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

          .login-form {
                margin-left: 65px;
          }

          legend {
                margin-right: -50px;
                font-weight: bold;
                color: #404040;
          }

    </style>


  </head>

  <body>
        <div class="container">
                <div class="content" style="margin-top:60px;">
                        <div class="row">
                                <div class="login-form">
                                    <?php  if (isset($message)){ echo $message; } ?>
                                        <h3>Change Password</h3>
                                       <form method='post' action="" enctype="multipart/form-data">
                                        <table class="table table-bordered">
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Od Password :</label></span></td>
                                            <td><input class="input-block-level" type="password" required id="" oninput="" name="oldpassword" value="" placeholder="Enter Old Password..:"  validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">New Password:</label></span></td>
                                            <td><input class="input-block-level" type="password"   name="password" placeholder="Enter New Password..:"  validation/></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn" type="submit"  id="sub" name="submit" value="SAVE" /></td>
                                        </tr>
                                        </table>
                                </form>
                                </div>
                        </div>
                </div>
        </div> <!-- /container -->

</body>
</html>
