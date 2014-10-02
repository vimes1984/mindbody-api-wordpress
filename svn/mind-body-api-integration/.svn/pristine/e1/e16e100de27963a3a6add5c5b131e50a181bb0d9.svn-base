<?php
/**
 * the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruemarketing.com
 * @copyright 4-29-2014 Accrue
 */

  	$plugins_url = realpath(__DIR__ . '/../..');

  	Global $wpdb,$woocommerce, $pagenow;
  	require_once($plugins_url .'/woocommerce/includes/api/class-wc-api-server.php');
/*
  The rest of these require_once's are taken from the woocommerce plugin!
*/
  	require_once($plugins_url .'/woocommerce/includes/api/interface-wc-api-handler.php');
  	require_once($plugins_url .'/woocommerce/includes/api/class-wc-api-json-handler.php');
  	require_once($plugins_url .'/woocommerce/includes/api/class-wc-api-resource.php');
  	require_once(realpath(__DIR__ . '/..').'/assets/class-wc-api-products.php');
	require_once(plugin_dir_path(__FILE__) . "../includes/classService.php");
	require_once(plugin_dir_path(__FILE__) . "../includes/saleService.php");

/*
 	Get plugins options!
*/

	$getadminfunction = new MindBody();
 	
 	//plugin options basic
	$no_exists_value = get_option( 'mind_body_options' );
	$sourcename = $no_exists_value['sourcename'];
	$password = $no_exists_value['password'];
	$siteID = $no_exists_value['siteID'];
	//plugin dependancies options.
	$hideornot = get_option( 'mind_body_options_plugins' );
	$gethideornot = $hideornot["hidenotice"];
	//get class dates for imports
	$getdates = get_option( 'mind_body_options_import' );
	$fromdate = $getdates["importfromdate"];
	$todate = $getdates["importtodate"];
	$showresults = false;
/*
	End dates
*/

  	$WCAPIServer = new  WC_API_Server('/');

  	$WCproducts = new WC_API_Products($WCAPIServer);


  	$getProducts = $WCproducts->get_products(null, null, array(), 1);
echo "<pre>";
 // 	var_dump($WCproducts);
echo "</pre>";
?>

<div class="wrap">
<?php if(isset($no_exists_value['sourcename']) && $no_exists_value['sourcename'] != null && isset($no_exists_value['password']) && $no_exists_value['password'] != null && isset($no_exists_value['siteID']) && $no_exists_value['siteID'] != null){ ?>
	<div ng-app="ngappadmin">
		<div ng-controller="ngappcontroller">

			<?php 
				$chknotice = $getadminfunction->checkwoocommerce();
				if ($gethideornot != 1) {
			    	if ($chknotice == false) { ?>
						<div class="error">
					    	<form method="post" action="options.php">
						    	<h1>Plugins</h1>
						    	<p><?php _e( 'This Plugin is deisgned to work with the following:', 'mindbody-plugin' ); ?></p>
						        <h3><?php _e( 'Woocommerce', 'mindbody-plugin' ); ?> </h3>
						        <a class="button-secondary" href="http://www.woothemes.com/woocommerce/" target="_blank"><?php _e( 'Read More', 'mindbody-plugin' ); ?></a>
						        <h3><?php _e( 'Woocommerce Bookings', 'mindbody-plugin' ); ?></h3>
						        <a class="button-secondary" href="http://www.woothemes.com/products/woocommerce-bookings/" target="_blank"><?php _e( 'Read More', 'mindbody-plugin' ); ?></a>
								<?php $this->options = get_option( 'mind_body_options_plugins' ); ?>
						        <?php 
									settings_fields( 'mind_body_group_plugins' );   
									do_settings_sections( 'mind-body-admin_plugins' );
									submit_button( 'HIDE', 'primary', 'submit', true, 'data-ng-click="testclick()"' );

								?>
					    	</form>
					    </div>
			    <?php 
			    	}//end plugin activate check 
				}//end hideornot check
			    ?>


			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div class="wrap">

				
				<?php


					if ( isset ( $_GET['tab'] ) ) $getadminfunction->mindbody_admin_tabs($_GET['tab']); else $getadminfunction->mindbody_admin_tabs('settings');
				?>

				<div id="poststuff">
						<?php
						wp_nonce_field( "ilc-settings-page" ); 
						
						if ( $pagenow == 'admin.php' && $_GET['page'] == 'mind-body' ){ 
						
							if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
							else $tab = 'settings';
							switch ( $tab ){
								case 'staff' :
									include_once(dirname(__FILE__). '/adminview/staff.php');
								break; 
								case 'classes' : 
									include_once(dirname(__FILE__). '/adminview/classes.php');
								break;
								case 'courses' : 
									include_once(dirname(__FILE__). '/adminview/courses.php');
								break;
								case 'products' : 
									include_once(dirname(__FILE__). '/adminview/products.php');
								break;
								case 'settings' : 
									include_once(dirname(__FILE__). '/adminview/options.php');
								break;
							}
						}
						?>
					<p>Plugin built by  <a href="http://accruemarketing.com/">Accrue</a> | programed by <a href="http://twitter.com/vimes1984">BAWD</a></p>
				</div>
			</div>
		</div>
	</div>
<?php }else{ ?>
	<?php echo $getadminfunction->checkwoocommerce(); ?>
	<h3>Please set these setting prior to using this plugin</h3>
	<form method="post" action="options.php">
		<?php
			// This prints out all hidden setting fields
			settings_fields( 'mind_body_group' );   
			do_settings_sections( 'mind-body-admin' );
			submit_button(); 
		?>
	</form> 
	<p>More info can be found here: <a href="https://api.mindbodyonline.com/Home/LogIn">https://api.mindbodyonline.com/Home/LogIn</a></p>
	<p>Plugin built by  <a href="http://accruemarketing.com/">Accrue</a> | programed by <a href="http://twitter.com/vimes1984">BAWD</a></p>

</div>
<?php } ?>
