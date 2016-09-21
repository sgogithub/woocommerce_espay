<?php 
require_once('../../../../wp-config.php');

global $wpdb;
$prefix = $wpdb->prefix;

$wo = new WC_Gateway_eSpay;
$passwordAdmin = $wo->password();

$order_id = (!empty($_REQUEST['order'])?$_REQUEST['order']:'');
//$passwordServer = (!empty($_REQUEST['password'])?$_REQUEST['password']:'');
//$amt = (!empty($_REQUEST['amount'])?$_REQUEST['amount']:'0');

//$product_code = (!empty($_REQUEST['product_code'])?$_REQUEST['product_code']:'0');
//$payment_ref = (!empty($_REQUEST['payment_ref'])?$_REQUEST['payment_ref']:'0');

$meta_key = '_order_total';
$meta_key_curr = '_order_currency';

$sql = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}posts.post_password, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
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

$sql1 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}posts.post_password, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
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

$sql2 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}posts.post_password, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_billing_first_name')
";
$results2 = $wpdb->get_results( $sql2); 

$sql3 = "SELECT {$prefix}woocommerce_order_items.order_id, {$prefix}posts.ID, {$prefix}posts.post_status, {$prefix}posts.post_date, {$prefix}posts.post_password, {$prefix}postmeta.post_id, {$prefix}postmeta.meta_key, {$prefix}postmeta.meta_value
FROM {$prefix}woocommerce_order_items
JOIN {$prefix}posts ON {$prefix}woocommerce_order_items.order_id={$prefix}posts.ID
JOIN {$prefix}postmeta ON {$prefix}woocommerce_order_items.order_id={$prefix}postmeta.post_id
where 
{$prefix}woocommerce_order_items.order_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.post_id = '".mysql_real_escape_string($order_id)."' 
and 
{$prefix}postmeta.meta_key in('_payment_method_title')
";
$results3 = $wpdb->get_results( $sql3); 

//and
//{$prefix}postmeta.meta_value = '".$amt."'
//{$prefix}postmeta.meta_key = '".$meta_key."'
//{$prefix}postmeta.meta_key in('_order_currency','_order_total')
//{$prefix}postmeta.meta_key in('_order_currency','_order_total','_billing_first_name','_payment_method_title')


//echo'<pre>';
//var_dump($results);
//echo'</pre>';

$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
$url = $shop_page_url;//get_site_url();

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( $myaccount_page_id ) {
 	$myaccount_page_url = get_permalink( $myaccount_page_id );
}

		   $order_id_ori =  $results[0]->order_id;
		   $post_status = $results[0]->post_status;
		   $post_date = $results[0]->post_date;
		   $reconcile_id = $results[0]->post_password;
		   $ccy_ori = $results[0]->meta_value;
		   if($ccy_ori = 'IDR'){
		   		$ccy = 'Rp';
		   }
		   else{
		   		$ccy = $results[0]->meta_value;
		   }
		   
		   $fullname = $results2[0]->meta_value;
		   $paymentmethod = $results3[0]->meta_value;
		   $amount = $results1[0]->meta_value;
?>
<?php if($order_id_ori && $post_status == 'wc-completed'){}
elseif($order_id_ori && $post_status == 'wc-processing'){
}
elseif($order_id_ori && $post_status == 'wc-cancelled'){
}
else{
?>
					<?=get_header();?>
					<style>
					#outPopUp {
					 position: fixed;
					  padding:20px;
					  width: 300px;
					  height: auto;
					  z-index: 1500;
					  top: 20%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: white;
					  text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4><img src="success.png">  Hi...... </b> </H4> </B><hr>
								  Order kamu telah kami terima! Kami harap kamu dapat menggunakan produk yang dipesan secepatnya dengan 
								  melakukan pembayaran via <u><?=$paymentmethod;?></u>.
								  <br><br>
								  <a href='<?=$myaccount_page_url?>' title='selengkapnya'>Nomor Order/Id # Anda</a> : <font color="red"><?=$_REQUEST['order']?> </font><br>
								  <br><br>
								  <form action="<?=$url?>" method="post">
								  <input type='submit' value="Continue Shopping">
								  </form>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
<?php }?>					