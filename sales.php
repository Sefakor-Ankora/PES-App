<?php
    require_once'includes/initialize.php'; 
    if(filter_input(INPUT_POST, "submit")){
        $customer = new Customer();
        $customer->name =  str_replace("'","",trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT)));
        $customer->tel = trim(filter_input(INPUT_POST, "tel", FILTER_DEFAULT));
        $customer->email = trim(filter_input(INPUT_POST, "email", FILTER_DEFAULT));
        $customer->location = trim(filter_input(INPUT_POST, "location", FILTER_DEFAULT));
        $customer->branch = trim(filter_input(INPUT_POST, "branch", FILTER_DEFAULT));
        $customer->country = trim(filter_input(INPUT_POST, "country", FILTER_DEFAULT));
        if(filter_input(INPUT_POST, "dob") != ""){
       		$customer->dob = trim(filter_input(INPUT_POST, "dob", FILTER_DEFAULT));
        }
        $customer->notes = trim(filter_input(INPUT_POST, "notes", FILTER_DEFAULT));
        if($customer && $customer->save()){
            $session->message("Customer info saved successfully<br />Add New Transaction for ".$customer->name);
            $action = filter_input(INPUT_POST ,"action");
            if ($action == 1){
            redirect_to("newsinglesale.php?customer=". base64_encode($customer->name) );
            } else if ($action == 2){
                redirect_to("customers1.php");
            } else if ($action == 3){
                redirect_to("customerdetails.php?id=". base64_encode($customer->id) );
            }
        }else {
            $message = "An error occured";
        }
    }
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Sales</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="sales-add.php"><h4>New Sale</h4></a></li>
        <li><a href="sales-today.php"><h4>Today's Sales</h4></a></li>
        <li><a href="sales-my.php"><h4>Search Sales</h4></a></li>
    </ul><br />
    <div id="container2">
    	<table class="data_pes">
    		<thead>
    			<th>NAME</th>
    			<th>PHONE</th>
    			<th>EMAIL</th>
    			<th>LOCATION</th>    		
    			<th>AMOUNT DUE GH &cent;</th>
    			<th>ACTIONS</th>
    		</thead>
        <?php $customers = Customer::find_all(); if($customers){foreach ($customers as $customer){?>
    		<tbody>
    			<tr>
    				<td><?php echo ucwords($customer->name) ?></td>
                    <td><?php echo $customer->phone ?></td>
                    <td><?php echo $customer->email ?></td>
                    <td><?php echo ucwords($customer->location) ?></td>
                    <td class="money"><?php echo $customer->amountdue ?></td>
                    <td>
                        <a class="alert-success" href="settings-stock-view.php?&id=<?php echo base64_encode($stock->id) ?>">SUBSCRIPTIONS</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="alert-info" href="settings-stock-modify.php?&id=<?php echo base64_encode($stock->id) ?>">MODIFY STOCK</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="settings-stock-edit.php?&id=<?php echo base64_encode($stock->id) ?>">EDIT</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class='alert-danger' onclick="return confirm('Are you sure?')" href="delete.php?type=stock&id=<?php echo base64_encode($stock->id) ?>">DELETE</a>
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
	</div>
</div>
<?php require_once 'layouts/footer.php';