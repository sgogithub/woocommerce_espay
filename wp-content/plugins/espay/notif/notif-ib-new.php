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
{$prefix}woocommerce_order_items.order_id = '".$order_id."' 
and 
{$prefix}postmeta.post_id = '".$order_id."' 
and 
{$prefix}postmeta.meta_key in('_order_currency','_order_total','_billing_first_name','_payment_method_title')
";
//and
//{$prefix}postmeta.meta_value = '".$amt."'
//{$prefix}postmeta.meta_key = '".$meta_key."'
//{$prefix}postmeta.meta_key in('_order_currency','_order_total')

$results = $wpdb->get_results( $sql); 
//echo'<pre>';
//var_dump($results);
//echo'</pre>';

$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
$url = $shop_page_url;//get_site_url();

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( $myaccount_page_id ) {
 	$myaccount_page_url = get_permalink( $myaccount_page_id );
}
//DIE;
	if(count($results) < 1){
		$flagStatus = '1,Invalid Order Id,,,,';
		$flagStatus;
	}
	else{
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
		   
		   $fullname = $results[1]->meta_value;
		   $paymentmethod = $results[2]->meta_value;
		   $amount = $results[3]->meta_value;
		   
		   if($order_id_ori && $post_status == 'wc-completed'){
//		   	 $flagStatus = '1;Failed;;;;';
				?>
					<?=get_header();?>
					<style>
					#outPopUp {
					  position: fixed;
					  width: 300px;
					  height: 200px;
					  z-index: 1500;
					  top: 20%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: none;
					text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4>Selamat! Anda akan menerima pesanan anda dalam beberapa kali pengiriman</H4> </B><hr>
								  Hi <b><?=$fullname?>,</b> <br>
								  Terima Kasih telah belanja di toko kami dengan menggunakan <b><u><?=$paymentmethod?></u></b>.
								  <br><br>
								  <a href='<?=$myaccount_page_url?>' title='selengkapnya'>Kode Pemesanan # Anda</a> : <font color="red"><?=$_REQUEST['order']?> </font><br>
								  Jumlah uang yang ditagihkan : <font color="red"><?=$ccy?>. <?=number_format($amount, 2); ?> </font>
								  <br><br>
<!--								  Pembayaran via <?=$_REQUEST['method']?> sukses! -> -->
								  Kami akan segera memproses pesanan Anda dan mengatur pengiriman pesanan. 
								  <br><br>
								  <form action="<?=$url?>" method="post">
								  <input type='submit' value="Continue Shopping">
								  </form>
								  <br>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
				<?php 
		   }
		   elseif($order_id_ori && $post_status == 'wc-processing'){
//		   	 $flagStatus = '1;Failed;;;;';
				?>
					<?=get_header();?>
					<style>
					#outPopUp {
					  position: fixed;
					  width: 300px;
					  height: 200px;
					  z-index: 1500;
					  top: 20%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: none;
					text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4>Selamat! Anda akan menerima pesanan anda dalam beberapa kali pengiriman</H4> </B><hr>
								  Hi <b><?=$fullname?>,</b> <br>
								  Terima Kasih telah belanja di toko kami dengan menggunakan <b><u><?=$paymentmethod?></u></b>.
								  <br><br>
								  <a href='<?=$myaccount_page_url?>' title='selengkapnya'>Kode Pemesanan # Anda</a> : <font color="red"><?=$_REQUEST['order']?> </font><br>
								  Jumlah uang yang ditagihkan : <font color="red"><?=$ccy?>. <?=number_format($amount, 2); ?> </font>
								  <br><br>
<!--								  Pembayaran via <?=$_REQUEST['method']?> sukses! -> -->
								  Kami akan segera memproses pesanan Anda dan mengatur pengiriman pesanan. 
								  <br><br>
								  <form action="<?=$url?>" method="post">
								  <input type='submit' value="Continue Shopping">
								  </form>
								  <br>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
				<?php 
		   }
		   elseif($order_id_ori && $post_status == 'wc-cancelled'){
//		   	 $flagStatus = '1;Failed;;;;';
				?>
					<?=get_header();?>
					<style>
					#outPopUp {
					  position: fixed;
					  width: 300px;
					  height: 200px;
					  z-index: 1500;
					  top: 40%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: none;
					text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4>AN ERROR OCCURRED IN THE PROCESS OF PAYMENT</H4> </B><hr>
								  <font color="red">Order #<?=$_REQUEST['order']?> </font>.
								  <br>
								  Click <a href='<?=$url?>'>here </a>to continue shopping.
								  <br>
								  <br>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
				<?php 
		   } 
		   elseif($order_id_ori && $post_status == 'trash'){
//		   	 $flagStatus = '1;Failed;;;;';
				?>
					<?=get_header();?>
					<style>
					#outPopUp {
					  position: fixed;
					  width: 300px;
					  height: 200px;
					  z-index: 1500;
					  top: 40%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: none;
					text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4>AN ERROR OCCURRED IN THE PROCESS OF PAYMENT</H4> </B><hr>
								  <font color="red">Order #<?=$_REQUEST['order']?> </font>.
								  <br>
								  Click <a href='<?=$url?>'>here </a>to continue shopping.
								  <br>
								  <br>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
				<?php 
		   }
		   else{
//		   	 $meta_value = $amount;
//		   	 $flagStatus = '0;Success;'.$reconcile_id.';'.$order_id_ori.';'.$post_date.'';
				?>
					<?=get_header();?>
					<style>
					#outPopUp {
					  position: fixed;
					  width: 300px;
					  height: 200px;
					  z-index: 1500;
					  top: 40%;
					  left: 50%;
					  margin: -100px 0 0 -150px;
					  background: none;
					text-align: center;
					}
					</style>
					<div id="outPopUp">
						  <div id="primary" class="site-content" style="align">
							  <div class="entry-content">
								  <B><H4>AN ERROR OCCURRED IN THE PROCESS OF PAYMENT</H4> </B><hr>
								  <font color="red">Order #<?=$_REQUEST['order']?> </font>.
								  <br>
								  Click <a href='<?=$url?>'>here </a>to continue shopping.
								  <br>
								  <br>
							  </div>
						  </div>
					</div>	  
					  
					<?php //  get_sidebar();
					echo get_footer(); 
					?>
				<?php 
		   }
}
?>