<?php
	require_once'includes/initialize.php'; 
	require_once 'layouts/header.php';
	require_once 'layouts/sidepane.php';


	$sub = Subscription::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));
	$package = Package::find_by_id($sub->package);
 	$setting = $database->query("SELECT * FROM tbl_app LIMIT 1");
	$c = Customer::find_by_id($sub->customer);
	$row = mysqli_fetch_array($setting);


	$msg = $row['remindersms']. "\n";            
	$msg .= "Amount : ".  $package->amount."\n"   
	. "Due Date: ". datetime_to_text($sub->nextdate);

	// Authorisation details.                
	$username = $row['smsno'];
	$hash = $row['gateway'];

	// Config variables. Consult http://api.txtlocal.com/docs for more info.
	$test = "0";

	// Data for text message. This is the text message data.
	$sender = $row['name']; // This is who the message appears to be from.
	$numbers = $c->phone ; // A single number or a comma-seperated list of numbers                 

	// 612 chars or less
	// A single number or a comma-seperated list of numbers
	$msg = urlencode($msg);
	$data = "username=".$username."&hash=".$hash."&message=".$msg."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	$ch = curl_init('http://api.txtlocal.com/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);
	$results = json_decode($result, true);
	if($results["status"] == "success"){
		$sub->msgs += 1;
		$sub->save();
		$session->message("SMS reminder sent to ".$c->name. " at " . $c->phone);
		redirect_to("dashboard.php");
	} else {
		$session->message("SMS reminder not sent!<br/>SMS status: ".$results["status"]."<br/>SMS balance remaining: ".$results["balance"]);
		redirect_to("dashboard.php");
	}



 ?>