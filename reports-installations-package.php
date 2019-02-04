<?php
    require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php';
$packages = Package::find_all(); ?>
<div class="container1">
    <h3 style="text-align: center; ">Reports-Subscription Report / Subscription Package</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="reports.php"><h4>Overview</h4></a></li>
        <li><a href="reports-sales.php"><h4>Sales</h4></a></li>
        <li><a href="reports-installations.php"><h4>Installations</h4></a></li>
        <li><a href="reports-stock.php"><h4>Stock</h4></a></li>     
    </ul><br /><hr />
    <ul class="navsmall">  
        <li><a href="reports-installations.php"><h4>Subscription Report / User</h4></a></li>
        <li><a href="reports-installations-package.php"><h4>Subscription Report / Package </h4></a></li>  
        <li><a href="reports-installations-custom.php"><h4>Custom Report</h4></a></li>
    </ul><br /><hr />
   <div id="container2">
           <form action="" method="POST">
         <ul class="navsmall1">  
        <li>
            <h4>Display data from past 
                <select name="mon" class="input-medium">
                <?php for ($i =2; $i <= 12; $i ++){ ?>
                <option value="<?php echo $i; ?>" <?php if (isset($_POST['mon']) && $_POST['mon'] == $i){ echo "selected"; }  ?> ><?php  echo $i . " Months" ?></option>
            <?php  } ?>
            </select> 
             for <select  name="st" class="input-medium">
                        <option value="">All</option>
                  <?php  if ($packages){ foreach($packages as $package): ?>
                        <option value="<?php echo $package->id ?>" <?php if (isset($_POST['st']) && $_POST['st'] == $package->id){ echo "selected"; }  ?>><?php echo $package->name ?></option>
                    <?php endforeach; } ?>
                    </select>
                    &nbsp;&nbsp;
                    <input type="submit" value="Go" name="submit" class="btn-info" >
            </h4>
        </li>
    </ul><hr />        
        </form>
     <div class="dashboard_line" style="border: 1px solid #F5F5F5">
        <canvas id="salestats" class="dashboard_canvas">
          
        </canvas>
       </div>       
    </div>
</div>
<script src="chart/Chart.bundle.js"></script>
<script>
    <?php  if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST));   ?>
    var ctx = document.getElementById("salestats").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php
          $salesdata;
          for ($i = ($mon-1); $i >= 1; $i--){
              $date = new DateTime(date("Y-m-d"));    
              $date->sub(new DateInterval("P".$i. "M") );     
              echo "'".$date->format('F, Y')."'".",";
               if($st == ""){
                  // get upcoming for all users
                  $salesdata[] = Subscription::count_total(0,$date->format('m'),$date->format('Y'));
                } else {
                  //get upcomin for just one user
                 $salesdata[] = Subscription::count_total($st,$date->format('m'),$date->format('Y'));
                }              
            }
            if($st == ""){
                  // get upcoming for all users
                  $salesdata[] = Subscription::count_total(0,date('m'),date('Y'));
                } else {
                  //get upcomin for just one user
                 $salesdata[] = Subscription::count_total($st,date('m'),date('Y'));
                }
            echo "'".date('F, Y')."'" ?>],
        datasets: [{
            label: "Sales Data (GHS)",
            data: [
            <?php  
          for ($i = 0; $i < ($mon-1); $i++){
            echo $salesdata[($i)]. ",";
          } echo $salesdata[($mon-1)]
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
                title: 'Stock Data',
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
<?php  } ?>
</script>
<?php require_once 'layouts/footer.php';