<?php
  require_once'includes/initialize.php'; 
  $us = User::find_by_id($session->id);
  require_once 'layouts/header.php';
  require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Dashboard</h3>      
     <hr/>
         <div style="text-align: center; font-family: sans-serif;">    
       <?php
          echo "Summary for ". date("d M, Y") ." as at " . date("h:i a");
        ?>
      </div>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <div class="dashboard_container">

<!--###################################################################-->

  <div class="dashboard_block left" style="border: 1px solid #5AD6F8;box-shadow:20px 20px 50px #5AD6F8">
        <h4>Today's Sales Data</h4><hr />
        <?php
     
         if($session->role == 1){
        	$total=0;    $ss = 0;
          // get upcoming for all users
          $users = User::find_all();
           if ($users){  ?>
           <table class="dash_table"  style="font-size: 14px">
                <thead>
                    <th>USER</th>                    
                    <th>TOTAL SALES AMOUNT GH &cent;</th>                    
                </thead>
                <tbody><!-- type -->
                <?php 
                     foreach($users as $user): 
                   
                    $ss = Stockhistory::sales_today($user->id);    ?>
                       <tr>
                          <td><?php echo $user->name ?></td> 
                          <td><?php $total += $ss; echo (double)$ss ?></td>               
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: white; color: black;font-weight: bolder">
                      <td>TOTAL</td>
                      <td class="money"><?php echo round($total,2) ?></td>
                    </tr>
                </tbody>
            </table>
          <?php  } else { ?>
            <div class="alert alert-info">No records to display</div>
         <?php } 

        } else {
          //get upcomin for just one user
        	$t = 0; $total = 0; $sales = NULL;
          	$sales = Stockhistory::get_latest_sales($session->id);
           if ($sales){  ?>
           <table class="dash_table">
                <thead>
                    <th>DATE</th>
                    <th>DESCRIPTION</th>
                    <th>QTY</th>
                    <th>UNIT PRICE</th>
                    <th>AMOUNT GH &cent;</th>
                </thead>
                <tbody><!-- type -->
                <?php 
          foreach($sales as $sale): $stock = Stock::find_by_id($sale->stock); ?>
                    <tr>
                      <td><?php echo datetime_to_text($sale->created) ?></td>
                      <td><b>
                         <a target="_new" href='sales-details.php?id=<?php echo base64_encode($sale->id) ?>'><?php echo $stock->name  ?></a>
                          </b></td>
                      <td class="money"><b><?php echo $sale->qty  ?></b></td>       
                      <td><?php  echo $sale->price ?></td>
                      <td class="money"><b><?php $t = $sale->amount_paid; $total += $t; echo round($t,2); ?></b></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: white; color: black;font-weight: bolder">
                      <td colspan="4">TOTAL</td>
                      <td class="money"><?php echo $total ?></td>
                    </tr>
                </tbody>
            </table>
          <?php  } else { ?>
            <div class="alert alert-info">No records to display</div>
         <?php } 
        } ?>
       </div>

       <!--###################################################################-->

       <div class="dashboard_block right" style="border: 1px solid #5AD6F8;box-shadow:20px 20px 50px #5AD6F8;">
        <h4>Today's Subscription Payment Receipts</h4><hr />
         <?php
           $categorys = Category::find_all();
         if($session->role == 1){ ?>
          
        <table class="dash_table">
          <thead>
            <th>User</th>
            <?php
             
              $comps;
              $ids;
              $userrs = User::find_all();
                        $arr_length = count($categorys);
              $arr_length1 = count($userrs);
           
         if ($categorys){ foreach ($categorys as $category) { $ids = NULL; ?>
              <th><?php echo ucwords($category->name)  ?></th>
            <?php
              $custs = Package::find_all_for_category($category->id);
                                                            
              if($custs){ foreach($custs as $cust):
                  $ids[] = $cust->id;
              endforeach;
              $idds = implode(",", $ids);
              $comps[] = $idds;
             }                           }
         }
             $amounts1;
   
	          		for ($ct = 0; $ct < $arr_length; $ct++):
	          			$amounts1[$ct] = 0;
	          		endfor;
            ?>
            <th>Total (GHS)</th>
          </thead>
          <tbody>
            <?php for ($ct = 0; $ct < $arr_length1; $ct++):
            	$tot =0;
             ?>
            <tr>
              <td><?php echo ucwords($userrs[$ct]->name) ?></td>           
                <?php for ($cct = 0; $cct < $arr_length; $cct++){ $amt =0; ?>
            <td><?php $amt = Payment::find_for_cate_user($userrs[$ct]->id,$comps[$cct]); $amounts1[$cct] += (double) $amt; $tot += (double)$amt; echo (double )$amt; ?></td>
                <?php  } ?>
                <td><?php  echo (double)$tot  ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                     <?php for ($cctt = 0; $cctt < $arr_length; $cctt++){ ?>
            <td><?php echo  (double)$amounts1[$cctt]  ?></td>
                <?php  } ?>
                    <td><?php echo (double) array_sum($amounts1) ?></td>
                </tr>
            </tfoot>
        </table>
      <?php  } else { ?>
        <table class="dash_table">
          <thead>
            <th>Category</th>
            <th>Amount </th>
          </thead>
          <tbody>
            <?php $amt=0; $amtt=0; foreach ($categorys as $category) {  
              $custs = Package::find_all_for_category($category->id);
              if($custs){ foreach($custs as $cust):
                  $ids[] = $cust->id;
              endforeach; }
              $idds = implode(",", $ids); ?>              
              <tr>
                <td><?php  echo ucwords($category->name) ?></td>
                <td><?php  $amtt += $amt = Payment::find_for_cate_user($session->id,$idds); echo (double)$amt ?></td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td>TOTAL (GHS)</td>
              <td><?php echo (double)$amtt  ?></td>
            </tr>
          </tfoot>
        </table>
        <?php }  ?>
       </div>       


       <div class="dashboard_block left" style="border: 1px solid #FFBA85;box-shadow:20px 20px 50px #FFBA85; ">         
        <h4>Upcoming Subscription Payments</h4><hr />

        <?php
          $subs = NULL;
         if($session->role == 1){
          // get upcoming for all users
          $subs = Subscription::get_upcoming(0);
        } else {
          //get upcomin for just one user
          $subs = Subscription::get_upcoming($session->id);
        }

        if ($subs){  ?>
        <table class="dash_table">
          <thead>
            <th>Date Due</th>
            <th>Customer/Number</th>
            <th>Package</th>
            <th>Amount (GHS)</th>
            <th>No. of msgs sent</th>
            <th><!--<a class="button" href="sendmsgs.php?id=all" onclick="return confirm('Send Payment reminder to all in List?')">SMS ALL</a>--></th>
          </thead>
          <tbody>
            <?php foreach($subs as $sub): ?>
            <tr>
              <td><?php echo datetime_to_text($sub->nextdate) ?></td>
              <td> <a target="_new" href="subscriptions-details.php?&id=<?php echo base64_encode($sub->id) ?>"><?php $cust = Customer::find_by_id($sub->customer); echo $cust->name."<br />". $cust->phone;  ?></a></td>
              <td><?php $package = Package::find_by_id($sub->package); echo $package->name  ?></td>
              <td><?php echo $package->amount ?></td>
              <td><?php echo $sub->msgs ?></td>
              <td><a onclick="return confirm('Send Payment reminder to  <?php echo $cust->name ." at " . $cust->phone ?>?')" href="sendmsgs.php?id=personal&id=<?php echo base64_encode($sub->id) ?>">SMS</a></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php  } else { ?>
        <div class="alert alert-warning">No records to display</div>
     <?php } ?>
       </div>

       <!--###################################################################-->


       <div class="dashboard_block right" style="border: 1px solid #FD5959;box-shadow:20px 20px 50px #FD5959;">
        <h4>Past Due Subscription Payments</h4><hr />
         <?php
        $subs = NULL;
         if($session->role == 1){
          // get upcoming for all users
          $subs = Subscription::get_past(0);
        } else {
          //get upcomin for just one user
          $subs = Subscription::get_past($session->id);
        }

        if ($subs){  ?>
        <table class="dash_table">
          <thead>
            <th>Date Due</th>
            <th>Customer/Number</th>
            <th>Package</th>
            <th>Amount (GHS)</th>
            <th>No. of msgs sent</th>
            <th><a class="button" href="send_msg.php?id=all" onclick="return confirm('Send SMS to all in List?')">SMS ALL</a></th>
          </thead>
          <tbody>
            <?php foreach($subs as $sub): ?>
            <tr>
              <td><?php echo datetime_to_text($sub->nextdate) ?></td>
              <td> <a target="_new" href="subscriptions-details.php?&id=<?php echo base64_encode($sub->id) ?>"><?php $cust = Customer::find_by_id($sub->customer); echo $cust->name."<br />". $cust->phone;  ?></a></td>
              <td><?php $package = Package::find_by_id($sub->package); echo $package->name  ?></td>
              <td><?php echo $package->amount ?></td>
              <td><?php echo $sub->msgs ?></td>
              <td><a onclick="return confirm('Send SMS to <?php echo $cust->name ." at " . $cust->phone ?>?')" href="send_msg.php?id=personal&id=<?php echo base64_encode($sub->id) ?>">SMS</a></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php  } else { ?>
        <div class="alert alert-danger">No records to display</div>
     <?php } ?>
       </div> 

       <div class="dashboard_block left" style="border: 1px solid #5AD6F8;box-shadow:20px 20px 50px #5AD6F8;">
        <h4>Today's New Installations</h4><hr />
         <?php
        $subs = NULL;
         if($session->role == 1){
          // get upcoming for all users
          $subs = Subscription::get_latest(0);
        } else {
          //get upcomin for just one user
          $subs = Subscription::get_latest($session->id);
        }

        if ($subs){  ?>
        <table class="dash_table">
          <thead>
            <th>Date Due</th>
            <th>Customer/Number</th>
            <th>Package</th>
            <th>Amount (GHS)</th>
          </thead>
          <tbody>
            <?php foreach($subs as $sub): ?>
            <tr>
              <td><?php echo datetime_to_text($sub->nextdate) ?></td>
              <td> <a target="_new" href="subscriptions-details.php?&id=<?php echo base64_encode($sub->id) ?>"><?php $cust = Customer::find_by_id($sub->customer); echo $cust->name."<br />". $cust->phone;  ?></a></td>
              <td><?php $package = Package::find_by_id($sub->package); echo $package->name  ?></td>
              <td><?php echo $package->amount ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php  } else { ?>
        <div class="alert alert-info">No records to display</div>
     <?php } ?>
       </div>            

        
       <?php   if($session->role == 1){  ?>   
       <div class="dashboard_line left" style="box-shadow:20px 20px 50px #FD5959;">
        <h4>Low Stock Warnings</h4><hr /> 
  <table class="dash_table">
        <thead>
          <th>STOCK ITEM</th>
          <th>DESCRIPTION</th>
          <th>THRESHOLD</th>
          <th>AVAILABLE</th>
          <th></th>
        </thead>
        <tbody>
            <?php $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock):
              if ($stock->th >= $stock->qty){
             ?>
          <tr>
                    <td><?php echo $stock->name ?></td>
                    <td><?php echo $stock->about ?></td>
                    <td style="font-size: 14px; color: green"><?php echo $stock->th ?></td>
                    <td style="font-size: 14px; color: red"><b><?php echo $stock->qty ?></b></td>
                               
                    <td>
                        <a target="_new" class="btn-success" href="settings-stock-view.php?&id=<?php echo base64_encode($stock->id) ?>">VIEW DETAILS</a> &nbsp;&nbsp;
                        <a target="_new" class="btn-info" href="settings-stock-modify.php?&id=<?php echo base64_encode($stock->id) ?>">MODIFY STOCK</a> 
                    </td>
                </tr>
                <?php } endforeach; } else {  ?>
                <tr>
                    <td colspan="4" style="text-align: center;"><div class='alert-info'>No stock items to Display
                        <a href="settings-stock-add.php">Add Stock Item</a></div></td>
                </tr>
                <?php } ?>
        </tbody>
      </table>
       </div>        
        <?php } ?> 

       <!--###################################################################-->

               
        <div class="dashboard_line" style="border: 1px solid #F5F5F5;box-shadow:20px 20px 50px #F5F5F5;">
        <h4>Overall Subscription stats for past <?php echo $us->graph ?> months</h4><hr /> 
        <canvas id="substats" class="dashboard_canvas">
          
        </canvas>
       </div>

       <!--###################################################################-->

       
        <div class="dashboard_line" style="border: 1px solid #F5F5F5;box-shadow:20px 20px 50px #F5F5F5;">
        <h4>Overall Sales stats for past <?php echo $us->graph ?> months</h4><hr /> 
        <canvas id="salestats" class="dashboard_canvas">
          
        </canvas>        
       </div>          
	</div>
</div>
<script src="chart/Chart.bundle.js"></script>
<script>
var ctx = document.getElementById("substats").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php  

          $substats;
          for ($i = ($us->graph-1); $i >= 1; $i--){
              $date = new DateTime(date("Y-m-d"));    
              $date->sub(new DateInterval("P".$i. "M") );     
              echo "'".$date->format('F, Y')."'".",";
               if($session->role == 1){
                  // get upcoming for all users
                  $substats[] = Subscription::get_total_count(0,$date->format('m'),$date->format('Y'));
                } else {
                  //get upcomin for just one user
                 $substats[] = Subscription::get_total_count($session->id,$date->format('m'),$date->format('Y'));
                }              
            }
            if($session->role == 1){
                  // get upcoming for all users
                  $substats[] = Subscription::get_total_count(0,date('m'),date('Y'));
                } else {
                  //get upcomin for just one user
                 $substats[] = Subscription::get_total_count($session->id,date('m'),date('Y'));
                }
            echo "'".date('F, Y')."'" ?>],
        datasets: [{
            label: "Subscription Data",
            data: [
            <?php  
          for ($i = 0; $i < ($us->graph-1); $i++){
            echo $substats[($i)]. ",";
          } echo $substats[($us->graph-1)]
            ?>],
            borderWidth: 1
        }]
    },
    options: {
      responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                title: 'Subscription Data',
                gridLines: {
                  display: false
                }
            }],
            xAxes: [{
                ticks: {
                    beginAtZero:true
                },                
                gridLines: {
                  display: false
                }
            }]
        }        

    }
});


var ctx = document.getElementById("salestats").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php  
          $salesdata;
          for ($i = ($us->graph-1); $i >= 1; $i--){
              $date = new DateTime(date("Y-m-d"));    
              $date->sub(new DateInterval("P".$i. "M") );     
              echo "'".$date->format('F, Y')."'".",";
               if($session->role == 1){
                  // get upcoming for all users
                  $salesdata[] = Stockhistory::get_total_amount(0,$date->format('m'),$date->format('Y'));
                } else {
                  //get upcomin for just one user
                 $salesdata[] = Stockhistory::get_total_amount($session->id,$date->format('m'),$date->format('Y'));
                }              
            }
            if($session->role == 1){
                  // get upcoming for all users
                  $salesdata[] = Stockhistory::get_total_amount(0,date('m'),date('Y'));
                } else {
                  //get upcomin for just one user
                 $salesdata[] = Stockhistory::get_total_amount($session->id,date('m'),date('Y'));
                }
            echo "'".date('F, Y')."'" ?>],
        datasets: [{
            label: "Sales Data (GHS)",
            data: [
            <?php  
          for ($i = 0; $i < ($us->graph-1); $i++){
            echo $salesdata[($i)]. ",";
          } echo $salesdata[($us->graph-1)]
            ?>],
            borderWidth: 1
        }]
    },
    options: {
      responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                title: 'Sales Data',
                gridLines: {
                  display: false
                }
            }],
            xAxes: [{
                ticks: {
                    beginAtZero:true
                },                
                gridLines: {
                  display: false
                }
            }]
        }
        

    }
});
</script>
<?php require_once 'layouts/footer.php';