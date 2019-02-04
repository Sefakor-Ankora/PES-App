<?php
require_once'includes/initialize.php'; 
    $stock = Stock::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Stock Item View- <?php echo $stock->name ?></h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-stock-add.php"><h4>Add Stock Item</h4></a></li>
        <li><a href="settings-stock.php"><h4>All Stock Items</h4></a></li>        
        <li><a href="settings-stock-modify.php?&id=<?php echo base64_encode($stock->id) ?>"><h4>Modify Stock</h4></a></li>      
    </ul><br />
    <div id="container2">
        <table class="form_table" style="width: 40%">
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Stock Item:</label></span></td>
                <td><?php  echo $stock->name ?></td>
            </tr>
            <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Stock Item:</label></span></td>
                <td><?php echo $stock->about ?></td>              
            </tr>
            <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Unit Price:</label></span></td>
                <td><?php echo $stock->price ?></td>              
            </tr>
              <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Current Stock Item Quantity:</label></span></td>
                <td class="
                <?php  
                if ($stock->th >= $stock->qty){ echo "alert-danger";} else { echo "alert-info"; }
                ?>
                "><?php echo $stock->qty ?></td>              
            </tr>
              <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Low Warning Threshold Quantity:</label></span></td>
                <td><?php echo $stock->th ?></td>              
            </tr>            
                        <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Created By:</label></span></td>
                <td><?php $user = User::find_by_id($stock->createdby); if ($user){ echo "<b>".$user->name ."</b>" . " on ". datetime_to_text1($stock->created); }  ?></td>
            </tr>
                     <tr>
                 <td></td>
                <td>  <a href="settings-stock-edit.php?&id=<?php echo base64_encode($stock->id) ?>">EDIT INFO</a></td>
            </tr>
            </table>
        <hr />
        <h4 style="text-align: center;">Stock Update History</h4>
    	<table class="data_pes">
            <thead style="position: relative;">
                <th>DATE</th>
                <th>DESCRIPTION</th>
                <th>PREVIOUS QTY</th>                
                <th>+qty</th>
                <th>-qty</th>
                <th>NEW QTY</th>
                <th>UNIT PRICE</th>
                <th>USER</th>
                <th>REMARKS</th>
            </thead>
    		<tbody><!-- type -->
            <?php $stockhs = Stockhistory::find_all_for_stock($stock->id); if ($stockhs){ foreach($stockhs as $stockh): ?>
    			<tr>
                    <td><?php echo datetime_to_text($stockh->created) ?></td>
                    <td class="money">
                        <?php 
                            switch ($stockh->type) {
                                case 'add':
                                    echo $stockh->remarks;
                                    break;
                                case 'sale':
                                    echo "<a target=\"_new\" href='sales-details.php?id=". base64_encode($stockh->id) ."'>Sale Record</a>";
                                    break;
                                case 'subscription':
                                    echo "<a target=\"_new\" href='subscriptions-details?id=". base64_encode($stockh->sub) ."'>New Subscription</a>";
                                    break;
                                case 'subtract':
                                    echo $stockh->remarks;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            ?>

                    </td>
                    <td class="money"><?php echo $stockh->prev ?></td>
                    
                <?php if ($stockh->type == "add"){ ?>
                    <td class="money"><b><?php echo $stockh->qty ?></b></td>
                    <td></td>
                    <td class="money"><?php echo ($stockh->prev + $stockh->qty)  ?></td>           
                    
                <?php } else if ($stockh->type == "subtract" || $stockh->type == "sale" || $stockh->type == "subscription"){ ?>
                    <td></td>
                    <td class="money"><b><?php echo $stockh->qty ?></b></td> 
                    <td class="money"><?php echo ($stockh->prev - $stockh->qty)  ?></td>                
                <?php } ?>
                    <td><?php echo $stockh->price ?></td>
                    <td><?php $user = User::find_by_id($stock->createdby); if ($user){ echo $user->name; } ?></td>               
                    <td class="money"><?php echo $stockh->remarks  ?></td>
                </tr>
                <?php endforeach; } ?>
    		</tbody>
    	</table>
	</div>
</div>
<?php require_once 'layouts/footer.php';