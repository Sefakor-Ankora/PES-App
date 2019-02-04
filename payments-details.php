<?php
    require_once'includes/initialize.php';
    $payment = Payment::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT))); 
    $sub= Subscription::find_by_id($payment->sub);
    $customer = Customer::find_by_id($sub->customer);
    $package = Package::find_by_id($sub->package);
    $cat = Category::find_by_id($package->category);
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
    </ul><br />
    <div id="container2">
         <table class="form_table">
              <tr><td colspan="4"><hr /><h4>Payment Information</h4></td></tr>                
                 <tr>                                
                     <td><b>Payment Date</b></td>
                     <td><?php echo datetime_to_text($payment->paid)  ?></td>
                     <td><b>Payment Amount GH &cent;</b></td>
                    <td><?php echo $payment->amount  ?></td>        
                </tr>  
                 <tr>                                
                     <td><b>Payment Option</b></td>
                     <td><?php echo ucfirst($payment->poption)  ?></td>                      
                     <td><b>Receipt No.</b></td>
                     <td><?php echo $payment->receipt  ?></td>         
                </tr>                  
                 <tr>                                            
                    <td><b>Remarks</b></td>         
                    <td><?php echo $payment->remarks  ?></td>   
                    <td colspan="2"></td>
                </tr>                  
                <tr><td colspan="4"><hr /><h4>Customer Information</h4></td></tr>                
                <tr>         
                    <td><b>Customer Name</b></td>
                    <td><?php echo $customer->name;  ?></td>
                    <td><b>Customer Email</b></td>         
                    <td><?php echo $customer->email  ?></td>         
                </tr>  
                  <tr>                                
                    <td><b>Customer Phone</b></td>
                    <td><?php echo $customer->phone  ?></td>         
                    <td><b>Customer Location</b></td>         
                    <td><?php echo $customer->location  ?></td>         
                </tr>
                <tr><td colspan="4"><hr /><h4>Subscription Information</h4></td></tr>                
                 <tr>                                
                     <td><b>Subscription Category</b></td>
                     <td><?php echo $cat->name  ?></td>                             
                    <td><b>Subscription Package</b></td>         
                    <td><?php echo $package->name  ?></td>         
                </tr>  
                  <tr>                                
                    <td><b>Decoder</b></td>
                    <td><?php echo $sub->decoder  ?></td>    
                    <td><b>Smartcard</b></td>         
                    <td><?php echo $sub->smartcard  ?></td>    
                </tr>  
                <tr>                                
                    <td><p>&nbsp;</p></td>   
                </tr> 
                <tr>
                    <td>&nbsp;</td>
                    <td><a href="payments-edit.php?id=<?php echo base64_encode($payment->id) ?>" class="btn btn-warning">EDIT</a> </td>
                    <td><a href="delete.php?type=payment&id=<?php echo base64_encode($payment->id) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">DELETE</a></td>
                   <td>&nbsp;</td>
                </tr>                
            </table>
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