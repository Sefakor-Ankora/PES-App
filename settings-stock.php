<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Stock Items</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-stock-add.php"><h4>Add Stock Item</h4></a></li>
        <li><a href="settings-stock.php"><h4>All Stock Items</h4></a></li>        
    </ul><br />
    <div id="container2">
    	<table class="data_pes">
    		<thead>
    			<th>STOCK ITEM</th>
                <th>UNIT PRICE</th>
    			<th>DESCRIPTION</th>
                <th>AVAILABLE QUANTITY</th>
                <th>LOW WARNING THRESHOLD</th>
    			<th>ACTIONS</th>
    		</thead>
    		<tbody>
            <?php $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock): ?>
    			<tr>
                    <td><?php echo $stock->name ?></td>
                    <td class="money"><?php echo $stock->price ?></td>
                    <td><?php echo $stock->about ?></td>
                    <td class="money"><b><?php echo $stock->qty ?></b></td>
                    <td class="<?php  
                if ($stock->th >= $stock->qty){ echo "alert-danger";}
                ?> money"><b><?php echo $stock->th ?></b></td>               
                    <td>
                        <a class="alert-success" href="settings-stock-view.php?&id=<?php echo base64_encode($stock->id) ?>">VIEW DETAILS</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="alert-info" href="settings-stock-modify.php?&id=<?php echo base64_encode($stock->id) ?>">MODIFY STOCK</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="settings-stock-edit.php?&id=<?php echo base64_encode($stock->id) ?>">EDIT</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class='alert-danger' onclick="return confirm('Are you sure?')" href="delete.php?type=stock&id=<?php echo base64_encode($stock->id) ?>">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; } else {  ?>
                <tr>
                    <td colspan="5" style="text-align: center;"><div class='alert-info'>No stock items to Display
                        <a href="settings-stock-add.php">Add Stock Item</a></div></td>
                </tr>
                <?php } ?>
    		</tbody>
    	</table>
	</div>
</div>
<?php require_once 'layouts/footer.php';