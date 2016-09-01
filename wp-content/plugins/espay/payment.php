<?php 
require_once('../../../wp-config.php');
ini_set( 'display_errors', false );
error_reporting( 0 );
global $wpdb, $woocommerce;

$wo = new WC_Gateway_eSpay;
$passwordAdmin = $wo->password();
$signatureKey = $wo->sigKey();

$order_id = (!empty($_REQUEST['order_id'])?$_REQUEST['order_id']:'');
$passwordServer = (!empty($_REQUEST['password'])?$_REQUEST['password']:'');
$amt = (!empty($_REQUEST['amount'])?$_REQUEST['amount']:'0');

$product_code = (!empty($_REQUEST['product_code'])?$_REQUEST['product_code']:'0');
$payment_ref = (!empty($_REQUEST['payment_ref'])?$_REQUEST['payment_ref']:'0');

$signaturePostman = (!empty($_REQUEST['signature'])?$_REQUEST['signature']:'');
$rq_datetime = (!empty($_REQUEST['rq_datetime'])?$_REQUEST['rq_datetime']:'');

$key = '##'.$signatureKey.'##'.$rq_datetime.'##'.$order_id.'##'.'PAYMENTREPORT'.'##';
//$key = '##7BC074F97C3131D2E290A4707A54A623##2016-07-25 11:05:49##145000065##INQUIRY##';
$uppercase = strtoupper($key);
$signatureKeyRest = hash('sha256', $uppercase);

$meta_key = '_order_total';
$meta_key_curr = '_order_currency';

$sql = "SELECT wp_woocommerce_order_items.order_id, wp_posts.ID, wp_posts.post_status, wp_posts.post_date, wp_posts.post_password, wp_postmeta.post_id, wp_postmeta.meta_key, wp_postmeta.meta_value
FROM wp_woocommerce_order_items
JOIN wp_posts ON wp_woocommerce_order_items.order_id=wp_posts.ID
JOIN wp_postmeta ON wp_woocommerce_order_items.order_id=wp_postmeta.post_id
where 
wp_woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
wp_postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
wp_postmeta.meta_key in('_payment_method_title')
";
$results = $wpdb->get_results( $sql); 

$sql1 = "SELECT wp_woocommerce_order_items.order_id, wp_posts.ID, wp_posts.post_status, wp_posts.post_date, wp_posts.post_password, wp_postmeta.post_id, wp_postmeta.meta_key, wp_postmeta.meta_value
FROM wp_woocommerce_order_items
JOIN wp_posts ON wp_woocommerce_order_items.order_id=wp_posts.ID
JOIN wp_postmeta ON wp_woocommerce_order_items.order_id=wp_postmeta.post_id
where 
wp_woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
wp_postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
wp_postmeta.meta_key in('_order_total')
";
$results1 = $wpdb->get_results( $sql1); 

//and
//wp_postmeta.meta_value = '".$amt."'
//wp_postmeta.meta_key = '".$meta_key."'
//wp_postmeta.meta_key in('_payment_method_title','_order_total')

//echo'<pre>';
//var_dump($results);
//echo'</pre>';
//
//DIE;
if($passwordAdmin != $passwordServer){
	$flagStatus = '1;Invalid Password;;;;;';
	echo $flagStatus;
}
else{
	if($signatureKeyRest == $signaturePostman){
		if(count($results) < 1){
			$flagStatus = '1,Invalid Order Id,,,,';
			echo $flagStatus;
		}
		else{
			   $order_id_ori =  $results[0]->order_id;
			   $post_status = $results[0]->post_status;
			   $paymentMethod = $results[0]->meta_value;
			   $paymentMethodReplace = str_replace('Waiting for Payment', 'Payment is Successful', $paymentMethod);
			   $post_date = $results[0]->post_date;
			   $reconcile_id = $results[0]->post_password;
			   
			   $amount = $results1[0]->meta_value;
			   
			   if($order_id_ori && $post_status == 'wc-completed'){
			   	 $flagStatus = '1,Failed,,,,';
			   }
			   elseif($order_id_ori && $post_status == 'wc-processing'){
			   	 $flagStatus = '1,Failed,,,,';
			   }
			   elseif($order_id_ori && $post_status == 'wc-cancelled'){
			   	 $flagStatus = '1,Failed,,,,';
			   } 
			   elseif($order_id_ori && $post_status == 'trash'){
			   	 $flagStatus = '1,Failed,,,,';
			   }
			   else{
			   	 $meta_value = $amount;
			   	 $flagStatus = '0,Success,'.$reconcile_id.','.$order_id_ori.','.$post_date.'';
		         $orderWc = new WC_Order($order_id_ori);
		         $orderWc->add_order_note( __( 'Pembayaran telah dilakukan melalui ESPay dengan product '.$product_code.' dan referensi pembayaran '.$payment_ref, 'woocommerce' ) );
		         $orderWc->payment_complete();
		         
	//	         $payment_method_title = 'ESPay Payment Gateways - '.$product_code; 
	//	         $wpdb->query($wpdb->prepare("UPDATE wp_postmeta SET meta_value='".$payment_method_title."' WHERE post_id='".$order_id_ori."' and meta_key='_payment_method_title'"));
			   	 $wpdb->query($wpdb->prepare("update wp_postmeta set meta_value = replace(meta_value,'".$paymentMethod."','".$paymentMethodReplace."') where post_id = '".$order_id_ori."' and meta_key='_payment_method_title'"));
			   	 $wpdb->flush();
			   	 
	//			 $orderWc->update_status('completed', __( 'Order Completed', 'woocommerce' ));
			   }
			   echo $flagStatus;
		}
	}
	else{
		$flagStatus = '1;Invalid Signature Key;;;;;';
		echo $flagStatus;
	}
}
?>