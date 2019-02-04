<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php';
$users = User::find_all(); 
 ?>
<div class="container1">
    <h3 style="text-align: center; ">Reports-Overview</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="reports.php"><h4>Overview</h4></a></li>
        <li><a href="reports-sales.php"><h4>Sales</h4></a></li>
        <li><a href="reports-installations.php"><h4>Subscriptions</h4></a></li>
        <li><a href="reports-stock.php"><h4>Stock</h4></a></li>     
    </ul><br /><hr />
    <div id="container2">
       <table class="data_pes1" style="width: 600px; margin-left: 70px">            
      <tr><td colspan="4"><hr /><h4>Monthly Overview For <?php echo date("M, Y")  ?></h4></td></tr>                
                 <tr>                                
                    <td><b>Total sales this month</b></td>
                    <td><?php   $date = new DateTime(date("Y-m-d")); 
                 echo Stockhistory::get_total_amount(0,$date->format('m'),$date->format('Y'));  ?></td>
                   <td colspan="2">&nbsp;</td>
                </tr>
                <tr><td colspan="4"><hr /><h4>Subscription Overview for this month</h4></td></tr>                
                 <tr>                                
                    <td><b>Total Number of New Subscriptions</b></td>
                    <td><?php echo Subscription::get_total_count(0,$date->format('m'),$date->format('Y'));  ?></td>    
                    <td><b>Installation amount GH &cent;</b></td>         
                    <td><?php echo (double)Subscription::get_total_amount_type(0,"iamount",$date->format('m'),$date->format('Y'));  ?></td>    
                </tr>  
                  <tr>                                
                    <td><b>Administrative overhead amount GH &cent;</b></td>
                    <td><?php echo (double)Subscription::get_total_amount_type(0,"overhead",$date->format('m'),$date->format('Y'));   ?></td>    
                    <td><b>Subscription amount GH &cent;</b></td>
                    <td><?php echo (double)Subscription::get_total_amount_type(0,"amt",$date->format('m'),$date->format('Y')); ?></td>        
                </tr>
               <tr> 
                    <td><b>Total Amount from New Subscriptions GH &cent;</b></td>
                    <td><?php echo (double)Subscription::get_total_amount_type(0,"amountdue",$date->format('m'),$date->format('Y')); ?></td>
                    <td colspan="2">&nbsp;</td>
                </tr>                
                <tr><td colspan="4"><hr /><h4>Stock Item information</h4></td></tr>  
                 <tr>                                
                    <td colspan="4">
                  <table class="data_pes">
        <thead>
          <th>STOCK ITEM</th>
                <th>QUANTITY SOLD</th>
                <th>TOTAL AMOUNT GH &cent;</th>
        </thead>
        <tbody>
            <?php $t=0; $tt=0; $tot=0; $tot1=0; $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock): $t = (double)Stockhistory::count_total_amount($stock->id,$date->format('m'),$date->format('Y')); if ($t != 0){ ?>
          <tr>
                    <td><?php echo $stock->name ?></td>
                    <td style="text-align: center;"><b><?php $tot += $t; echo $t; ?></b></td>                     
                    <td style="text-align: center;"><b><?php $tot1 += $tt = (double)Stockhistory::get_total_amount_item($stock->id,$date->format('m'),$date->format('Y')); echo $tt; ?></b></td>              
                </tr>
                <?php } endforeach; } ?>
                <tr><td colspan="3"><hr /></td></tr>              
                <tr>
                  <td><b>TOTAL</b></td>
                  <td style="text-align: center;" class="money"><?php echo $tot ?></td>
                  <td style="text-align: center;" class="money"><?php echo $tot1 ?></td>
                </tr>
        </tbody>
      </table>
                    </td>    
                </tr>                  
        </table>        
             <hr /> 
          
	</div>
</div>
<?php require_once 'layouts/footer.php';