<?php
    require_once'includes/initialize.php'; 
    global $database;
    if(filter_input(INPUT_POST, "submit")){
        $cat = new Category();
        $cat->name = trim(filter_input(INPUT_POST, "name", FILTER_DEFAULT));
        if (Category::find_by_name($cat->name)){
            $session->message("Category \"{$cat->name}\" already exists!");
            redirect_to("settings-categories-add.php");
        }           
        $cat->about = trim(filter_input(INPUT_POST, "about", FILTER_DEFAULT));
        $cat->created = strftime("%Y-%m-%d %H:%M:%S", time());
        $cat->createdby = $session->id;
        if($cat && $cat->save()){
            $session->message("Category {$cat->name} saved successfully");
            redirect_to("settings-categories-add.php");
        }else {
            $message = "An error occured";
        }
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
                                            <td><input autofocus class="input-xxlarge" type="text" id="name" oninput="" name="name" value="" placeholder="Enter Name..:" required validation/></td>
                                        </tr>
                                        <tr>
                                            <td  <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">About:</label></span></td>
                                            <td>
                                                <textarea class="input-xxlarge" id="about" name="about" value="" placeholder="Enter Description..:" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="reset" name="" value="CLEAR" class="btn btn-danger" /></td>
                                        </tr>
                                        </table>
                                </form>
    </div>
</div>
<?php require_once './layouts/footer.php'; 