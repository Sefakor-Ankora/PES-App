<?php
require_once'includes/initialize.php'; 
require_once 'layouts/header.php';
require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Users</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="settings-users-add.php"><h4>Add User</h4></a></li>
        <li><a href="settings-users.php"><h4>All Users</h4></a></li>
    </ul><br />
    <div id="container2">
    	<table class="data_pes">
    		<thead>
    			<th>NAME</th>
    			<th>ROLE</th>
    			<th>LAST LOGIN</th>
                <th>LOGIN IP</th>
    			<th>ACTIONS</th>
    		</thead>
    		<tbody>
                <?php $users = User::find_all(); if ($users){ foreach($users as $user): ?>
    			<tr>
    				<td><?php echo ucfirst($user->name) ?></td>
    				<td><?php echo ucfirst($user->role_name()) ?></td>
    				<td><?php if ((int)$user->login !=0){ echo datetime_to_text1($user->login); } else { echo "N/A";} ?></td>
    				<td><?php echo $user->ip ?></td>
                    <td>
                        <a  href="settings-users-edit.php?&id=<?php echo base64_encode($user->id) ?>">EDIT</a>
                        <a class="alert-danger" onclick="return confirm('Are you sure?')" href="delete.php?type=user&id=<?php echo base64_encode($user->id) ?>">DELETE</a>
                    </td>
    			</tr>
            <?php endforeach; } else {  ?>
                <tr>
                    <td colspan="5" style="text-align: center;"><div class='alert-info'>No Users to Display
                        <a href="settings-users-add.php">Add User</a></div></td>
                </tr>
            <?php } ?>
    		</tbody>
    	</table>
	</div>
</div>
<?php require_once 'layouts/footer.php';