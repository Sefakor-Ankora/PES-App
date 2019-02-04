<?php
    require_once'includes/initialize.php'; 
    $stockh = Stockhistory::find_by_id(base64_decode(filter_input(INPUT_GET, "id", FILTER_DEFAULT)));    
    if(filter_input(INPUT_POST, "submit")){
        extract(filter_input_array(INPUT_POST)); 
        $stockh->amount_paid = $amount_paid;
        $stockh->poption = $poption;
        if($stockh && $stockh->save()){
            $session->message("Sale updated successfully");
            redirect_to("sales-details.php?id=".base64_encode($stockh->id));
        }else {
            $message = "An error occured";
        }
    }
    require_once 'layouts/header.php';
    require_once './layouts/sidepane.php'; ?>
<div class="container1">
    <h3 style="text-align: center; ">Sales - Update Credit Sale</h3>        
     <hr/>
    <?php  if(isset($message)){ echo "<h4 class='alert-info'>".$message."</h4>" ;}  ?>
    <ul class="navsmall">  
        <li><a href="sales-add.php"><h4>New Sale</h4></a></li>
        <li><a href="sales-today.php"><h4>Today's Sales</h4></a></li>
        <li><a href="sales-my.php"><h4>Search Sales</h4></a></li>
    </ul><br />
    <div id="container2">
        <form method='post' action="">  

            <table class="form_table">
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
            <tr><td colspan="2"><p>&nbsp;</p></td></tr>
                <tr>
                    <td><span class="help-inline"><label class="control-label" for="search">Amount Paid (GH&cent;):</label></span></td>
                    <td><input class="input-xxlarge" type="number" id="amount_paid"  name="amount_paid" value="<?php echo $stockh->amount_paid  ?>" placeholder="Enter Amount Paid..:"  /></td>
                </tr>              
                <tr>
                 <td style="vertical-align: top; "><span class="help-inline"><label class="control-label" for="search">Payment method:</label></span></td>
                    <td>
                        <select  name="poption" class="input-xxlarge" required >
                            <option value="">Please Select</option>
                            <option  value="cash">Cash</option>
                            <option value="mobile money">Mobile Money</option>     
                            <option value="cheque">Cheque</option>                  
                        </select>
                    </td>
                </tr>      
                            <tr><td colspan="2"><p>&nbsp;</p></td></tr>
                                      <tr>
                    <td>&nbsp;</td>
                    <td><input class="btn btn-warning" type="submit"  id="sub" name="submit" value="SAVE" />  &nbsp;&nbsp;&nbsp;  <input type="reset" name="" value="CLEAR" class="btn btn-danger" /></td>
                </tr>
            </table>

            <div id="details"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
     function get_details(item){
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
                    mlhttp.open("GET","as/search.php?type=getdetails&value="+ item,true);
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