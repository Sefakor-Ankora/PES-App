<?php
    require_once'includes/initialize.php'; 
    $sub = Subscription::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));
    require_once 'layouts/header.php';
    require_once 'layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Subscription Record Details</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="subscriptions-add.php"><h4>Add New Subscription</h4></a></li>
        <li><a href="subscriptions-search.php"><h4>Search Subscriptions</h4></a></li>
        <li><a href="subscriptions.php"><h4>Subscription Report</h4></a></li>      
    </ul><br />
    <div id="container2">        
            <table class="form_table">
                <tr>                                
                    <td style="width: 20%"><b>Date Created</b>
                    <td style="width: 30%"><?php echo datetime_to_text1($sub->created)  ?></td>
                    <td colspan="2"><b>&nbsp;</b></td>
                </tr>  
                <tr><td colspan="4"><hr /><h4>Customer Information</h4></td></tr>                
                 <tr>                                
                    <td><b>Customer Name</b></td>
                    <td><?php $customer = Customer::find_by_id($sub->customer);
                    echo $customer->name;  ?></td>
                    <td style="width: 20%"><b>Customer Email</b></td>         
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
                     <td><?php $package = Package::find_by_id($sub->package);
                     $cat = Category::find_by_id($package->category); echo $cat->name  ?></td>                             
                    <td><b>Subscription Package</b></td>         
                    <td><?php echo $package->name  ?></td>         
                </tr>  
                  <tr>                                
                    <td><b>Decoder</b></td>
                    <td><?php echo $sub->decoder  ?></td>    
                    <td><b>Smartcard</b></td>         
                    <td><?php echo $sub->smartcard  ?></td>    
                </tr>                
                <tr><td colspan="4"><hr /><h4>Installation Information</h4></td></tr>                
                 <tr>                                
                    <td><b>Installation Date</b></td>
                    <td><?php echo datetime_to_text($sub->idate);  ?></td>    
                    <td><b>Technician Name</b></td>         
                    <td><?php echo $sub->technician;  ?></td>    
                </tr>  
                  <tr>                                
                    <td><b>Technician Phone</b></td>
                    <td><?php echo $sub->tphone  ?></td>    
                    <td colspan="2"><b></b></td>         
                </tr>                        
                <tr><td colspan="4"><hr /><h4>Payment Information</h4></td></tr> 
                  <tr>                                
                      <td><b>Install Amount GH &cent;</b></td>
                      <td><?php echo $sub->iamount ?></td>           
                      <td><b>Administrative Overhead GH &cent; </b></td>
                      <td><?php echo $sub->overhead ?></td>           
                </tr>      
                 <tr>                                
                      <td><b>Package Amount GH &cent; </b></td>
                      <td><?php echo $sub->amt;  ?></td>           
                      <td><b>Total Amount GH &cent; </b></td>
                      <td><?php echo $sub->amountdue;  ?></td> 
                </tr>     
               <tr> 
                 <td><b>Cost of Hardware Required GH &cent; </b></td>
                      <td><?php echo $sub->hardware;  ?></td> 
                    <td><b>Next Payment Due on/before </b></td>
                      <td><?php echo datetime_to_text($sub->nextdate) ?></td>
                </tr>
                  <tr>                                
                    <td colspan="4"><p>&nbsp;</p></td>   
                </tr>  
                  <tr>
                    <td>&nbsp;</td>
                    <td><a href="subscriptions-edit.php?id=<?php echo base64_encode($sub->id) ?>" class="btn btn-warning">EDIT</a> </td>
                    <td><a href="delete.php?type=subscription&id=<?php echo base64_encode($sub->id) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?\nThis deletes customer info too.')">DELETE</a></td>
                   <td>&nbsp;</td>
                </tr>
                <tr>                                
                    <td colspan="4"><p>&nbsp;</p></td>   
                </tr>  
            </table>
                <h3 style="text-align: center; ">Payment History</h3>        
     <hr/>
        <table class="form_table">
            <thead>
                <th>PAYMENT DATE</th>
                <th>AMOUNT PAID GH &cent;</th>
                <th>REMARKS</th>                
                <th>PROCESSED BY</th>
                <th></th>
            </thead>
        <?php $payments = Payment::find_by_subscription($sub->id); if($payments){foreach ($payments as $payment){ ?>
            <tbody>
                <tr>
                    <td><?php echo datetime_to_text2($payment->paid); ?></td>                
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
                    <td colspan="6" style="text-align: center;"><div class='alert-info'>No Records to Display</div></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
          <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
    </div>
</div>
<script type="text/javascript">
     function load_packages(cat){
        document.getElementById('packages').innerHTML = "Please Wait, Processing...";
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
            document.getElementById('packages').innerHTML = mlhttp.responseText;
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
            mlhttp.open("GET","as/search.php?type=loadpackages&value="+ cat,true);
            mlhttp.send();  

    }
function available_bal(cat){
        if(window.XMLHttpRequest) {
                    mlhttp=new XMLHttpRequest();
                    }
                    else  {
                    mlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    mlhttp.onreadystatechange=function(){
            if (mlhttp.readyState===4 && mlhttp.status===200){
                document.getElementById('amtdue').value = mlhttp.responseText;     
                }
            };
            mlhttp.open("GET","as/search.php?type=getamount&value="+ cat,true);
            mlhttp.send();  

}
  function get_stock(cat){
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
            available_bal(cat);
            var myScripts = editdiv.getElementsByTagName("script");
                            if (myScripts.length > 0) {
                            eval(myScripts[0].innerHTML);
                            }
                }
            };
            mlhttp.open("GET","as/search.php?type=getstock&value="+ cat,true);
            mlhttp.send();  

    }
    
   function paiddd(ad){       
       if(parseInt(ad) === 1){ // yes, so remove disabled 
            document.getElementById('poption').disabled=false;
            document.getElementById('pdate').disabled=false;   
        } else {
            document.getElementById('poption').disabled=true;
            document.getElementById('pdate').disabled=true;           
        }
    }
</script>                        
<?php require_once './layouts/footer.php'; 