<?php
    require_once'includes/initialize.php'; 
        $package = Package::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

    if(filter_input(INPUT_POST, "submit")){
         $items = $_POST['items'];
        $qty = $_POST['qty'];
        $package->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        $package->about = trim(filter_input(INPUT_POST, "about", FILTER_DEFAULT));
        $package->category = trim(filter_input(INPUT_POST, "category", FILTER_DEFAULT));
        $package->amount = trim(filter_input(INPUT_POST, "amount", FILTER_DEFAULT));
                $package->items = implode(",", $items);
        $package->qty = implode(",", $qty);
        if($package && $package->save()){
            $session->message("Packages saved successfully");
            redirect_to("settings-packages.php");
        }else {
            $message = "An error occured";
        }
    }
        if(filter_input(INPUT_POST, "cancel")){
            redirect_to("settings-packages.php");
        }
    require_once 'layouts/header.php';
    require_once './layouts/sidepane.php'; ?>
 
    <div class="container1">
    <h3 style="text-align: center; ">Packages</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-packages-add.php"><h4>Add Package</h4></a></li>
        <li><a href="settings-packages.php"><h4>All Packages</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
            <table class="form_table">
           <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Category:</label></span></td>
                <td>
                <select autofocus name="category" class="input-xxlarge" required>
                        <option value="">Please Select</option>
                  <?php $cats = Category::find_all(); if ($cats){ foreach($cats as $catt): ?>
                        <option value="<?php echo $catt->id ?>" <?php if ($catt->id == $package->category){ echo "selected"; } ?>><?php echo $catt->name ?></option>
                    <?php endforeach; } ?>
                    </select>
                </td>
            </tr>         
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Name:</label></span></td>
                <td><input  class="input-xxlarge" type="text" id="name" oninput="" name="name" value="<?php echo $package->name ?>" placeholder="Enter Name..:" required validation/></td>
            </tr>                                                                       
            <tr>
                <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">About:</label></span></td>
                <td>
                    <textarea class="input-xxlarge" id="about" name="about"  placeholder="Enter Description..:" required><?php echo $package->about ?></textarea>
                </td>
            </tr>

            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Unit Cost (GH &cent;):</label></span></td>
                <td><input class="input-xxlarge" type="text" id="amount" oninput="" name="amount" value="<?php echo $package->amount ?>" placeholder="Enter Amount..:" required validation/></td>
            </tr>
             <tr><td colspan="2"><hr /></td></tr>
<tr>
<td style="vertical-align: top;"><span class="help-inline"><label class="control-label" for="search">Select Required Items For Package</label></span></td>
<td>
<?php 
$items = explode(",", $package->items);
$qty = explode(",", $package->qty);
$stocks = Stock::find_all(); if($stocks){
foreach ($stocks as $stock): ?>
<input type="checkbox" name="items[]" <?php if (in_array($stock->id, $items)){ echo "checked";}  ?> value="<?php echo $stock->id ?>">&nbsp;&nbsp; <?php echo $stock->name ?>&nbsp;&nbsp;<input class="input-xlarge" type="number" name="qty[]" value="<?php if (in_array($stock->id, $items)){ echo $qty[array_search($stock->id, $items)]; }  ?>" placeholder="Enter Required  Quantity..:" /><br />
<?php endforeach;
} else {
echo "No Stock Items to Display";
}
?>
<br/>
</td>
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