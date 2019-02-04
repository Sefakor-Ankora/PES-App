<?php
    require_once'includes/initialize.php';
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Payments</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="payments-add.php"><h4>New Payment Record</h4></a></li>        
        <li><a href="payments.php"><h4>All Payments</h4></a></li>
        <li><a href="payments-search.php"><h4>Search Payments</h4></a></li>
    </ul><br /><p>&nbsp;</p>
    <div id="container2">
        <form action="" method="POST" >
           <table class="data_pes" style="width: 40%">
            <thead style="position: relative;">
                <th>SEARCH BY</th>
                <th></th>                
                <th></th>
            </thead>
    		<tbody>              
                <tr>
                    <td> <select  name="crit" class="input-large">                                          
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "name"){ echo "selected"; } ?> value="name">Customer Name</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "phone"){ echo "selected"; } ?> value="phone">Customer Phone</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "email"){ echo "selected"; } ?> value="email">Customer Email</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "location"){ echo "selected"; } ?> value="location">Customer Location</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "smartcard"){ echo "selected"; } ?> value="smartcard">Smartcard</option>                        
                        <option <?php if (isset($_POST['crit']) && $_POST['crit'] == "decoder"){ echo "selected"; } ?> value="decoder">Decoder</option>
                    </select></td>
                    <td> 
                        <input class="input-xlarge" type="text" oninput="" name="value" value="<?php if (isset($_POST['value'])){ echo $_POST['value'];}  ?>" 
                               placeholder="Enter Search Value..:" required validation/>
                    </td>
                    <td>
                        <input type="submit" name="search" value="SEARCH" class="btn btn-info" />
                    </td>
                </tr>
    		</tbody>
    	</table>
        </form>
             <hr />        
            <?php 
                if(filter_input(INPUT_POST, "search")){
                    extract(filter_input_array(INPUT_POST)); 
                    $options = array('name','phone','email','location');
                    $options2 = array('smartcard','decoder');
                    $payments=null;
                    
                    if(in_array($crit, $options)){
                        $sql = "SELECT * FROM tbl_customer WHERE deleted = 0 AND {$crit} LIKE '%{$value}%' ";                        
                        $custs = Customer::find_by_sql($sql);
                        $ids;
                        if($custs){ foreach($custs as $cust):
                            $ids[] = $cust->id;
                        endforeach;
                        $idds = implode(",", $ids);
                        $sql = "SELECT * FROM tbl_subscription WHERE deleted = 0 AND customer IN ({$idds})  ";
                        $subs = Subscription::find_by_sql($sql);
                        $idss;
                            if($subs){ foreach($subs as $sub):
                                $idss[] = $sub->id;
                            endforeach;
                            $idsss = implode(",", $idss);
                            $sql = "SELECT * FROM tbl_payment WHERE deleted = 0 AND sub IN ({$idsss})  ";
                            $payments = Payment::find_by_sql($sql);                            
                            } 
                        }                        
                    } else {
                        $sql = "SELECT * FROM tbl_subscription WHERE deleted = 0 AND {$crit} LIKE '%{$value}%' ";
                        $subs = Subscription::find_by_sql($sql);
                        $idss;
                            if($subs){ foreach($subs as $sub):
                                $idss[] = $sub->id;
                            endforeach;
                            $idsss = implode(",", $idss);
                            $sql = "SELECT * FROM tbl_payment WHERE deleted = 0 AND sub IN ({$idsss})  ";
                            $payments = Payment::find_by_sql($sql);                            
                            } 
                    } ?>
                     <h4 style="text-align: center; ">Results</h4> 
       <table class="data_pes">
    		<thead>
    			<th>PAYMENT DATE</th>
    			<th>CUSTOMER</th>                        
                        <th>AMOUNT PAID GH &cent;</th>
                        <th>REMARKS</th>    			
                        <th>PROCESSED BY</th>
    			<th></th>
    		</thead>
        <?php if($payments){foreach ($payments as $payment){ ?>
    		<tbody>
    			<tr>
                            <td><?php echo datetime_to_text2($payment->paid); ?></td>
                            <td><?php $sub = Subscription::find_by_id($payment->sub); $customer = Customer::find_by_id($sub->customer); echo ucwords($customer->name) ?></td>                    
                    <td><?php echo $payment->amount ?></td>
                    <td><?php echo $payment->remarks ?></td>
                    <td><?php $u = User::find_by_id($payment->createdby); echo ucwords($u->name). " on ". datetime_to_text1($payment->created); ?></td>
                    <td>
                        <a class="alert-success" href="payments-details.php?&id=<?php echo base64_encode($payment->id) ?>">DETAILS</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                        
                        <a class="alert-warning" href="payments-edit.php?&id=<?php echo base64_encode($payment->id) ?>">EDIT</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class='alert-danger' onclick="return confirm('Are you sure?')" href="delete.php?type=payment&id=<?php echo base64_encode($payment->id) ?>">DELETE</a>
                    </td>
    			</tr>
            <?php }} else { ?>
                <tr>
                    <td colspan="7" style="text-align: center;"><div class='alert-info'>No Subscriptions to Display</div></td>
                </tr>
            <?php } ?>
    		</tbody>
    	</table>
                <?php }
            ?>
	</div>
</div>
<script type="text/javascript">
     function loadform(item){
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
                    mlhttp.open("GET","as/search.php?type=paymentform&value="+ item,true);
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