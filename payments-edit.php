<?php
    require_once'includes/initialize.php'; 
    $payment = Payment::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT))); 
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 
        
        $payment->amount = $amtpaid;
        $payment->amountdue = $amtdue;
        $payment->receipt = $receipt;
        $payment->remarks = $remarks;
        $payment->createdby = $session->id;
        $payment->poption = $poption;
        $payment->package = $package;
        $payment->paid = $pdate;
        if($payment && $payment->save()){
            $session->message("Payment completed successfully");
            redirect_to("payments-details.php?id=".base64_encode($payment->id));
        }else {
            $message = "An error occured";
        }
    }
    require_once 'layouts/header.php';
     require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Subscription Payments</h3>    
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="payments-add.php"><h4>New Payment Record</h4></a></li>        
        <li><a href="payments.php"><h4>All Payments</h4></a></li>
        <li><a href="payments-search.php"><h4>Search Payments</h4></a></li>
    </ul><br /><p>&nbsp;</p>
     <div id="container2">         
        <form method='post' action="">  
            <table class="form_table">            
        <tr>
            <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Amount Paid GHC &cent;:</label></span></td>
            <td><input value="<?php echo $payment->amountdue ?>" class="input-xxlarge" placeholder="Enter Amount Paid..." type="number" id="amtdue" oninput="" name="amtpaid"   required /></td>
               <td rowspan="5"></td>
        </tr>
           <tr>
         <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Payment Method:</label></span></td>
            <td>
               <select id="poption"  name="poption" class="input-xxlarge" >
                         <option value="">Please Select</option>
                         <option value="cash" <?php if($payment->poption == "cash"){ echo "selected"; } ?>>Cash</option>
                         <option value="mobile money" <?php if($payment->poption == "mobile money"){ echo "selected"; } ?>>Mobile Money</option>
                         <option value="cheque" <?php if($payment->poption == "cheque"){ echo "selected"; } ?>>Cheque</option>
                    </select>
            </td>
        </tr>
    <tr>
        <td><span class="help-inline"><label class="control-label" for="search">Payment Date:</label></span></td>
        <td><input class="input-xxlarge" required="" type="date" id="pdate" oninput="" name="pdate" value="<?php  echo $payment->paid ?>" placeholder="Enter Payment Date..:" /></td>
    </tr>           

    <tr>
        <td><span class="help-inline"><label class="control-label" for="search">Remarks:</label></span></td>
        <td>
        <textarea class="input-xxlarge" id="remarks" name="remarks" placeholder="Enter Remarks..:" required><?php  echo $payment->remarks ?></textarea>
    </td>
    </tr>                                    
      <tr>
        <td>&nbsp;</td>
        <td><input class="btn btn-warning" type="submit" onclick="return confirm('Confirm Payment?')"  id="sub" name="submit" value="SAVE" /> 
            &nbsp;&nbsp;&nbsp; <a href="payments-details.php?id=<?php echo base64_encode($payment->id) ?>" class="btn btn-danger">CANCEL</a>  </td>
    </tr>
    <tr><td colspan="2"><p>&nbsp;</p></td></tr>
    </table>
        </form> 
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