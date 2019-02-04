<?php
    require_once'includes/initialize.php'; 
    $sp =1;
    global $database;
$user = User::find_by_id($session->id);
    if (filter_input(INPUT_POST, "submit")){
        $name = trim(filter_input(INPUT_POST, "name"));
        $gateway = trim(filter_input(INPUT_POST, "gateway"));
        $smsno = trim(filter_input(INPUT_POST, "smsno"));
         $graph = trim(filter_input(INPUT_POST, "graph"));
        $welcomesms = trim(filter_input(INPUT_POST, "welcomesms"));
        $remindersms = trim(filter_input(INPUT_POST, "remindersms"));
        $paymentsms = trim(filter_input(INPUT_POST, "paymentsms"));        
        $sql = "UPDATE tbl_app SET name = '{$name}' , gateway = '{$gateway}' , smsno = '{$smsno}', welcomesms = '{$welcomesms}' , remindersms = '{$remindersms}', paymentsms = '{$paymentsms}'";
        if($graph != $user->graph){
            $user->graph = $graph;
            $user->save();
        }
        if ($database->query($sql)){
            $message = "SETTINGS UPDATED";
        } else {
            $message = "AN ERROR OCCURED";
        }
    }
        $setting = $database->query("SELECT * FROM tbl_app LIMIT 1");
$username;
$hash;

    require_once 'layouts/header.php';
 require_once './layouts/sidepane.php'; ?>
      <div class="container1">
    <h3 style="text-align: center; ">Settings</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <div id="container2">
        <?php while ($row = mysqli_fetch_array($setting)) { 

			$username = $row['smsno'];
			$hash = $row['gateway'];
			
			// You shouldn't need to change anything here.	
			$data = "username=".$username."&hash=".$hash;
			$ch = curl_init('http://api.txtlocal.com/balance/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$credits = curl_exec($ch);
			$credit = json_decode($credits, true);
			// This is the number of credits you have left	
			curl_close($ch);
         ?>
        <form method='post' action="">  
                                        <table class="form_table">
                                        	<tr>
                                        		<td><h4>Remaining SMS Credit:</h4></td>
                                        		<td> <?php echo $credit['balance']['sms'] ?></td>
                                        		<td colspan="2">&nbsp;</td>
                                        	</tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Organaization Name:</label></span></td>
                                            <td><input  class="input-xlarge" type="text" id="name" oninput="" name="name" value="<?php echo $row['name'] ?>" placeholder="Enter Organaization Name..:" validation/></td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current Organaization Name:</label></span></td>
                                            <td><?php echo strtoupper($row['name']) ?></td>
                                        </tr>
                                         <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">SMS Gateway Hash Value:</label></span></td>
                                            <td><input  class="input-xlarge" type="text" id="gateway" name="gateway" value="<?php echo $row['gateway'] ?>" placeholder="Enter SMS Gateway Link..:"  validation/></td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current SMS Gateway Hash Value:</label></span></td>
                                            <td><?php echo $row['gateway'] ?></td>
                                        </tr>          
                                         <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">SMS Gateway username:</label></span></td>
                                            <td><input class="input-xlarge" type="text" id="gateway" name="smsno" value="<?php echo $row['smsno'] ?>" placeholder="Enter SMS Number..:"  validation/></td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current SMS Gateway username:</label></span></td>
                                            <td><?php echo $row['smsno'] ?></td>
                                        </tr>      
                                        <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">New Subscription message:</label></span></td>
                                            <td>
                                        <textarea class="input-xlarge" id="sms" name="welcomesms"  placeholder="Enter New Subscription SMS Message..:" required><?php echo $row['welcomesms'] ?></textarea>
                                            </td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current New Subscription Message:</label></span></td>
                                            <td><?php echo $row['welcomesms'] ?></td>
                                        </tr>                                        
                                        <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Payment reminder message:</label></span></td>
                                            <td>
                                        <textarea class="input-xlarge" id="sms" name="remindersms"  placeholder="Enter Payment reminder Message..:" required><?php echo $row['remindersms'] ?></textarea>
                                            </td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current Payment Reminder Message:</label></span></td>
                                            <td><?php echo $row['remindersms'] ?></td>
                                        </tr>
                                        <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Processed Payment message:</label></span></td>
                                            <td>
                                        <textarea class="input-xlarge" id="sms" name="paymentsms"  placeholder="Enter Processed Payment  Message..:" required><?php echo $row['paymentsms'] ?></textarea>
                                            </td>
                                             <td><span class="help-inline"><label class="control-label" for="search">Current Processed Payment Message:</label></span></td>
                                            <td><?php echo $row['paymentsms'] ?></td>
                                        </tr>   
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Display Graph figures for the past</label></span></td>
                                            <td><select name="graph" class="input-xlarge">
                                                <?php for ($i =2; $i <= 12; $i ++){ ?>
                                                <option <?php if ($i == $user->graph){ echo "selected"; } ?>><?php  echo $i . " Months" ?></option>
                                            <?php  } ?>
                                            </select></td>
                                            <td colspan="2"></td>
                                        </tr>                                                                             
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn btn-success" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="reset" name="" value="RESET FORM" class="btn btn-danger" /></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </table>
                                </form>
                            <?php } ?>
    </div>   
</div>
<?php require_once './layouts/footer.php'; 
