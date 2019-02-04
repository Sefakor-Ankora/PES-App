<?php
    require_once'includes/initialize.php'; 
        $stock = Stock::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

    if(filter_input(INPUT_POST, "submit")){
        $type = trim(filter_input(INPUT_POST, "type", FILTER_DEFAULT));
        $qty = trim(filter_input(INPUT_POST, "qty", FILTER_DEFAULT));
        if($type == "subtract" && $stock->qty < $qty){
        $session->message("Couldn't subtract!<br />Current Qty: {$stock->qty}<br />Requested Qty: {$qty}");
        redirect_to("settings-stock-modify.php?&id=".base64_encode($stock->id));
        } else {
            $stockh = new Stockhistory();
            $stockh->stock = $stock->id;
            $stockh->type = $type;
            $stockh->prev = $stock->qty;
            $stockh->qty = trim(filter_input(INPUT_POST, "qty", FILTER_DEFAULT));
            if($type == "subtract"){
                  $stockh->new = $stock->qty - $qty;
            } else {
                  $stockh->new = $stock->qty + $qty;
            }
          
            $stockh->remarks = trim(filter_input(INPUT_POST, "remarks", FILTER_DEFAULT));
            $stockh->created = strftime("%Y-%m-%d %H:%M:%S", time());
            $stockh->createdby = $session->id;  
            $stock->qty = $stockh->new;
            $stock->save();
            if($stockh && $stockh->save()){ 
            $session->message("Stock updated successfully");
            redirect_to("settings-stock-view.php?&id=".base64_encode($stock->id));
            }else {
                $message = "An error occured";
            }
           

        }
        $stock->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        $stock->about = trim(filter_input(INPUT_POST, "about", FILTER_DEFAULT));
        $stock->qty = trim(filter_input(INPUT_POST, "qty", FILTER_DEFAULT));
        $stock->created = strftime("%Y-%m-%d %H:%M:%S", time());
        $stock->createdby = $session->id;        

    }
    
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>

    <div class="container1">
    <h3 style="text-align: center; "> Modify Item Stock -<?php echo $stock->name ?></h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-stock-add.php"><h4>Add Stock Items</h4></a></li>
        <li><a href="settings-stock.php"><h4>All Stock Items</h4></a></li>
        <li><a href="settings-stock-view.php?&id=<?php  echo base64_encode($stock->id); ?>"><h4>Go back</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
            <table class="form_table">
                <tr>
                    <td><span class="help-inline"><label class="control-label" for="search">Item</label></span></td>
                    <td><?php echo $stock->name  ?></td>
                </tr>
                <tr>
                    <td><span class="help-inline"><label class="control-label" for="search">Current Qty:</label></span></td>
                    <td><?php echo $stock->qty  ?></td>
                </tr>
                <tr>
                    <td><span class="help-inline"><label class="control-label" for="search">Transaction Type:</label></span></td>
                    <td>
                        <select autofocus name="type" class="input-xxlarge" required>
                            <option value="">Please Select</option>
                            <option value="add">Add</option>
                            <option value="subtract">Subtract</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><span class="help-inline"><label class="control-label" for="search">Stock Qty:</label></span></td>
                    <td><input  class="input-xxlarge" type="text" id="name" oninput="" name="qty" value="" placeholder="Enter Quantity..:" required validation/></td>
                </tr>
                <tr>
                    <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Remarks:</label></span></td>
                    <td>
                        <textarea class="input-xxlarge" id="about" name="remarks" placeholder="Enter Description..:" required></textarea>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp; <button onclick="history.go(-1)" class="btn btn-danger">Go Back</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php require_once './layouts/footer.php'; 