<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Settings: Categories</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-categories-add.php"><h4>Add Category</h4></a></li>
        <li><a href="settings-categories.php"><h4>All Categories</h4></a></li>
    </ul><br />
    <div id="container2">
    	<table class="data_pes">
    		<thead>
    			<th>NAME</th>
    			<th>ABOUT</th>
    			<th>NO. OF PACKAGES</th>
    			<th>ACTIONS</th>
    		</thead>
    		<tbody>
            <?php $cats = Category::find_all(); if ($cats){ foreach($cats as $cat): ?>
    			<tr>
    				<td><?php echo ucwords($cat->name) ?></td>
    				<td><?php echo $cat->about ?></td>
    				<td><?php echo Package::count_cat($cat->id); ?></td>
                    <td>
                        <a href="settings-categories-edit.php?&id=<?php echo base64_encode($cat->id) ?>">EDIT</a>
                        <a class='alert-danger' onclick="return confirm('Are you sure?')" href="delete.php?type=category&id=<?php echo base64_encode($cat->id) ?>">DELETE</a>
                    </td>
    			</tr>
                <?php endforeach; } else {  ?>
                <tr>
                    <td colspan="4" style="text-align: center;"><div class='alert-info'>No Categories to Display
                        <a href="settings-categories-add.php">Add Category</a></div></td>
                </tr>
                 <?php } ?>
    		</tbody>
    	</table>
	</div>
</div>
<?php require_once 'layouts/footer.php';