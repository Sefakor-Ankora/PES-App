<?php 
require_once 'includes/initialize.php';     
    if($session->is_logged_in()){
        redirect_to("dashboard.php");         
   }

    if (isset($_POST['login'])){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $found_user = User::authenticate($username, $password);


       if ($found_user  && ($found_user->deleted != 1)){
                 global $database;
                  $session->login($found_user);            
                  if ( (int)$found_user->login == 0){
                      // first time logging on  
                      $session->message("<div class='alert alert-info'>CHANGE YOUR PASSWORD BELOW</div>");               
                      redirect_to("mypass.php");                            
                  } else {
                      $found_user->login = strftime("%Y-%m-%d %H:%M:%S", time());
                      $found_user->ip = $_SERVER['REMOTE_ADDR'];
                          $found_user->update();  
                      redirect_to("dashboard.php");
                  }
        } else if($found_user && ($found_user->deleted == 1)){
            $message = "<div class=\"alert alert-error\">Login Failed. Check the following<br>Account is not removed</div>";
             $username = trim($_POST['username']);
             $password = "";            
        } else {
            // if username / password  combo was not found in the database
            $message = "<div class=\"alert alert-error\">Username /password combination incorrect</div>";
             $username = trim($_POST['username']);
             $password = "";
        }



    } 
        if (isset($_GET['message'])){
             $message = "<div class='alert alert-info'>You have been logged out</div>";
        }

?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>PES</title>
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
                <div class="content" style="margin-top:100px">                    
                        <div class="row">
<div class="login-form" style="margin-top:40px">
    <?php  if (isset($message)){ echo $message; } ?>
	     <div style="width: 100%;height: 120px;margin: 30px auto">
	              <img src="images/pes.jpg" style="height: 100%;width:100%" alt="Premium electronics logo" />
	    </div>      
        <form action="index.php" method="Post" >
                <fieldset>
                        <div class="clearfix">
                                <div class="input-prepend">
                                        <span class="add-on"><i class="icon-user"></i></span>
                                        <input class="span2" id="prependedInput" type="text" name="username" placeholder="Username" autofocus required>
                                </div>
                        </div>
                        <div class="clearfix">
                                <div class="input-prepend">
                                        <span class="add-on"><i class="icon-lock"></i></span>
                                        <input class="span2" id="prependedInput" type="password" name="password" placeholder="Password" required>
                                </div>
                        </div>
                    <button class="btn" style="margin-top: 5px" type="submit" name="login">Sign in&nbsp;&nbsp;<span class="icon-arrow-right"></span></button>
                </fieldset> 
        </form>
</div>
                        </div>
                </div>
        </div> <!-- /container -->

            
            <div  style="width: 60%;height: 200px;margin: 30px auto">
                <img src="images/Multichoice 1.png" style="width: 20%; height: 30%;float: left;margin-right: 50px" />
                <img src="images/dstv-logo.png" style="width: 20%; height: 30%;float: left;margin-right: 50px" />
                <img src="images/1507011426GoTV_ForNewsItem.png" style="width: 20%; height: 30%;float: left;margin-right: 50px" />
                <img src="images/2000px-Canal+.svg.png" style="width: 20%; height: 30%;float: right" />
            </div>
</body>
</html>
