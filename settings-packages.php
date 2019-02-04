<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Packages</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-packages-add.php"><h4>Add Package</h4></a></li>
        <li><a href="settings-packages.php"><h4>All Packages</h4></a></li>
    </ul><br />
    <div id="container2">
    	<table class="data_pes">
    		<thead>
    			<th>PACKAGE</th>
    			<th>CATEGORY</th>
    			<th>ABOUT</th>
                <th>UNIT PRICE GH &cent;</th>
    			<th>ACTIONS</th>
    		</thead>
    		<tbody>
            <?php $packages = Package::find_all(); if ($packages){ foreach($packages as $package): ?>
    			<tr>
                    <td><?php echo ucwords($package->name) ?></td>
                    <td><?php echo Category::get_name($package->category); ?></td>
                    <td><?php echo $package->about ?></td>
                    <td class="money"><b><?php echo $package->amount ?></b></td>
                    <td>
                        <a href="settings-packages-edit.php?&id=<?php echo base64_encode($package->id) ?>">EDIT</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="alert-danger" onclick="return confirm('Are you sure?')" href="delete.php?type=package&id=<?php echo base64_encode($package->id) ?>">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; } else {  ?>
                <tr>
                    <td colspan="5" style="text-align: center;"><div class='alert-info'>No Packages to Display
                        <a href="settings-packages-add.php">Add Package</a></div></td>
                </tr>
                <?php } ?>
    		</tbody>
    	</table>
	</div>
</div>
<?php require_once 'layouts/footer.php';