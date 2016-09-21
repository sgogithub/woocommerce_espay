<?php 
require_once('../../../wp-config.php');
//require_once('index.php');
global $wpdb;

$prefix = $wpdb->prefix;

$wo = new WC_Gateway_eSpay;
$passwordAdmin = $wo->password();
$creditcardfee = $wo->creditcardfee();
$signatureKey = $wo->sigKey();

$order_id = (!empty($_REQUEST['order_id'])?$_REQUEST['order_id']:'');
$passwordServer = (!empty($_REQUEST['password'])?$_REQUEST['password']:'');

$signaturePostman = (!empty($_REQUEST['signature'])?$_REQUEST['signature']:'');
$rq_datetime = (!empty($_REQUEST['rq_datetime'])?$_REQUEST['rq_datetime']:'');

$key = '##'.$signatureKey.'##'.$rq_datetime.'##'.$order_id.'##'.'INQUIRY'.'##';
//$key = '##7BC074F97C3131D2E290A4707A54A623##2016-07-25 11:05:49##145000065##INQUIRY##';
$uppercase = strtoupper($key);
$signatureKeyRest = hash('sha256', $uppercase);

$meta_key = '_order_total';
$meta_key_curr = '_order_currency';

$sql = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_currency')
";
$results = $wpdb->get_results( $sql); 

$sql1 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_total')
";
$results1 = $wpdb->get_results( $sql1); 

$sql2 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_productcode_espay')
";
$results2 = $wpdb->get_results( $sql2); 

$sql3 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_creditcardfee_espay')
";
$results3 = $wpdb->get_results( $sql3); 

$sql4 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_fee_espay')
";
$results4 = $wpdb->get_results( $sql4); 

$sql5 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_order_total_ori')
";
$results5 = $wpdb->get_results( $sql5); 

//{$prefix}postmeta.meta_key = '".$meta_key."'
//{$prefix}postmeta.meta_key in('_order_currency','_order_total','_order_productcode_espay','_order_creditcardfee_espay','_order_fee_espay','_order_total_ori')

//echo'<pre>';
//var_dump($results5);
//echo'</pre>';

//die;
if($passwordAdmin != $passwordServer){
	$flagStatus = '1;Invalid Password;;;;;';
	echo $flagStatus;
}
else{
	if($signatureKeyRest == $signaturePostman){
		if(count($results) < 1){
			$flagStatus = '1;Invalid Order Id;;;;;';
			echo $flagStatus;
		}
		else{
		//	echo date("d M Y H:i:s");die;
			   $order_id_ori =  $results[0]->order_id;
			   $post_status = $results[0]->post_status;
			   $ccy = $results[0]->meta_value;
			   
			   $amount = $results1[0]->meta_value;
			   $post_date = $results[0]->post_date;
			   $time = substr($post_date,10,10);
			   $productCode = $results2[0]->meta_value;
			   $feeDb = $results4[0]->meta_value;
			   $creditcardfeeDb = $results3[0]->meta_value;
			   $amountOri = $results5[0]->meta_value;
			   
			   $post_date_format = date("d/m/Y",strtotime($post_date));
			   $datetimeformat = $post_date_format.''.$time;
			   
	//		   die;
			   if($order_id_ori && $post_status == 'wc-completed'){
			   	 $flagStatus = '1;Failed;;;;;';
			   }
			   elseif($order_id_ori && $post_status == 'wc-processing'){
			   	 $flagStatus = '1;Failed;;;;;';
			   }
			   elseif($order_id_ori && $post_status == 'wc-cancelled'){
			   	 $flagStatus = '1;Failed;;;;;';
			   } 
			   elseif($order_id_ori && $post_status == 'trash'){
			   	 $flagStatus = '1;Failed;;;;;';
			   }
			   else{
			   	if($productCode == 'CREDITCARD'){
	//		   		$amountcredit = $amount + $feeDb;
	//	            $amountFinish =  ($amountcredit * $creditcardfeeDb)/100;
	//		        $totalamount = $amount + $amountFinish; //disc rate
	//		   		$meta_value = $totalamount;
			   		
					$totalamount = $amountOri +(( ($amountOri + $feeDb) * $creditcardfeeDb)) / 100;
			   		$meta_value = $totalamount;
			   		
			   	}
			   	else{
	//		   	 	$meta_value = $amount;
			   	 	$meta_value = $amountOri;
			   	}
	//		   	ECHO $meta_value;
	//		   	DIE('test');
			   	 $flagStatus = '0;Success;'.$order_id_ori.';'.$meta_value.';'.$ccy.';Payment '.$order_id_ori.';'.$datetimeformat.'';
		
			   	 global $woocommerce;
		         $orderWc = new WC_Order($order_id_ori);
		         $orderWc->add_order_note( __( 'Menunggu pembayaran melalui melalui ESPay dengan order id '.$order_id_ori, 'woocommerce' ) );
		//         $orderWc->payment_complete();
		//            $woocommerce->cart = new My_WC_Cart();
		//            $woocommerce->cart->empty_cart( false );
		
			   }
			   echo $flagStatus;
		//	   return $flagStatus;
		}
	}
	else{
		$flagStatus = '1;Invalid Signature Key;;;;;';
		echo $flagStatus;
	}
}
?>
