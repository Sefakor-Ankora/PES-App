<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Search Sales</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="sales-add.php"><h4>New Sale</h4></a></li>
        <li><a href="sales-today.php"><h4>Today's Sales</h4></a></li>
        <li><a href="sales-my.php"><h4>Search Sales</h4></a></li>
    </ul><br />
    <div id="container2">                   
        <table class="data_pes" style="width: 90%">
            <thead style="position: relative;">
                <th>START DATE</th>
                <th>END DATE</th>                
                <th>STOCK ITEM</th>
                <th>PAYMENT OPTION</th>
                <th>USER</th>
                <th>ORDER BY</th>
                <th>DIRECTION</th>                
                <th></th>
            </thead>
    		<tbody>              
                <tr>
                    <td><input type="date" id="sdate" class="input-medium"  /></td>
                    <td><input type="date" id="edate" class="input-medium"  /></td>
                    <td>
                        <select  id="item" class="input-medium">
                        <option value="">All</option>
                  <?php $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock): ?>
                        <option value="<?php echo $stock->id ?>"><?php echo $stock->name ?></option>
                    <?php endforeach; } ?>
                    </select>
                    </td>
                    <td> <select  id="poption" class="input-medium">                                          
                        <option value="">All</option>
                            <option value="cash">Cash</option>
                            <option value="mobile money">Mobile Money</option>     
                            <option value="cheque">Cheque</option>                  
                            <option value="credit">Credit</option>                   
                    </select></td>
                    <td> <select  id="user" class="input-medium">
                        <?php if ($session->role == 1){  ?>
                        <option value="">All</option>
                  <?php $users = User::find_all(); if ($users){ foreach($users as $user): ?>
                        <option value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                    <?php endforeach; } } else { ?>
                        <option value="<?php echo $session->id  ?>">Me</option>
                    <?php  } ?>
                    </select></td>
                    <td> <select  id="order" class="input-medium">                                          
                        <option value="created">Date</option>                   
                        <option value="stock">Stock Items</option>                   
                        <?php if ($session->role == 1){   ?><option value="createdby">User</option> <?php }  ?>
                    </select></td>
                    <td> <select  id="direction" class="input-medium">                                          
                        <option value="ASC">Earliest First</option>                   
                        <option value="DESC">Latest First</option>                   
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
        var item = document.getElementById('item').value;
        var user = document.getElementById('user').value;
        var order = document.getElementById('order').value;
        var poption = document.getElementById('poption').value;
        var direction = document.getElementById('direction').value;
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
                    mlhttp.open("GET","as/search.php?type=salesearch&sdate="+ sdate + "&edate="+edate + "&item=" +item+"&user="+user+"&order="+order+"&direction="+direction+"&poption="+poption,true);
                    mlhttp.send();  

    }

   function total(qty){
        var up = parseFloat(document.getElementById('up').value);
        var prev = parseFloat(document.getElementById('prev').value);
        if(qty > prev){ alert("Quantity entered is greater than Existing Quantity");}
        document.getElementById('total_amount').value = parseFloat(up*parseFloat(qty)).toFixed(2);
    }
</script>
<?php require_once 'layouts/footer.php';
