<?php
    require_once'includes/initialize.php'; 
    $sub = Subscription::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));
    $customer = Customer::find_by_id($sub->customer);
    $payment = Payment::find_by_sub($sub->id);
    
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 
 
        // sufficient qty if execution gets to this line
        // First , get customer info into table
        $customer->name = $name;
        $customer->email = $email;
        $customer->phone = $phone;
        $customer->location = $location;
        $customer->save();
        
        //now save subscription info
        $sub->decoder = $decoder;
        $sub->smartcard = $smartcard;
        $sub->idate = $idate;
        $sub->technician = $technician;
        $sub->tphone = $iphone;
        $sub->iamount = $iamount;    
        $sub->overhead = $overhead;
        $sub->amt = $amt;
        $sub->hardware = $hardware;
        $sub->amountdue = $sub->overhead + $sub->iamount + $sub->amt + $sub->hardware;
        $sub->save();
        // create stock records for items attached to this package
        //         
        //finally, create record for the subscription payment in payment table     
        $payment->poption = $poption;    
        //$payment->receipt = $receipt;
        $payment->amount = $payment->amountdue = $sub->amountdue;  
        $payment->save();
            $session->message("Subscription saved successfully");
            redirect_to("subscriptions-details.php?id=".base64_encode($sub->id));
      
    }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center;">Edit Subscription Record</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="subscriptions-add.php"><h4>Add New Subcription</h4></a></li>
        <li><a href="subscriptions-search.php"><h4>Search Subcriptions</h4></a></li>
        <li><a href="subscriptions.php"><h4>Subscription  Report</h4></a></li>        
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
            <table class="form_table">
                <tr><td colspan="2"><h3>Customer Information</h3></td></tr>   
                 <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Name:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="name" name="name" value="<?php echo $customer->name ?>" placeholder="Enter Name..:" required /></td>                
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Phone Number:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="phone" oninput="" name="phone" value="<?php echo $customer->phone ?>" placeholder="Enter Phone Number..:" required /></td>
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Email:</label></span></td>
                <td><input class="input-xxlarge" type="email" id="email" oninput="" name="email" value="<?php echo $customer->email ?>" placeholder="Enter Email..:" required /></td>
            </tr>
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Location:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="location" oninput="" name="location" value="<?php echo $customer->location ?>" placeholder="Enter Location..:" required /></td>
            </tr>             
                <tr><td colspan="2"><hr /><h3>Subscription Information</h3></td></tr>
                   <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Category:</label></span></td>
                 <td> 
                     <?php $package = Package::find_by_id($sub->package); $cat = Category::find_by_id($package->category);  ?>
                     <input class="input-xxlarge" type="text" value="<?php echo $cat->name ?>" readonly="" />
                    </td>
                </tr>
                <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Package:</label></span></td>
                    <td>
                        <input class="input-xxlarge" type="text" value="<?php echo $package->name ?>" readonly="" />
                    </td>
                </tr>   
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Decoder:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="decoder" oninput="" name="decoder" value="<?php echo $sub->decoder ?>" placeholder="Enter Decoder Info..:" required validation/></td>
            </tr>    
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Smartcard:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="smartcard" oninput="" name="smartcard" value="<?php echo $sub->smartcard ?>" placeholder="Enter Smartcard..:" required validation/></td>
            </tr>         
            <tr><td colspan="3"><hr /><h3>Installation Information</h3></td></tr>
                   <tr>               
                <td><span class="help-inline"><label class="control-label" for="search">Installer Name:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="technician" oninput="" name="technician" value="<?php echo $sub->technician ?>" placeholder="Enter Technician..:" required validation/></td>
                <td rowspan="3"></td>
            </tr>   
                           <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Installer Phone:</label></span></td>
                <td><input class="input-xxlarge" type="text" id="location" oninput="" name="iphone" value="<?php echo $sub->tphone ?>" placeholder="Enter Installer Phone number..:" required validation/></td>
            </tr>   
             <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Installation Date:</label></span></td>
                <td><input class="input-xxlarge" type="date" id="idate" oninput="" name="idate" value="<?php echo $sub->idate ?>" placeholder="Enter Installation Date..:" required validation/></td>
            </tr>   
             <tr><td colspan="3"><hr /><h3>Payment Information</h3></td></tr>             
            <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Install Amount GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Install Amount..." type="number" id="iamount" oninput="" name="iamount" value="<?php echo $sub->iamount; ?>"  required /></td>
                    <td rowspan="5"></td>
                </tr>
             <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Administrative Overhead GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Administrative Overhead..." type="number" id="overhead" oninput="" name="overhead" value="<?php echo $sub->overhead ?>"   required /></td>
                    <td rowspan="5"></td>
                </tr>
        <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Subscription Package Amount GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Subscription Package Amount..." type="number" id="amt" oninput="" name="amt" value="<?php echo $sub->amt ?>"  required /></td>
                    <td rowspan="5"></td>
                </tr>  
               <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Hardware Required GHC &cent;:</label></span></td>
                 <td><input class="input-xxlarge" placeholder="Enter Hardware Package Amount..." type="number" id="hardware" oninput="" name="hardware" value="<?php echo $sub->hardware ?>"  required /></td>
                    <td rowspan="5"></td>
                </tr> 
                   <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Payment Method:</label></span></td>
                    <td>
                        <select id="poption"  name="poption" class="input-xxlarge" >
                         <option value="">Please Select</option>
                         <option value="cash" <?php if($payment->poption == "cash"){ echo "selected"; } ?>>Cash</option>
                         <option value="mobile money" <?php if($payment->poption == "mobile money"){ echo "selected"; } ?>>Mobile Money</option>
                         <option value="cheque" <?php if($payment->poption == "cheque"){ echo "selected"; } ?>>Cheque</option>
                    </select>
                    </td>
                </tr>
               <tr>
        <td><span class="help-inline"><label class="control-label" for="search">Payment Date:</label></span></td>
                <td><input class="input-xxlarge" required="" type="date" id="pdate" oninput="" name="pdate" value="<?php echo $payment->paid ?>" placeholder="Enter Payment Date..:" /></td>
            </tr>  
              <tr>
                <td>&nbsp;</td>
                <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp; 
                    <button class="btn btn-danger" onclick="history.go(-1)">CANCEL EDIT</button></td>
            </tr>
            </table>
        </form>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>   
    </div>
</div>
<?php require_once './layouts/footer.php'; 