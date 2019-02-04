<?php
    require_once'includes/initialize.php'; 
    $stock = Stock::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

    if(filter_input(INPUT_POST, "submit")){
        $stock->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        $stock->about = trim(filter_input(INPUT_POST, "about", FILTER_DEFAULT));
         $stock->price = trim(filter_input(INPUT_POST, "price", FILTER_DEFAULT));
         $stock->th = trim(filter_input(INPUT_POST, "th", FILTER_DEFAULT));
        if($stock && $stock->save()){
            $session->message("{$stock->name} saved successfully");
            redirect_to("settings-stock-view.php?&id=".base64_encode($stock->id));
        }else {
            $message = "An error occured";
        }
    }
            if(filter_input(INPUT_POST, "cancel")){
            redirect_to("settings-stock-view.php?&id=".base64_encode($stock->id));
        }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>

    <div class="container1">
    <h3 style="text-align: center; ">Stock Items</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-stock-add.php"><h4>Add Stock Items</h4></a></li>
        <li><a href="settings-stock.php"><h4>All Stock Items</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
                                        <table class="form_table">
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Stock Items:</label></span></td>
                                            <td><input autofocus class="input-xxlarge" type="text" id="name" oninput="" name="name" value="<?php echo $stock->name ?>" placeholder="Enter Name..:" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">About:</label></span></td>
                                            <td>
                                                <textarea class="input-xxlarge" id="about" name="about"  placeholder="Enter Description..:" required><?php echo $stock->about ?></textarea>
                                            </td>
                                        </tr>
                                          <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Unit Price:</label></span></td>
                                            <td><input class="input-xxlarge" type="text" oninput="" name="price" value="<?php echo $stock->price ?>" placeholder="Enter Name..:" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Opening Quantity<br /> (cannot be modified):</label></span></td>
                                            <td><input disabled="" class="input-xxlarge" type="text"  value="<?php echo $stock->qty ?>"  validation/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Low Threshold warning Quantity:</label></span></td>
                                            <td><input  class="input-xxlarge" type="number" id="th" oninput="" name="th" value="<?php echo $stock->th ?>" placeholder="Enter Threahold warning qty..:" required validation/></td>
                                        </tr>                                          
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="submit" name="cancel" value="CANCEL" class="btn btn-danger" /></td>
                                        </tr>
                                        </table>
                                </form>
    </div>
</div>
<?php require_once './layouts/footer.php'; 