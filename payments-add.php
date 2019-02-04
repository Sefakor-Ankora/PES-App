<?php
    require_once'includes/initialize.php'; 
    global $database;          
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 
        $m="";
        $payment = new Payment();
        $payment->sub = $sub;
        $payment->amount = $amtpaid;
        $payment->amountdue = $amtdue;
        $payment->receipt = $receipt;
        $payment->remarks = $remarks;
        $payment->createdby = $session->id;
        $payment->poption = $poption;
        $payment->package = $package;
        $payment->paid = $pdate;
        $payment->created = strftime("%Y-%m-%d %H:%M:%S", time());       
        //updaet next due payment date
        $subb = Subscription::find_by_id($sub);
        $subb->nextdate = $nextdate;
        $subb->msgs = 0;
        $subb->save();
        if($upgrade == 1){
            $subb->package = $newpackage;
        }
        
        $package = Package::find_by_id($subb->package);
        
        if($payment && $payment->save()){
             $setting = $database->query("SELECT * FROM tbl_app LIMIT 1");
             $c = Customer::find_by_id($subb->customer);
            $row = mysqli_fetch_array($setting);
            if($sendemail == 1){
                /// send email
                $msg = $row['paymentsms']. "<br />";
                $msg .= "<p>Details are as follows:</p>"
                        . "<p><b>Payment Date: </b>". datetime_to_text($payment->paid) ."</p>"
                        . "<p><b>Payment Method: </b>". ucwords($payment->poption)."</p>"
                        . "<p><b>Amount GH &cent;:</b>". $payment->amount."</p>"
                        . "<p><b>Subscription Package:</b>". $package->name ."</p><br /><br />"
                        . "Thank you for your business.";
                $mail = new PHPMailer();
                $mail->isHTML(true);
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Username = "pesemail12345@gmail.com";
                $mail->Password = "PES123!@#";
                $mail->Port = 465;
                $mail->SMTPSecure = "ssl";
                $mail->Host = "smtp.gmail.com";
                $mail->FromName = "Premium Electronic Services";
                $mail->From = "info@pes.com";
                $mail->addAddress($c->email,$c->name);
                $mail->Subject = "Payment received";
                $mail->Body = $msg;
                
                if($mail->send()){
                    $m .="Email sent to " . $c->email;
                } else {
                    $m .= $mail->ErrorInfo;
                }
            }
            if ($sendsms == 1){
                //send sms
                //format message 

              $msg = $row['paymentsms']. "\n";            
                $msg .= "Date: ". datetime_to_text($payment->paid) ."\n"
                . "Amount : ".  $payment->amount."\n"   
                . "Next Due: ". datetime_to_text($subb->nextdate) ."\n\n"
                . "Thank you.";

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
                //send sms 
                $results = json_decode($result, true);
                if($results["status"] == "success"){
                    $m .= "<br />SMS sent to ".$c->name. " at " . $c->phone;
                } else {
                    $m .= "<br />SMS  not sent!<br/>SMS status: ".$results["status"]."<br/>SMS balance remaining: ".$results["balance"];
                }
            }

            $m .= "Payment completed successfully";
            $session->message($m);
            redirect_to("payments-details.php?id=".base64_encode($payment->id));
        }else {
            $message = "An error occured";
        }
    }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Subscription Payments</h3>       
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="payments-add.php"><h4>New Payment Record</h4></a></li>        
        <li><a href="payments.php"><h4>All Payments</h4></a></li>
        <li><a href="payments-search.php"><h4>Search Payments</h4></a></li>
    </ul><br /><p>&nbsp;</p>
     <div id="container2">         
            <table class="form_table">                         
                <tr>                 
                    <td>
                        <input autofocus="" onchange="loadform(this.value)" class="input-xlarge" type="text" oninput="" id="value" 
                               placeholder="Enter Smartcard No./ Decoder No..:" required validation/>
                        <button class="btn btn-info" onclick="loadform(document.getElementById('value').value)">SEARCH</button>
                    </td>
                </tr>
                
            </table>            
         <hr />         
            <div id="details"></div>
    </div>
</div>
<script type="text/javascript">
     function loadform(item){
        document.getElementById('details').innerHTML = "Please Wait, Processing...";
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('details').innerHTML = mlhttp.responseText;
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
                    mlhttp.open("GET","as/search.php?type=paymentform&value="+ item,true);
                    mlhttp.send();  

    }

       function loadprice(val){
        document.getElementById('pinfo').innerHTML = "<div class=\"alert alert-info\">Please Wait, Processing...</div>";
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('pinfo').innerHTML = "<div class=\"alert alert-info\">" + mlhttp.responseText + "</div>";
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
                    mlhttp.open("GET","as/search.php?type=pinfo&value="+ val,true);
                    mlhttp.send();  

    }
     function toggleupgrade(val){
            if(parseInt(val) === 1){
                document.getElementById('newpackage').disabled = false;    
                document.getElementById('amtdue').value  = "";
            } else {
                 document.getElementById('newpackage').disabled = true;
                 //reload amount for existing package in amount 
                  document.getElementById('amtdue').value  = document.getElementById('oldamt').value;
                  document.getElementById('pinfo').innerHTML  = "";
            }
        }
</script>                        
<?php require_once './layouts/footer.php'; 