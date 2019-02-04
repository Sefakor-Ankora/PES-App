<?php
    require_once'includes/initialize.php'; 
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
            <thead style="position: relative;">
                <th>DATE</th>
                <th>DESCRIPTION</th>
                <th>PAYMENT OPTION</th>
                <th>PREVIOUS QTY</th>                
                <th>QTY</th>
                <th>NEW QTY</th>
                <th>UNIT PRICE</th>
                <th>AMOUNT GH &cent;</th>
                <th>USER</th>
                <th>REMARKS</th>
            </thead>
            <tbody><!-- type -->
            <?php 
                if ($session->role == 1){//Administrator
                        $stockhs = Stockhistory::find_all_for_todaysales();
                    }else {
                        $stockhs = Stockhistory::find_all_for_today_mesales($session->id);
                    }
                
            if ($stockhs){ foreach($stockhs as $stockh): $stock = Stock::find_by_id($stockh->stock); ?>
                <tr>
                    <td><?php echo datetime_to_text($stockh->created) ?></td>
                    <td class="money"><b>
                <a href='sales-details?id=<?php echo base64_encode($stockh->id) ?>'><?php echo $stock->name  ?></a>
                        </b></td>
                        <td><b><?php echo ucfirst($stockh->poption) ?></b></td>
                    <td class="money"><b><?php echo $stockh->prev ?></b></td>
                <td class="money"><b><?php echo $stockh->qty  ?></b></td>       
                    <td class="money"><b><?php echo $stockh->new ?></b></td> 
                    <td><?php echo $stockh->price ?></td>
                    <td class="money"><b><?php echo round(($stockh->price * $stockh->qty),2);  ?></b></td>                                        
               <td><?php $user = User::find_by_id($stock->createdby); if ($user){ echo $user->name; } ?></td>               
               <td class="money"><b><?php echo $stockh->remarks  ?></b></td>
                </tr>
                <?php endforeach; } else {
                    echo "<tr><td colspan=\"9\"><div class='alert-info'>No records to show</div></td></tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'layouts/footer.php';