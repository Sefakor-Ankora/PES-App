<?php
    require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Reports-Custom Stock Report</h3>        
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
           <table class="data_pes" style="width: 90%">
            <thead style="position: relative;">
                <th>START DATE</th>
                <th>END DATE</th>                
                <th>ITEM</th>
                <th>USER</th>
                <th>RECORD TYPE</th>           
                <th></th>
            </thead>
            <tbody>              
                <tr>
                    <td><input type="date" id="sdate" class="input-medium" value="<?php echo date("Y-m-01") ?>"  /></td>
                    <td><input type="date" id="edate" class="input-medium"  /></td>
                    <td>
                        <select  id="stock" class="input-medium">
                        <option value="">All</option>
                  <?php  $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock): ?>                 
                        <option value="<?php echo $stock->id ?>"><?php echo $stock->name; ?></option>
                    <?php endforeach; } ?>
                    </select>
                    </td>
                    <td><select  id="user" class="input-medium">
                        <option value="">All</option>
                  <?php $users = User::find_all(); if ($users){ foreach($users as $user): ?>
                        <option value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                    <?php endforeach; } ?>
                    </select></td>
                    <td> <select  id="type" class="input-medium">                                          
                        <option value="">All</option>                   
                        <option value="subscription">Subscription</option>                   
                        <option value="add">New Stock</option>                   
                        <option value="sale">Sale</option>                   
                        <option value="subtract">Stock Substracted</option>                        
                    </select></td>
                    <td>
                        <button onclick="show_report();" class="btn btn-info">SHOW</button>
                    </td>
                </tr>
            </tbody>
        </table>
        
             <hr />        
            <div id="results"></div>         
    </div>
</div>
<script type="text/javascript">
     function show_report(){
        document.getElementById('results').innerHTML = "Please Wait, Processing...";
        var sdate = document.getElementById('sdate').value;
        var edate = document.getElementById('edate').value;
        var stock = document.getElementById('stock').value;
        var user = document.getElementById('user').value;
        var type = document.getElementById('type').value;
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('results').innerHTML = mlhttp.responseText;
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
                    mlhttp.open("GET","as/search.php?type=stockreport&sdate="+ sdate + "&edate="+edate + "&stock=" +stock+"&user="+user+"&rtype="+type,true);
                    mlhttp.send();  

    }


</script>
<?php require_once 'layouts/footer.php';