<?php
    require_once'includes/initialize.php'; 
        $cat = Category::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));

    if(filter_input(INPUT_POST, "submit")){
        $cat->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        $cat->about = trim(filter_input(INPUT_POST, "about", FILTER_DEFAULT));
        if($cat && $cat->save()){
            $session->message("Category saved successfully");
            redirect_to("settings-categories.php");
        }else {
            $message = "An error occured";
        }
    }
        if(filter_input(INPUT_POST, "cancel")){
            redirect_to("settings-categories.php");
        }
    require_once 'layouts/header.php';
    require_once './layouts/sidepane.php'; ?>
 
    <div class="container1">
    <h3 style="text-align: center; ">Categories</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-categories-add.php"><h4>Add Category</h4></a></li>
        <li><a href="settings-categories.php"><h4>All Categories</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  
                                        <table class="form_table">
                                        <tr>
                                            <td><span class="help-inline"><label class="control-label" for="search">Name:</label></span></td>
                                            <td><input autofocus class="input-xxlarge" type="text" id="name"  name="name"placeholder="Enter Name..:" value="<?php echo $cat->name;  ?>" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Category Description:</label></span></td>
                                            <td>
                                                <textarea  class="input-xxlarge" id="about" name="about" value="" placeholder="Enter Description..:" required><?php echo $cat->about ?></textarea>
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