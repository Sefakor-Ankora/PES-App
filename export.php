<?php
    require_once 'includes/initialize.php';
        extract(filter_input_array(INPUT_GET));        

       // echo "Encoded = ". $qu. "<br />Decoded = ";
                $qu = str_replace(" ", "+", $qu);
            //    echo $qu;
            //    echo "<br />base64_decode result = ". base64_decode($qu); exit;
        switch ($dtype) {
    case "sales":
        $sql = base64_decode($qu);
        $sales = SaleReport::find_by_sql($sql);
        $data = "";
        $data .= "<table>
                                        <thead>
                                            <tr>
                                                <th>DATE</th>
                                                <th>CUSTOMER CARE PERSONNEL</th>
                                                <th>CODE</th>
                                                <th>RECEIPT NO.</th>
                                                <th>BRANCH</th>
                                                <th>CUSTOMER TYPE</th>
                                                <th>CUSTOMER</th>
                                                <th>CUSTOMER ROOM NO</th>
                                                <th>TRANSACTION DETAILS</th>
                                                <th>SALE TYPE</th>
                                                <th>DESTINATION</th>
                                                <th>MODE OF PAYMENT</th>
                                                <th>DISCOUNT</th>
                                                <th>DISCOUNT COMMENTS</th>
                                                <th>VAT / NHIL </th>
                                                <th>SALE AMOUNT </th>
                                                <th>GRAND TOTAL &cent;</th>
                                                <th>USD RATE</th>
                                                <th>GRAND TOTAL USD</th>
                                                <th>WHOLE DAY W/ FUEL</th>
                                                <th>WHOLE DAY W/ FUEL GH(&cent;)</th>
                                                <th>STATUS</th>
                                                <th>CLOSED DATE</th>
                                                <th>CLOSED BY</th>
                                                <th>DRIVER</th>
                                                <th>VEHICLE MAKE</th>
                                                <th>VEHICLE NAME </th>
                                                <th>VEHICLE NO</th>
                                                <th>VEHICLE CATEGORY</th>
                                                <th>OR BEFORE</th>
                                                <th>OR AFTER</th>
                                                <th>DISTANCE</th>
                                                <th>2ND DRIVER</th>
                                                <th>2ND VEHICLE MAKE</th>
                                                <th>2ND VEHICLE NAME</th>
                                                <th>2ND VEHICLE NO</th>
                                                <th>2ND VEHICLE CATEGORY</th>
                                                <th>2ND OR BEFORE</th>
                                                <th>2ND OR AFTER</th>
                                                <th>2ND DISTANCE</th>
                                                <th>DELETE COMMENTS</th>
                                            </tr>
                                        </thead>
                                        <tbody> ";                                          
                                             $total = 0; foreach($sales as $sale): 
                                           $data .= "<tr>
                                                <td>".  $sale->saledate. "</td>
                                                <td>{$sale->creatorname}</td>
                                                <td>". ucfirst($sale->code) ."</td>
<td>{$sale->receiptno}</td>
                                                <td>{$sale->branchname}</td>
                                                <td>{$sale->ctype}</td>
                                                <td>{$sale->customer}</td>
                                                <td>{$sale->roomno}</td>
                                                <td>{$sale->details}</td>
                                                <td>"; if ( (int) $sale->saletype ==0){
                                                                              $data .=     ucfirst($sale->saletype);
                                                                            } else {
                                                                              $data .=   ucfirst($sale->servicename);
                                                                            }
                                                $data .= "</td>
                                                <td>";       
                                                                        if ( (int) $sale->locationid ==0){
                                                                $data .= ucfirst($sale->locationid);
                                                                            } else {
                                                                                $data .= ucfirst($sale->locname);
                                                                            } 
                                                $data .="</td>
                                                <td>  ". ucfirst($sale->mop) ."</td>  
                                                <td>";
                                                                        if ($sale->discount == 1){
                                                                       $data .= number_format($sale->damt,2,'.','');
                                                                            } else {
                                                                                 $data .= "N/A";
                                                                            } 
                                                $data .="</td>
                                                <td>";
                                                                        if ($sale->discount == 1){
                                                                       $data .=  $sale->dcomments;
                                                                            } else {
                                                                                 $data .= "N/A";
                                                                            } 
                                                $data .="</td>
                                                <td>";    
                                                                        if ($sale->tax == 1){
                                                                   $data .= number_format($sale->vat,2,'.','');
                                                                            } else {
                                                                                 $data .= "N/A";
                                                                            }                                                                            
                                                $data .= "</td>
                                                <td>". number_format($sale->amt,2,'.','') ."</td>
                                                 <td>";
                                                                        if ($sale->discount == 1){
                                                            $data .= number_format(($sale->salestotal - $sale->damt),2,'.','');
                                                            $num = number_format(($sale->salestotal - $sale->damt),2,'.','');
                                                                            } else {
                                                                                  $data .= number_format($sale->salestotal,2,'.','');
                                                                                  $num = number_format($sale->salestotal,2,'.','');
                                                                            } 
                                                    $data .= "</td>                                                                                                      
                                                <td>"; if ($sale->rate !=0 ){  $data .= number_format($sale->rate,2,'.','') ; } else { $data .= "N/A";}$data.=" </td>
                                                 <td>";   
                                                if ($sale->rate != 0){
                                                                             $data .= number_format($num  / $sale->rate,2,'.','');
                                                                        } else {
                                                                            $data.= "N/A";
                                                                        }
                                                $data .="</td>
                                                    <td>";
                                                     if ($sale->wd == 1) { $data .="YES";} else { $data .="NO";} $data .="</td>
                                                <td>"; if ($sale->wd == 1) { $data .= number_format($sale->wdfuel ,2,'.',''); } else {  $data .="NA"; }
                                                    $data .="</td>
                                                    <td>". ucfirst($sale->status) ."</td>
                                                 <td>";
                                                                        if ( (int)$sale->closeddate != 0){
                                                                        $data .=      $sale->closeddate;
                                                                            } else {
                                                                        $data .=          "N/A";
                                                                                }
                                                $data .="</td>      
                                                 <td>";     
                                                                        if ( (int)$sale->closeddate != 0){
                                                                   $data .=           $sale->closername;
                                                                            } else {
                                                                   $data .=               "N/A";
                                                                                }
                                                $data .="</td>      
                                                <td>{$sale->drivername}</td>                                                                
                                                <td>".ucfirst($sale->carmake)."</td>                                                                
                                                <td>". ucfirst($sale->carname)."</td>                                                                
                                                <td>".    ucfirst($sale->carno)."</td>                                                                
                                                <td>".    ucfirst($sale->catname)."</td>                                                                  
                                                <td>".    ucfirst($sale->orbefore)."</td>                                                                  
                                                <td>".    ucfirst($sale->orafter)."</td>                                                                  
                                                <td>"; if ($sale->orbefore != "" && $sale->orafter != ""){  $data .= $sale->orafter - $sale->orbefore;  } else { $data .=  "N/A"; } $data .= "</td>                                                                  
                                                <td>";   if ($sale->st ==2):  $data .= ucfirst($sale->drivername1); else: $data .= "N/A";  endif;   $data .= "</td>                                                                
                                                <td>";   if ($sale->st ==2): $data .= ucfirst($sale->carmake1); else: $data .= "N/A";  endif;   $data .= "</td>                                                                
                                                <td>";  if ($sale->st ==2):  $data .= ucfirst($sale->carname1); else: $data .= "N/A";  endif;   $data .= "</td>                                                                
                                                <td>";  if ($sale->st ==2):   $data .= ucfirst($sale->carno1); else: $data .= "N/A";  endif;    $data .= "</td>                                                                
                                                <td>";  if ($sale->st ==2):  $data .= ucfirst($sale->catname1); else: $data .= "N/A";  endif;   $data .= "</td>                                                                  
                                                <td>";   if ($sale->st ==2): $data .= ucfirst($sale->orbefore1); else: $data .= "N/A";  endif;   $data .= "</td>                                                                  
                                                <td>";  if ($sale->st ==2): $data .= ucfirst($sale->orafter1); else: $data .= "N/A";  endif;    $data .= "</td>                                                                  
                                                <td>";  if ($sale->orbefore1 != "" && $sale->orafter1 != ""){  $data .= $sale->orafter1 - $sale->orbefore1;  } else { $data .= "N/A"; }  $data .= "</td>                                                                  
                                                    <td>".  ucfirst($sale->comments)."</td>
                                            </tr>";                                          
                                            $total  += $num;  
                                            endforeach; 
                                                        $data .= "<tr>"
                                                     . "<td colspan='16'><b>TOTAL AMOUNT GH &cent; </b></td>"
                                                     . "<td><b>" . number_format($total,2,'.','') . "</b></td>"
                                                     . "<td colspan='24'>&nbsp;</td>"
                                                             . "</tr>";
                                            
                                    $data .="</tbody>
                                    </table>";
                                    $filename ='SALES_REPORT-'.date('d_M_Y').'.xls';
         $lines = str_getcsv($data);
         $f = fopen($filename,'w+' );
         fputcsv($f, $lines);
         //fputcsv($f, str_getcsv($html));
         $out = $data;
        header("Content-Disposition: attachment; filename=".$filename);
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
         readfile("./".$filename);
         unlink($filename);
     
         break;

    case "fuel":
         $sql = base64_decode($qu);
        $records = FuelReport::find_by_sql($sql);
        $data = "";
        $data .= "<table>
                                    <thead>
                                        <tr>
                                         <th>DATE</th>
                                         <th>USER</th>
                                         <th>VEH. NAME</th>
                                         <th>VEHI REG.</th>
                                         <th>FUEL TYPE</th>
                                         <th>UNIT PRICE</th>
                                         <th>SOURCE</th>
                                         <th>VEH CARD NO.</th>
                                         <th>PREVIOUS BAL.</th>
                                         <th>AMT. USED</th>
                                         <th>QTY (L)</th>
                                         <th>NEW BALANCE (GH ¢)</th>
                                         <th>ODOMETER BEFORE</th>
                                         <th>ODOMETER AFTER</th>
                                         <th>ODOMETER DIFF</th>                                         
                                        </tr>
                                    </thead>
                                    <tbody>";
                                            $total = 0;
                                                    foreach ($records as $record){
                                       $data .= " <tr>
                                            <td>".  $record->fdate."</td>
                                           <td>{$record->creator}</td>                                            
                                            <td>{$record->carname}</td>                                            
                                            <td>{$record->carno}</td>
                                            <td>". strtoupper($record->ftype) . "</td>
                                            <td>".number_format($record->amtused / $record->qty,2,'.','') ."</td>
                                            <td>". strtoupper($record->source) . "</td>
                                            <td>{$record->cardid}</td>
                                            <td>".number_format($record->pbal,2,'.','') ."</td>
                                            <td>".number_format($record->amtused,2,'.','')."</td>
                                            <td>{$record->qty}</td>
                                            <td>".number_format($record->bal,2,'.','') ."</td> 
                                            <td>{$record->kmbefore}</td>
                                            <td>{$record->kmafter}</td>       
                                            <td>" . ($record->kmafter - $record->kmbefore) . "</td>
                                        </tr>";
                                         $total += $record->amtused;
                                                } 
                                                   $data .= "<tr>"
                                                     . "<td colspan='9'><b>TOTAL AMOUNT GH &cent; </b></td>"
                                                     . "<td><b>" . number_format($total,2,'.','') . "</b></td>"
                                                     . "<td colspan='5'>&nbsp;</td>"
                                                             . "</tr>
                                    </tbody>
                                </table>";
                                    $filename ='FUEL_REPORT-'.date('d_M_Y').'.xls';
         $lines = str_getcsv($data);
         $f = fopen($filename,'w+' );
         fputcsv($f, $lines);
         $out = $data;
        header("Content-Disposition: attachment; filename=".$filename);
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
         readfile("./".$filename);
         unlink($filename);
     
        break;
        
    case "maintenanceonly":
         $sql = base64_decode($qu);
        $records = Maintenance::find_by_sql($sql);
        $data = "";
        $data .= "
         <table>
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>TYPE</th>
                                            <th>VEHICLE REG. NO</th>
                                            <th>VEHICLE NAME</th>
                                            <th>VEHICLE MAKE</th>
                                            <th>GARAGE</th>
                                            <th>COST</th>                                            
                                            <th>KM BEFORE</th>
                                            <th>KM AFTER</th>                                            
                                            <th>VEHICLE DRIVER</th>
                                            <th>RECORD CREATOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                            $total = 0;
                                                    foreach ($records as $record){
                                                           $car = Car::find_by_id($record->car);
                                                        $garage = Garage::find_by_id($record->garage);
                                                        $driver = Driver::find_by_id($record->driver);
                                                        $user = User::find_by_id($record->user);
                                       $data .= " <tr>
                                            <td>".$record->rdate."</td>
                                            <td>{$record->type}</td>
                                            <td>{$car->no}</td>                                            
                                            <td>{$car->name}</td>
                                            <td>{$record->make}</td>
                                            <td>{$garage->name}</td>
                                            <td>". number_format($record->cost,2,'.','') ."</td>                                                                                        
                                            <td>{$record->orbefore}</td>                                            
                                            <td>{$record->orafter}</td>                 
                                            <td>{$driver->firstname}   {$driver->lastname}</td>                            
                                            <td>{$user->firstname}   {$user->lastname}</td>                                                                        
                                        </tr>";
                                         $total += $record->cost;
                                                } 
                                                   $data .= "<tr>"
                                                     . "<td colspan='6'><b>TOTAL AMOUNT GH &cent; </b></td>"
                                                     . "<td><b>" . number_format($total,2,'.','') . "</b></td>"
                                                     . "<td colspan='4'>&nbsp;</td>"
                                                             . "</tr>
                                    </tbody>
                                </table>";
                                    $filename ='MAINTENANCE_REPORT_ONLY-'.date('d_M_Y').'.xls';
         $lines = str_getcsv($data);
         $f = fopen($filename,'w+' );
         fputcsv($f, $lines);
         $out = $data;
        header("Content-Disposition: attachment; filename=".$filename);
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
         readfile("./".$filename);
         unlink($filename);
     
        break;
    case "maintenance":
         $sql = base64_decode($qu);
        $records = Maintenancereport::find_by_sql($sql);
        $data = "";
        $data .= "
         <table>
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>TYPE</th>
                                            <th>VEHICLE REG. NO</th>
                                            <th>VEHICLE NAME</th>
                                            <th>VEHICLE MAKE</th>
                                            <th>GARAGE</th>
                                            <th>ITEM</th>
                                            <th>ITEM DESCRIPTION</th>
                                            <th>COST</th>                                            
                                            <th>KM BEFORE</th>
                                            <th>KM AFTER</th>                                            
                                            <th>VEHICLE DRIVER</th>
                                            <th>RECORD CREATOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                            $total = 0;
                                                    foreach ($records as $record){
                                       $data .= " <tr>
                                            <td>".  $record->rdate."</td>
                                            <td>{$record->type}</td>
                                            <td>{$record->carno}</td>                                            
                                            <td>{$record->carname}</td>
                                            <td>{$record->carmake}</td>
                                            <td>{$record->garagename}</td>
                                            <td>{$record->mitemname}</td>
                                            <td>{$record->descript}</td>
                                            <td>". number_format($record->cost,2,'.','') ."</td>                                                                                        
                                            <td>{$record->orbefore}</td>                                            
                                            <td>{$record->orafter}</td>                 
                                            <td>{$record->drivername}</td>                            
                                            <td>{$record->creator}</td>                            
                                        </tr>";
                                         $total += $record->cost;
                                                } 
                                                   $data .= "<tr>"
                                                     . "<td colspan='8'><b>TOTAL AMOUNT GH &cent; </b></td>"
                                                     . "<td><b>" . number_format($total,2,'.','') . "</b></td>"
                                                     . "<td colspan='4'>&nbsp;</td>"
                                                             . "</tr>
                                    </tbody>
                                </table>";
                                    $filename ='MAINTENANCE_REPORT-'.date('d_M_Y').'.xls';
         $lines = str_getcsv($data);
         $f = fopen($filename,'w+' );
         fputcsv($f, $lines);
         $out = $data;
        header("Content-Disposition: attachment; filename=".$filename);
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
         readfile("./".$filename);
         unlink($filename);
     
        break;
        
    default:
        break;
}
