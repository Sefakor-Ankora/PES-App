<?php
    require_once'includes/initialize.php';    
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Subcsriptions</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="subscriptions-add.php"><h4>Add New Subscription</h4></a></li>
        <li><a href="subscriptions-search.php"><h4>Search Subscriptions</h4></a></li>
        <li><a href="subscriptions.php"><h4>Subscription Report</h4></a></li>      
    </ul><br />
    <div id="container2">
        <form action="" method="POST" >
           <table class="data_pes" style="width: 40%">
            <thead style="position: relative;">
                <th>SEARCH BY</th>
                <th></th>                
                <th></th>
            </thead>
    		<tbody>              
                <tr>
                    <td> <select  name="crit" class="input-large">                                          
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "name"){ echo "selected"; } ?> value="name">Customer Name</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "phone"){ echo "selected"; } ?> value="phone">Customer Phone</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "email"){ echo "selected"; } ?> value="email">Customer Email</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "location"){ echo "selected"; } ?> value="location">Customer Location</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "smartcard"){ echo "selected"; } ?> value="smartcard">Smartcard</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "decoder"){ echo "selected"; } ?> value="decoder">Decoder</option>
                    </select></td>
                    <td> 
                        <input class="input-xlarge" type="text" oninput="" name="value" value="<?php if (isset($_POST['value'])){ echo $_POST['value'];}  ?>" 
                               placeholder="Enter Search Value..:" required validation/>
                    </td>
                    <td>
                        <input type="submit" name="search" value="SEARCH" class="btn btn-info" />
                    </td>
                </tr>
    		</tbody>
    	</table>
        </form>
             <hr />        
            <?php 
                if(filter_input(INPUT_POST, "search")){
                    extract(filter_input_array(INPUT_POST)); 
                    $options = array('name','phone','email','location');
                    $options2 = array('smartcard','decoder');
                    $subs=null;
                    
                    if(in_array($crit, $options)){
                        $sql = "SELECT * FROM tbl_customer WHERE deleted = 0 AND {$crit} LIKE '%{$value}%' ";                        
                        $custs = Customer::find_by_sql($sql);
                        $ids;
                        if($custs){ foreach($custs as $cust):
                            $ids[] = $cust->id;
                        endforeach;
                        $idds = implode(",", $ids);
                        $sql = "SELECT * FROM tbl_subscription WHERE deleted = 0 AND customer IN ({$idds})  ";
                        $subs = Subscription::find_by_sql($sql);
                        }                        
                    } else {
                        $sql = "SELECT * FROM tbl_subscription WHERE deleted = 0 AND {$crit} LIKE '%{$value}%' ";
                        $subs = Subscription::find_by_sql($sql);
                    } ?>
                     <h4 style="text-align: center; ">Results</h4> 
        <table class="data_pes">
    		<thead>
    			<th>DATE</th>
    			<th>CUSTOMER</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>LOCATION</th>
                        <th>PACKAGE</th>
    			<th>DECODER</th>
    			<th>SMARTCARD</th>
                        <th>USER</th>
    			<th></th>
    		</thead>
        <?php if($subs != NULL){foreach ($subs as $sub){?>
    		<tbody>
    			<tr>
                            <td><?php echo datetime_to_text2($sub->created); ?></td>
                    <td><?php $customer = Customer::find_by_id($sub->customer); echo ucwords($customer->name) ?></td>
                    <td><?php echo $customer->phone ?></td>
                    <td><?php echo $customer->email ?></td>
                    <td><?php echo ucwords($customer->location) ?></td>
                    <td><?php $pa = Package::find_by_id($sub->package); echo $pa->name; ?></td>
                    <td><?php echo $sub->decoder ?></td>
                    <td><?php echo $sub->smartcard ?></td>
                    <td><?php $u = User::find_by_id($sub->createdby); echo ucwords($u->name) ?></td>
                    <td>
                        <a class="alert-success" href="subscriptions-details.php?&id=<?php echo base64_encode($sub->id) ?>">DETAILS</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                        
                        <a href="subscriptions-edit.php?&id=<?php echo base64_encode($sub->id) ?>">EDIT</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class='alert-danger' onclick="return confirm('Are you sure?')" href="delete.php?type=subscription&id=<?php echo base64_encode($sub->id) ?>">DELETE</a>
                    </td>
    			</tr>
            <?php }} else { ?>
                <tr>
                    <td colspan="9" style="text-align: center;"><div class='alert-info'>No Customers to Display
                        <a href="customers-add.php">Add Customer</a></div></td>
                </tr>
            <?php } ?>
    		</tbody>
    	</table>
                <?php }
            ?>
	</div>
</div>
<?php require_once 'layouts/footer.php';