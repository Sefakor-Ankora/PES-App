<?php
    require_once'includes/initialize.php'; 
    global $database;
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 
        $m = "";
        //perform stock check here
        $pp = Package::find_by_id($package);
        if($pp->items != "" && $pp->qty != ""){
            $ids = explode(",", $pp->items);
            $qtys = explode(",", $pp->qty);        
            foreach ($ids as $id){
                $item = Stock::find_by_id($id);
                if ($item->qty < $qtys[array_search($id, $ids)]){
                    $session->message("Inssufficient quantity to create Subscription<br />Item: {$item->name}<br />Available Quantity: {$item->qty}<br />Required: {$qty[array_search($id, $ids)]}");
                    redirect_to("subscriptions-add.php");
                }
            }
        }
        // sufficient qty if execution gets to this line
        // First , get customer info into table
        $customer = new Customer();
        $customer->name = $name;
        $customer->email = $email;
        $customer->phone = $phone;
        $customer->location = $location;
        $customer->created = strftime("%Y-%m-%d %H:%M:%S", time());
        $customer->createdby = $session->id;
        $customer->save();        
        
        //now save subscription info
        $sub = new Subscription();
        $sub->customer = $customer->id;
        $sub->decoder = $decoder;
        $sub->smartcard = $smartcard;
        $sub->package  = $package;
        $sub->idate = $idate;
        $sub->iamount = $iamount;    
        $sub->overhead = $overhead;
        $sub->amt = $amt;
        $sub->hardware = $hardware;
        $sub->amountdue = $sub->overhead + $sub->iamount + $sub->amt + $sub->hardware;

        $sub->paydate = $pdate;    
        
         $date = new DateTime($pdate);
        $interval = new DateInterval('P1M');        
        $date->add($interval);
        $sub->nextdate = $date->format('Y-m-d');
        
        
        $sub->technician = $technician;
        $sub->tphone = $iphone;
        $sub->created = strftime("%Y-%m-%d %H:%M:%S", time());
        $sub->createdby = $session->id;
        $sub->save();
        // create stock records for items attached to this package
        // 
        if($pp->items != "" && $pp->qty != ""){
            foreach ($ids as $id):
                $stock = Stock::find_by_id($id);
                $stockh = new Stockhistory();
                $stockh->sub = $sub->id;
                $stockh->stock = $item->id;            
                $stockh->qty = $qtys[array_search($id, $ids)];
                $stockh->price = $stock->price;
                $stockh->remarks = "N/A";
                $stockh->createdby = $session->id;
                $stockh->prev = $stock->qty;
                $stockh->new = $stock->qty - $stockh->qty;        
                $stockh->type = "subscription";//same as subtract                            
                $stockh->created = strftime("%Y-%m-%d %H:%M:%S", time());
                $stockh->save();
                //update stock info
                $stock->qty = $stockh->new;
                $stock->save();        
            endforeach;
        }
        //finally, create record for the subscription payment in payment table
       
        $payment = new Payment();
        $payment->amount = $payment->amountdue = $sub->amountdue;
        $payment->sub = $sub->id;
        $payment->paid = strftime("%Y-%m-%d", time());
        $payment->package = $sub->package;        
        $payment->poption = $poption;        
        $payment->remarks = "New Subscription payment";
        $payment->createdby = $session->id;
        $payment->created = strftime("%Y-%m-%d %H:%M:%S", time());
        $payment->save();


        $setting = $database->query("SELECT * FROM tbl_app LIMIT 1");
        $row = mysqli_fetch_array($setting);
        //send email and sms
        if ($sendemail == 1){
            $msg = $row['welcomesms']. "<br />";
            $msg .= "<p>Details are as follows:</p>"
        . "<p><b>Subscription Date: </b>". datetime_to_text($payment->paid) ."</p>"
        . "<p><b>Subscription Package:</b>". $pp->name ."</p>"
        . "<p><b>SmartCard:</b>". $sub->smartcard."</p>"
        . "<p><b>Decoder:</b>". $sub->decoder."</p>"
        . "<p><b>Amount Paid GH &cent;:</b>". $payment->amount."</p>"             
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
                $mail->addAddress($customer->email, $customer->name);
                $mail->Subject = "Welcome to Premium Electronics Services";
                $mail->Body = $msg;
                if($mail->send()){
                    $m .="Email sent to " . $customer->email;
                } else {
                    $m .= $mail->ErrorInfo;
                }
                   //   password for sms place account email is same as above password is : PES123!@#e
        }
        if ($sendsms == 1){
            // sms code will live here
            $msg = $row['welcomesms']. "\n";            
            $msg .= "Date: ". datetime_to_text($payment->paid) ."\n"
            . "SmartCard: ". $sub->smartcard."\n"   
            . "Amount: ". $payment->amount."\n\n"   
            . "Thank you for your business.";

             // Authorisation details.                
                $username = $row['smsno'];
                $hash = $row['gateway'];

                // Config variables. Consult http://api.txtlocal.com/docs for more info.
                $test = "0";

                // Data for text message. This is the text message data.
                $sender = $row['name']; // This is who the message appears to be from.
                $numbers = $customer->phone ; // A single number or a comma-seperated list of numbers
                
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
					$m .= "<br />SMS sent to ".$customer->name. " at " . $customer->phone;
				} else {
					$m .= "<br />SMS  not sent!<br/>SMS status: ".$results["status"]."<br/>SMS balance remaining: ".$results["balance"];
				}
        }
        
        
        if($sub){
            $m .= "<br />Subscription saved successfully";
            $session->message($m);
            redirect_to("subscriptions-details.php?id=".base64_encode($sub->id));
        }else {
            $message = "An error occured";
        }
    }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">SUBSCRIPTION APPLICATION FORM</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="subscriptions-add.php"><h4>Add New Subscription</h4></a></li>
        <li><a href="subscriptions-search.php"><h4>Search Subscriptions</h4></a></li>
        <li><a href="subscriptions.php"><h4>Subcsription Report</h4></a></li>       
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
            <table class="form_table">
                <tr><td colspan="2"><h3>Customer Information</h3></td></tr>
                <tr>
                 <tr>                                
            <td><b>Use System Date</b>
                  <input class="input-large" type="radio" checked  onclick="document.getElementById('customtime').disabled=true;document.getElementById('customtime2').disabled=true"  name="date" value="1" required/>
          </td>
    <td>
        <b>Enter Date</b>
        <input class="input-large" type="radio" onclick="document.getElementById('customtime').disabled=false;document.getElementById('customtime2').disabled=false"  name="date" value="0" required />

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="date"  id="customtime" name="saledate" disabled />
        <select class="input-medium" id="customtime2"  name="saledate2" required  disabled>
            <option value="Midnight">Midnight</option>
         <?php 
         for ($i=12; $i<13; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 am\">{$i}:00 am</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} am\">{$i}:{$j} am</option>";
             }
             }
         } 
            for ($i=1; $i<12; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 am\">{$i}:00 am</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} am\">{$i}:{$j} am</option>";
             }
             }
         } 
         ?>

         <option value="Noon" selected="selected">Noon</option>
         <?php  
         for ($i=12; $i<13; $i ++){
             for ($j =0; $j <=60; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 pm\">{$i}:00 pm</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} pm\">{$i}:{$j} pm</option>";
             }
             }
         } 
            for ($i=1; $i<12; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 pm\">{$i}:00 pm</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} pm\">{$i}:{$j} pm</option>";
             }
             }
         } 
         ?>
         </select></td>
         <td rowspan="5"></td>
                </tr>  
                 <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Name:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="name" name="name" value="" placeholder="Enter Name..:" required /></td>                
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Phone Number:<br />(eg, +233201111111)</label></span></td>
                <td><input class="input-xxlarge" type="text" id="phone" oninput="" name="phone" value="" placeholder="Enter Phone Number..:" required /></td>
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Email:</label></span></td>
                <td><input class="input-xxlarge" type="email" id="email" oninput="" name="email" value="" placeholder="Enter Email..:" required /></td>
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Location:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="location" oninput="" name="location" value="" placeholder="Enter Location..:" required /></td>
            </tr>             
                <tr><td colspan="3"><hr /><h3>Subscription Information</h3></td></tr>
                   <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Category:</label></span></td>
                    <td>
                        <select  name="category" class="input-xxlarge" required onchange="load_packages(this.value)">
                         <option value="">Please Select</option>
                  <?php $cats = Category::find_all(); if ($cats){ foreach($cats as $cat): ?>
                        <option value="<?php echo $cat->id ?>"><?php echo $cat->name ?></option>
                    <?php endforeach; } ?>
                    </select>
                    </td>                    
                </tr>
                <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Package:</label></span></td>
                    <td><div id="packages">
                    <select  name="package" class="input-xxlarge" required>
                         <option value="">Please Select</option>
                    </select>
                </div>
                <div id="details"></div>
                    </td>
                </tr>   
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Decoder:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="decoder" oninput="" name="decoder" value="" placeholder="Enter Decoder Info..:" required validation/></td>
            </tr>    
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Smartcard:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="smartcard" oninput="" name="smartcard" value="" placeholder="Enter Smartcard..:" required validation/></td>
            </tr>         
            <tr><td colspan="3"><hr /><h3>Installation Information</h3></td></tr>
                   <tr>               
                <td><span class="help-inline"><label class="control-label" for="search">Installer Name:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="technician" oninput="" name="technician" value="" placeholder="Enter Technician..:" required validation/></td>
            </tr>   
                           <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Installer Phone:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="location" oninput="" name="iphone" value="" placeholder="Enter Installer Phone number..:" required validation/></td>
            </tr>   
             <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Installation Date:</label></span></td>
                <td><input class="input-xxlarge" type="date" id="idate" oninput="" name="idate" value="" placeholder="Enter Installation Date..:" required validation/></td>
            </tr>   
             <tr><td colspan="3"><hr /><h3>Payment Information</h3></td></tr>            
             <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Installer Amount GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Install Amount..." type="number" id="iamount" oninput="" name="iamount" value="0"  required /></td>                   
                </tr>
             <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Administrative Overhead GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Administrative Overhead..." type="number" id="overhead" oninput="" name="overhead" value="0"   required /></td>
                   
                </tr>
        <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Package Amount GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Subscription Package Amount..." type="number" id="amt" oninput="" name="amt" value="0"  required /></td>
                    <td rowspan="5"></td>
                </tr>      
                              <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Hardware Required GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Hardware Package Amount..." type="number" id="hardware" oninput="" name="hardware" value="0"  required /></td>
                    <td rowspan="5"></td>
                </tr> 
                   <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Payment Method:</label></span></td>
                    <td>
                        <select id="poption"  name="poption" class="input-xxlarge" required="">
                         <option value="">Please Select</option>
                         <option value="cash">Cash</option>
                         <option value="mobile money">Mobile Money</option>
                         <option value="cheque">Cheque</option>
                         <option value="cheque">Credit</option>
                    </select>
                    </td>
                </tr>
     <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Payment Date:</label></span></td>
                <td><input class="input-xxlarge" required="" type="date" id="pdate" oninput="" name="pdate" value="" placeholder="Enter Payment Date..:" /></td>
            </tr>
     <tr>
         <td colspan="2"><p>&nbsp;</p></td>
            </tr>            
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">&nbsp;</label></span></td>
                <td>
                    Send Email To Customer &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox"  name="sendemail"value="1" />
                    <br /><br />
                    Send SMS To Customer &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="sendsms"value="1" />
                </td>
            </tr>
            <tr>
                <td colspan="2"><p>&nbsp;</p><p>&nbsp;</p></td>                
            </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input class="btn btn-success" onclick="return confirm('Confirm Subscription Details')" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="reset" name="" value="CLEAR" class="btn btn-danger" /></td>
            </tr>
            </table>
        </form>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>   
    </div>
</div>
<script type="text/javascript">
     function load_packages(cat){
        document.getElementById('packages').innerHTML = "Please Wait, Processing...";
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('packages').innerHTML = mlhttp.responseText;
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
            mlhttp.open("GET","as/search.php?type=loadpackages&value="+ cat,true);
            mlhttp.send();  

    }

  function get_stock(cat){
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
            document.getElementById('hardware').value = parseFloat(document.getElementById('hamt').value);
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
            mlhttp.open("GET","as/search.php?type=getstock&value="+ cat,true);
            mlhttp.send();  

    }
    
</script>                        
<?php require_once './layouts/footer.php'; 