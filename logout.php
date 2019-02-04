<?php require_once("includes/initialize.php"); 
if ( $session->logout()){
    redirect_to("index.php?message=<div class=\"alert alert-info\">You have been logged out.</div>");
}
