<?php
    require_once'includes/initialize.php';
        $stockh = Stockhistory::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));
  
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Sales details</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="sales-add.php"><h4>New Sale</h4></a></li>
        <li><a href="sales-today.php"><h4>Today's Sales</h4></a></li>
        <li><a href="sales-my.php"><h4>Search Sales</h4></a></li>
    </ul><br />
    <div id="container2">
     <table class="form_table" style="width: 40%">
            <tr>
                <td><span class="help-inline"><label class="control-label" for="search">Date/Time:</label></span></td>
                <td><?php  echo datetime_to_text1($stockh->created);  ?></td>
            </tr>
            <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Created By:</label></span></td>
                <td><?php $user = User::find_by_id($stockh->createdby); if ($user){ echo "<b>".$user->name ."</b>";}  ?></td>
            </tr>
            <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Item:</label></span></td>
                <td><?php $stock = Stock::find_by_id($stockh->stock); if ($stock){ echo $stock->name;} ?></td>              
            </tr>
                        <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Unit Price GH (&cent;):</label></span></td>
                <td><?php echo $stockh->price ?></td>              
            </tr>
              <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Quantity:</label></span></td>
                <td><?php echo $stockh->qty ?></td>              
            </tr>
                          <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Total Amount GH (&cent;):</label></span></td>
                <td><?php echo $stockh->total_amount ?></td>              
            </tr>
                          <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Amount Paid GH (&cent;):</label></span></td>
                <td><?php echo $stockh->amount_paid ?></td>              
            </tr>
                       <tr>
                 <td><span class="help-inline"><label class="control-label" for="search">Payment Option:</label></span></td>
                <td><?php echo ucfirst($stockh->poption) ?></td>              
            </tr>            
            <tr><td colspan="2"><p>&nbsp;</p><p>&nbsp;</p></td></tr>
                     <tr>
                 <td></td>
                <td>  
     <?php if($stockh->poption == "credit") { ?>
                    <a class="btn btn-warning" href="sales-edit.php?&id=<?php echo base64_encode($stockh->id) ?>">UPDATE SALE</a>
                    &nbsp; &nbsp;
              <?php  } ?>
                    <a class="btn btn-danger" onclick="return confirm('Are you sure? removing this sale will update the stock item quantity')" href="delete.php?&id=<?php echo base64_encode($stockh->id) ?>&type=sale">REMOVE SALE</a>
                </td>
            </tr>
            </table>
        <hr />
    </div>
</div>                     
<?php require_once './layouts/footer.php'; 