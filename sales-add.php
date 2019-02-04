<?php
    require_once'includes/initialize.php'; 
    global $database;
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 

        $stock = Stock::find_by_id($item);        

        if ($stock->qty < $qty){
            $session->message("Quantity entered is greater than Existing Quantity<br />Existing: {$stock->qty}<br />Quantity: {$qty}");
            redirect_to("sales-add.php");
        }
        $stockh = new Stockhistory();
        $stockh->stock = $item;
        $stockh->qty = $qty;
        $stockh->price = $stock->price;
        $stockh->total_amount = $qty * $stockh->price;
        $stockh->amount_paid = $amount_paid;
        $stockh->remarks = $remarks;
        $stockh->createdby = $session->id;
        $stockh->prev = $stock->qty;
        $stockh->new = $stock->qty - $qty;
        $stockh->poption = $poption;
        $stockh->type = "sale";//same as subtract
        if ($date){
            $stockh->created = strftime("%Y-%m-%d %H:%M:%S", time());
        }else{
            $stockh->created = $saledate . " ".strftime("%H:%M:%S", strtotime($saledate2));
        }
        if($stockh && $stockh->save()){
            //update stock info
            $stock->qty -= $qty;
            $stock->save();
            $session->message("Sale completed successfully");
            redirect_to("sales-details.php?id=".base64_encode($stockh->id));
        }else {
            $message = "An error occured";
        }
    }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
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
        <form method='post' action="">  
            <table class="form_table">                
                 <tr>                                
                    <td><b>Use System Date</b>
                          <input class="input-large" type="radio" checked  onclick="document.getElementById('customtime').disabled=true;document.getElementById('customtime2').disabled=true"  name="date" value="1" required/>
          </td>
    <td>
        <b>Enter Date</b>
        <input class="input-large" type="radio" onclick="document.getElementById('customtime').disabled=false;document.getElementById('customtime2').disabled=false"  name="date" value="0" required />

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="date"  id="customtime" name="saledate" disabled />
        <select class="input-medium" id="customtime2"  name="saledate2" required  disabled>
            <option value="Midnight">Midnight</option>
         <?php 
         for ($i=12; $i<13; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 am\">{$i}:00 am</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} am\">{$i}:{$j} am</option>";
             }
             }
         } 
            for ($i=1; $i<12; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 am\">{$i}:00 am</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} am\">{$i}:{$j} am</option>";
             }
             }
         } 
         ?>

         <option value="Noon" selected="selected">Noon</option>
         <?php  
         for ($i=12; $i<13; $i ++){
             for ($j =0; $j <=60; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 pm\">{$i}:00 pm</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} pm\">{$i}:{$j} pm</option>";
             }
             }
         } 
            for ($i=1; $i<12; $i ++){
             for ($j =0; $j <=50; $j = $j+10){
                 if ($j == 0){
                 echo "<option value=\"{$i}:00 pm\">{$i}:00 pm</option>";
                 } else{
                 echo "<option value=\"{$i}:{$j} pm\">{$i}:{$j} pm</option>";
             }
             }
         } 
         ?>
         </select></td>
                </tr>
                <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Select Item:</label></span></td>
                    <td>
                        <select autofocus name="item" class="input-xxlarge" required onchange="get_details(this.value)">
                        <option value="">Please Select</option>
                  <?php $stocks = Stock::find_all(); if ($stocks){ foreach($stocks as $stock): ?>
                        <option value="<?php echo $stock->id ?>"><?php echo $stock->name ?></option>
                    <?php endforeach; } ?>
                    </select>
                    </td>
                </tr>
            </table>
            <div id="details"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
     function get_details(item){
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
                    mlhttp.open("GET","as/search.php?type=getdetails&value="+ item,true);
                    mlhttp.send();  

    }

   function total(qty){
        var up = parseFloat(document.getElementById('up').value);
        var prev = parseFloat(document.getElementById('prev').value);
        if(qty > prev){ alert("Quantity entered is greater than Existing Quantity");}
        document.getElementById('total_amount').value = parseFloat(up*parseFloat(qty)).toFixed(2);
    }
</script>                        
<?php require_once './layouts/footer.php'; 