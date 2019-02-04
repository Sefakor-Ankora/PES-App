<?php
    require_once 'includes/initialize.php';
    $id = filter_input(INPUT_GET, "id", FILTER_DEFAULT);
    $type = filter_input(INPUT_GET, "type", FILTER_DEFAULT);
    
    switch ($type) {
        case "user":
            $user  = User::find_by_id(base64_decode($id));
            if($user && $user->delete()){
                $session->message("User info removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("settings-users.php");
            break;

        case "stock":
            $stock  = Stock::find_by_id(base64_decode($id));
            if($stock && $stock->delete()){
                $session->message("Stock info removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("settings-stock.php");
            break;

        case "package":
            $package  = Package::find_by_id(base64_decode($id));
            if($package && $package->delete()){
                $session->message("Package removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("settings-packages.php");        
            break;
            
        case "category":
                $category  = Category::find_by_id(base64_decode($id));
            if($category && $category->delete()){
                $session->message("Category removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("settings-categories.php");        
            break;

        case "sale":
            $stockhh  = Stockhistory::find_by_id(base64_decode($id));
            
            if($stockhh && $stockhh->delete()){
                $stockk = Stock::find_by_id($stockhh->stock);
                $stockh = new Stockhistory();
                $stockh->stock = $stockk->id;
                $stockh->type = "add";
                $stockh->prev = $stockk->qty;
                $stockh->qty = $stockhh->qty;       
                $stockh->new = $stockk->qty + $stockhh->qty;                
                
                $stockh->remarks = "Deleted Sale";
                $stockh->created = strftime("%Y-%m-%d %H:%M:%S", time());
                $stockh->createdby = $session->id;  
                $stockk->qty = $stockh->new;
                $stockh->save();
                $stockk->save();
            
                $session->message("Sale removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("sales-add.php");        
            break;        

        case "subscription":
            $sub  = Subscription::find_by_id(base64_decode($id));
            if($sub && $sub->delete()){
                //remove customer information
                $customer = Customer::find_by_id($sub->customer);
                $customer->delete();
                //delete and reverse stock inputs
                $stockhs  = Stockhistory::find_for_subscription($sub->id);
                if($stockhs && $stockhs->delete()){
                    foreach ($stockhs as $stockhh):
                        if($stockhh{
                            //update stock data
                            $stockk = Stock::find_by_id($stockhh->stock);
                            $stockh = new Stockhistory();
                            $stockh->stock = $stockk->id;
                            $stockh->type = "add";
                            $stockh->prev = $stockk->qty;
                            $stockh->qty = $stockhh->qty;       
                            $stockh->new = $stockk->qty + $stockhh->qty;                

                            $stockh->remarks = "Deleted Subscription";
                            $stockh->created = strftime("%Y-%m-%d %H:%M:%S", time());
                            $stockh->createdby = $session->id;  
                            $stockk->qty = $stockh->new;
                            $stockh->save();
                            $stockk->save();
                        }
                    endforeach;    
                }        
                //delete payment record
                $payment = Payment::find_by_sub($sub->id);
                $payment->delete();

                $session->message("Subscription removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("subscriptions-add.php");  
            break;

        case "payment":
            $payment  = Payment::find_by_id(base64_decode($id));
            if($payment && $payment->delete()){
                $session->message("Payment record removed successfully.");
            }else {
                $session->message("An error occured.");
            }
            redirect_to("subscriptions-details.php?id=". base64_encode($payment->sub));        
            break;

    default:
        break;
}
