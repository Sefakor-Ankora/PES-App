<?php
    require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Installations</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="subscriptions-add.php"><h4>Add New Subcription</h4></a></li>
        <li><a href="subscriptions-search.php"><h4>Search Subscriptions</h4></a></li>
        <li><a href="subscriptions.php"><h4>New Subcription Report</h4></a></li>     
    </ul><br />
    <div id="container2">
           <table class="data_pes" style="width: 90%">
            <thead style="position: relative;">
                <th>START DATE</th>
                <th>END DATE</th>                
                <th>PACKAGE</th>
                <th>USER</th>
                <th>ORDER</th>
                <th>DIRECTION</th>                
                <th></th>
            </thead>
    		<tbody>              
                <tr>
                    <td><input type="date" id="sdate" class="input-medium"  /></td>
                    <td><input type="date" id="edate" class="input-medium"  /></td>
                    <td>
                        <select  id="package" class="input-medium">
                        <option value="">All</option>
                  <?php $categorys = Category::find_all(); if ($categorys){ foreach($categorys as $category): ?>
                        <optgroup label="<?php echo $category->name; ?>">
                            <?php $packages = Package::find_all_for_category($category->id);      
                            foreach ($packages as $package): ?>
                        <option value="<?php echo $package->id ?>"><?php echo $package->name ?></option>
                    <?php endforeach; echo "</optgroup>"; endforeach; } ?>
                    </select>
                    </td>
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
                        <option value="stock">Package</option>                        
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
        var package = document.getElementById('package').value;
        var user = document.getElementById('user').value;
        var order = document.getElementById('order').value;
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
                    mlhttp.open("GET","as/search.php?type=subsearch&sdate="+ sdate + "&edate="+edate + "&package=" +package+"&user="+user+"&order="+order+"&direction="+direction,true);
                    mlhttp.send();  

    }


</script>
<?php require_once 'layouts/footer.php';