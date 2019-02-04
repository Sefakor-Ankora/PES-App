<?php
    require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php';
$stocks = Stock::find_all();  ?>
<div class="container1">
    <h3 style="text-align: center; ">Reports-Stock</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="reports.php"><h4>Overview</h4></a></li>
        <li><a href="reports-sales.php"><h4>Sales</h4></a></li>
        <li><a href="reports-installations.php"><h4>Installations</h4></a></li>
        <li><a href="reports-stock.php"><h4>Stock</h4></a></li>     
    </ul><br /><hr />
    <ul class="navsmall">  
        <li><a href="reports-stock.php"><h4>Stock Report / Quantities</h4></a></li> 
        <li><a href="reports-stock-custom.php"><h4>Custom Report</h4></a></li>
    </ul><br /><hr />
    <div id="container2">
        <form action="" method="POST">
         <ul class="navsmall1">  
        <li>
            <h4>Display data from 
                <select name="mon" class="input-medium">
                <?php for ($i =2; $i <= 12; $i ++){ ?>
                <option value="<?php echo $i; ?>" <?php if (isset($_POST['mon']) && $_POST['mon'] == $i){ echo "selected"; }  ?> ><?php  echo $i . " Months" ?></option>
            <?php  } ?>
            </select> 
             for <select  name="st" class="input-medium">
                  <?php  if ($stocks){ foreach($stocks as $stock): ?>
                        <option value="<?php echo $stock->id ?>" <?php if (isset($_POST['st']) && $_POST['st'] == $stock->id){ echo "selected"; }  ?>><?php echo $stock->name ?></option>
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
        extract(filter_input_array(INPUT_POST));  
       
         ?>
    var ctx = document.getElementById("salestats").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php
          $availabledata; $solddata;
          for ($i = ($mon-1); $i >= 1; $i--){
              $ti = 0; $quant=0; $used=0;
              $date = new DateTime(date("Y-m-d"));    
              $date->sub(new DateInterval("P".$i. "M") );     
              echo "'".$date->format('F, Y')."'".",";
                  //get upcomin for just one item
                 $sss = Stockhistory::get_available_data($st,$date->format('m'),$date->format('Y')); 
                    foreach($sss as $ss):
                      if($ti ==0){
                          $quant += $ss->prev;
                      }
                      if($ss->type =="add"){//add, openining balance, deleted subscription all increase the available qty
                          $quant += $ss->qty;               
                      } else {
                          $used += $ss->qty;               
                      }
                       $ti++;             
                   endforeach;
                     $availabledata[] = $quant;
                     $solddata[] = $used;
            }
            $ti = 0; $quant=0; $used=0;
                  //get upcomin for just one item
                $sss = Stockhistory::get_available_data($st,date('m'),date('Y'));
                 foreach($sss as $ss):
                      if($ti ==0){
                          $quant += $ss->prev;
                      }
                      if($ss->type =="add"){//add, openining balance, deleted subscription all increase the available qty
                          $quant += $ss->qty;               
                      } else {
                          $used += $ss->qty;               
                      }
                       $ti++;             
                   endforeach;
                     $availabledata[] = $quant;
                     $solddata[] = $used;

            echo "'".date('F, Y')."'" ?>],
           datasets: [{
            label: "Avaliable quantity",
            backgroundColor: 'rgb(54, 162, 235)', //blue
            data: [
            <?php  
          for ($i = 0; $i < ($mon-1); $i++){
            echo $availabledata[($i)]. ",";
          } echo $availabledata[($mon-1)]
            ?>],
            borderWidth: 1
        },
        {
            label: "Used Quantity",
            backgroundColor: 'rgb(201, 203, 207)', //grey
            data: [
            <?php  
          for ($i = 0; $i < ($mon-1); $i++){
            echo $solddata[($i)]. ",";
          } echo $solddata[($mon-1)]
            ?>],
            borderWidth: 1
        }
        ]
    },
    options: {
      responsive: true,
          tooltips: {
                mode: 'index',
                intersect: false
            },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                title: 'Stock Levels per month',
                gridLines: {
                  display: false
                },
                stacked: true
            }],
            xAxes: [{
                ticks: {
                    beginAtZero:true
                },                
                gridLines: {
                  display: true
                },
                stacked: true
            }]
        }
        

    }
});
<?php  } ?>
</script>
<?php require_once 'layouts/footer.php';