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
            <table class="form_table">                         
                <tr>                 
                    <td>
                        <input autofocus="" onchange="loadform(this.value)" class="input-xlarge" type="text" oninput="" id="value" 
                               placeholder="Enter Receipt No..:" required validation/>
                        <button class="btn btn-info" onclick="loadform(document.getElementById('value').value)">SEARCH</button>
                    </td>
                </tr>
                
            </table>            
         <hr />         
            <div id="details"></div>
    </div> 
       
   
</div>
<script type="text/javascript">
     function loadform(item){
        document.getElementById('details').innerHTML = "Please Wait, Processing...";
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('details').innerHTML = mlhttp.responseText;
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
                    mlhttp.open("GET","as/search.php?type=sales&value="+ item,true);
                    mlhttp.send();  

    }
</script>
<?php require_once 'layouts/footer.php';
