<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;


	/**
	*Find a needle in  a haystack
	*
	*/
	function multi_in_array($needle, $haystack, $key) {
	    foreach ($haystack as $h) {
	        if (array_key_exists($key, $h) && $h[$key]==$needle) {
	            return true;
	        }
	    }
	    return false;
	}
	function datediffInWeeks($date1, $date2){
    	$first = DateTime::createFromFormat('m/d/Y', $date1);
    	$second = DateTime::createFromFormat('m/d/Y', $date2);
    	if($date1 > $date2) return datediffInWeeks($date2, $date1);
    	return floor($first->diff($second)->days/7);
	}

	if($sourcename != "" && $password != "" && $siteID != ""  && $fromdate != "" && $todate != "" && $_GET ["settings-updated"] == 'true'){
		// initialize default credentials
		$creds = new SourceCredentials($sourcename, $password, array($siteID));
		//START Packagaes and services
		$salesService = new MBSaleService();
		$salesService->SetDefaultCredentials($creds);
		/*
			Into the darkness we delve!!!
			There are at LEAST two types of product from mind body Packages/Services...
			so we are gonna get em all MUAHAHAHAHAHAHA
		*/ 
		$resultgetServices = $salesService->GetServices(array(), array(), array(), null, null, true, null, $creds, XMLDetail::Full, 100);
		$resultgetPackages = $salesService->GetPackages();
		//ok now some manipulationfirst we drill down into it tto get the arrays we need then we append a prodtype to each sop we know for later what's what
		$drilldownresultgetServices = $resultgetServices->GetServicesResult->Services->Service;
		foreach ($drilldownresultgetServices as $service) { $service->prodtype = 'Service'; }
		$drilldownresultgetPackages = $resultgetPackages->GetPackagesResult->Packages->Package;
		foreach ($drilldownresultgetPackages as $package) { $package->prodtype = 'Package'; }
		//and now we merge em!
		$merged = array_merge ($drilldownresultgetServices, $drilldownresultgetPackages);
		//OK here goes into the darkness we delve! 
		//I'm sure this could be achived another way but this is the way i know..
		foreach ($merged as $key => $value) {
				/*	
					this is a hack and makes shit really slow...
					what this does is grab's the products allready inputed for 
					each iteration of this call to mindbody...
				*/ 
				//first check does the Product allready exist...
				$title = $value->Name;
				$id_ofpost_title = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '$title'");
				$checktypeofreturn = gettype($id_ofpost_title);
				//ok first let's check the type we have.. 
				switch ($value->prodtype) {
					case 'Service':
						if($checktypeofreturn == 'string') {
								//since it exists we are going to just update it with our new values...
								//here it comes all the custom product meta!
								//STAFF
								update_post_meta( $id_ofpost_title, '_regular_price', $value->Price);
								update_post_meta( $id_ofpost_title, '_price', $value->Price);
								update_post_meta( $id_ofpost_title, '_sku', $value->ProductID);
								update_post_meta( $id_ofpost_title, 'wpcf-enrollment-course', 'no');
								update_post_meta( $id_ofpost_title, 'wpcf-program-scheduletype', ' ');
								update_post_meta( $id_ofpost_title, '_virtual', 'yes');
								wp_update_post( array('ID' =>  $id_ofpost_title, 'post_excerpt' => $value->Name, 'post_content' => $value->Name ) );
								wp_set_object_terms( $id_ofpost_title, 'Service', 'product_cat' );
								wp_set_object_terms( $id_ofpost_title, ' Lessons and Events', 'product_cat', true );

								if (strpos($value->Name,'Private') !== false) {
									wp_set_object_terms( $id_ofpost_title, 'Private Lessons', 'product_cat', true);
								}elseif (strpos($value->Name,'Group') !== false) {
									# code...
									wp_set_object_terms( $id_ofpost_title, 'Group Classes', 'product_cat', true );
								}elseif (strpos($value->Name,'Membership') !== false) {
									# code...
									wp_set_object_terms( $id_ofpost_title, 'Membership', 'product_cat', true );
								}								
								//and here it ends...
						}elseif($checktypeofreturn == 'NULL'){
								if($value->imageurl != NULL){
									$imageurlarray = array(array('src' => $value->imageurl, 'position' => '10')); 
								}else{
									$imageurlarray = NULL; 
								}
								$data = array(
									'title' => $value->Name,
									'status' => 'publish',
									'sku' => $value->ProductID,
									'regular_price' => $value->Price,
									'visibility' => 'visible',
									'description' => $value->Name,
									'post_excerpt' => $value->Name,
									'images' => $imageurlarray
								);
								wp_set_object_terms( $prodid, 'Service', 'product_cat' );
								wp_set_object_terms( $prodid, ' Lessons and Events', 'product_cat', true );

								if (strpos($value->Name,'Private') !== false) {
									wp_set_object_terms( $id_ofpost_title, 'Private Lessons', 'product_cat', true );
								}elseif (strpos($value->Name,'Group') !== false) {
									# code...
									wp_set_object_terms( $id_ofpost_title, 'Group Classes', 'product_cat', true  );
								}elseif (strpos($value->Name,'Membership') !== false) {
									# code...
									wp_set_object_terms( $id_ofpost_title, 'Membership', 'product_cat', true  );
								}
								$prodid = $WCproducts->add_product($data);
								wp_update_post( array('ID' =>  $prodid, 'post_excerpt' => $value->Description ) );
								update_post_meta( $prodid, 'wpcf-enrollment-course', 'no');
								update_post_meta( $prodid, 'wpcf-program-scheduletype', ' ');
								update_post_meta( $prodid, '_virtual', 'yes');
						}
					break;
					case 'Package':
						if($checktypeofreturn == 'string') {
								//since it exists we are going to just update it with our new values...
								//here it comes all the custom product meta!
								//STAFF
								update_post_meta( $id_ofpost_title, '_sku', $value->ID);
								wp_set_object_terms ($id_ofpost_title, 'grouped', 'product_type');
								update_post_meta( $id_ofpost_title, 'wpcf-enrollment-course', 'no');
								update_post_meta( $id_ofpost_title, 'wpcf-program-scheduletype', ' ');
								wp_update_post( array('ID' =>  $id_ofpost_title, 'post_excerpt' => $value->Name, 'post_content' => $value->Name ) );
								wp_set_object_terms( $id_ofpost_title, 'Package', 'product_cat' );
								wp_set_object_terms( $id_ofpost_title, ' Lessons and Events', 'product_cat', true );
								//and here it ends...
						}elseif($checktypeofreturn == 'NULL'){						
								$imageurlarray = NULL; 
								$data = array(
									'title' => $value->Name,
									'status' => 'publish',
									'sku' => $value->ID,
									'visibility' => 'visible',
									'description' => $value->Name,
									'post_excerpt' => $value->Name,
									'images' => $imageurlarray
								);
								
								$prodid = $WCproducts->add_product($data);
								wp_set_object_terms( $prodid, 'Package', 'product_cat' );
								wp_set_object_terms( $prodid, ' Lessons and Events', 'product_cat', true );
								wp_set_object_terms ($prodid, 'grouped', 'product_type');
								wp_update_post( array('ID' =>  $prodid, 'post_excerpt' => $value->Description ) );
								update_post_meta( $prodid, 'wpcf-enrollment-course', 'no');
								update_post_meta( $prodid, 'wpcf-program-scheduletype', ' ');
						}
					break;
				}
		}
	}
?>
	<h2>Your courses options</h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="postbox-container-1" class="postbox-container">				
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3><span>Shortcodes</span></h3>
						<div class="inside">
							<p>You can display courses on page using a shortcode:</p>
							<code>[mindbodyclasses level="Absolute Beginner"]</code>
							<p>Or a full calendar like so:</p>
							<code>[mindbodyeventscal]</code>
						</div>
					</div>
				</div>				
			</div>			
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span>Import from Mind body</span></h3>
						<div class="inside">
							<p>Pick Courses  date range to import:</p>
							<p>this will overdie any setting you may have in the current courses...</p>
							<?php $this->options = get_option( 'mind_body_options_import' ); ?>
							<form method="post" action="options.php">
								<?php
									// This prints out all hidden setting fields
									settings_fields( 'mind_body_group_import' );   
									do_settings_sections( 'mind-body-admin_import' );
									submit_button( 'Import/Update', 'primary', 'submit', true, '' );
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
	
		</div> 
		<br class="clear">
	</div> <!-- #poststuff -->