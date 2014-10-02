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
		//START Enrollments services
		$classService = new MBClassService();
		$classService->SetDefaultCredentials($creds);
		$StartClassDateTime = new DateTime($fromdate);
		$EndClassDateTime = new DateTime($todate);
		$GetCourses = $classService->GetEnrollments(array(), array(), array(), array(), $StartClassDateTime, $EndClassDateTime);
		$resultget = $GetCourses->GetEnrollmentsResult->Enrollments->ClassSchedule;
		$resultafter = array();
		$completedresults =  array();
		//END Class services
		$i= 0;
		foreach ($resultget as $class) {
			if($class->IsAvailable){
				$resultafter[$i] = new StdClass;
			  	$resultafter[$i] = $class;
				$resultafter[$i]->title = $class->ClassDescription->Name;
				$resultafter[$i]->Level = $class->ClassDescription->Level->Name;
				$resultafter[$i]->Description = $class->ClassDescription->Description;
				$resultafter[$i]->imageurl = $class->ClassDescription->ImageURL;
				$resultafter[$i]->id = $class->ID;
				$classids[$i] = $class->ID;
				$i ++;
			}
		}
		//ok since these come in in acending order we are gonna flip them before we go through them
		$resultsflipped = array_reverse($resultafter); 
			//OK here goes into the darkness we delve! 
			//I'm sure this could be achived another way but this is the way i know..
		$index = 0;
		foreach ($resultsflipped as $key => $value) {
				$StartDate = new DateTime($value->StartDate);
				$stripStartDate = $StartDate->format('l jS \of F Y');
				$EndDate = new DateTime($value->EndDate);
				$stripEndDate = $EndDate->format('l jS \of F Y');
				$courselength = datediffInWeeks( $StartDate->format('m/d/Y'), $EndDate->format('m/d/Y') );
				/*	
					this is a hack and makes shit really slow...
					what this does is grab's the products allready inputed for 
					each iteration of this call to mindbody...
				*/ 
				$title = $value->title;
				$id_ofpost_title = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '$title'");
				$checktypeofreturn = gettype($id_ofpost_title);  
				//first check does the class allready exist...
				if($checktypeofreturn == 'string') {
						//since it exists we are going to just update it with our new values...
						//here it comes all the custom product meta!
						//STAFF
						if($value->DaySunday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Sundays');
						}elseif ($value->DayMonday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Mondays');
						}elseif ($value->DayTuesday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Tuesdays');
						}elseif ($value->DayWednesday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Wednesdays');
						}elseif ($value->DayThursday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Thursdays');
						}elseif ($value->DayFriday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Fridays');
						}elseif ($value->DaySaturday){
							update_post_meta( $id_ofpost_title, 'wpcf-week-day', 'Saturdays');
						}

						update_post_meta( $id_ofpost_title, 'wpcf-email-staff', $value->Staff->Email);
						update_post_meta( $id_ofpost_title, 'wpcf-name-staff', $value->Staff->Name);
						update_post_meta( $id_ofpost_title, 'wpcf-address-staff', $value->Staff->Address);
						update_post_meta( $id_ofpost_title, 'wpcf-city-staff', $value->Staff->City);
						update_post_meta( $id_ofpost_title, 'wpcf-state-staff', $value->Staff->State);
						update_post_meta( $id_ofpost_title, 'wpcf-country-staff', $value->Staff->Country);
						update_post_meta( $id_ofpost_title, 'wpcf-postalcode-staff', $value->Staff->PostalCode);
						update_post_meta( $id_ofpost_title, 'wpcf-mindbody-staff-id', $value->Staff->ID);
						update_post_meta( $id_ofpost_title, 'wpcf-firstname-staff', $value->Staff->FirstName);
						update_post_meta( $id_ofpost_title, 'wpcf-lastname-staff', $value->Staff->LastName);
						update_post_meta( $id_ofpost_title, 'wpcf-bio-staff-html', $value->Staff->Bio);
						update_post_meta( $id_ofpost_title, 'post_excerpt', $value->Description);
						
						//SCHEDULES
						update_post_meta( $id_ofpost_title, 'wpcf-program-id', $value->ClassDescription->Program->ID);
						update_post_meta( $id_ofpost_title, 'wpcf-program-name', $value->ClassDescription->Program->Name);
						update_post_meta( $id_ofpost_title, 'wpcf-program-scheduletype', $value->ClassDescription->Program->ScheduleType);
						update_post_meta( $id_ofpost_title, 'wpcf-starttime', date( "H:i",strtotime( $value->StartTime ) ) );
						update_post_meta( $id_ofpost_title, 'wpcf-endtime', date("H:i",strtotime($value->EndTime)));
						update_post_meta( $id_ofpost_title, 'wpcf-startdate', $stripStartDate);
						update_post_meta( $id_ofpost_title, 'wpcf-enddate', $stripEndDate);
						update_post_meta( $id_ofpost_title, 'wpcf-course-length', $courselength);
						update_post_meta( $id_ofpost_title, 'wpcf-enrollment-course', 'yes');
						// Update the post post_excerpt
						wp_update_post( array('ID' =>  $id_ofpost_title, 'post_excerpt' => $value->Description ) );
						
						wp_set_object_terms( $id_ofpost_title, 'enrollment-courses', 'product_cat' );
						wp_set_object_terms( $id_ofpost_title, ' Lessons and Events', 'product_cat', true );

						//additional class data
						update_post_meta( $id_ofpost_title, 'wpcf-prerequisites-class', $value->ClassDescription->Prereq);

						if($value->Staff->isMale){
							update_post_meta( $h['id'], 'wpcf-ismale', 'true');
						}else{
							update_post_meta( $h['id'], 'wpcf-ismale', 'false');
						}
						if($value->staffMobilePhone !== NULL){	
							update_post_meta( $h['id'], 'wpcf-mobilephone-staff', $value->Staff->MobilePhone);
						}else{
							update_post_meta( $h['id'], 'wpcf-mobilephone-staff', '403-253-6977');
						}
						//and here it ends...
				}elseif($checktypeofreturn == 'NULL'){
						if($value->imageurl != NULL){
							$imageurlarray = array(array('src' => $value->imageurl, 'position' => '10')); 
						}else{
							$imageurlarray = NULL; 
						}
						$data = array(
							'title' => $value->title,
							'type' => 'booking',
							'status' => 'publish',
							'sku' => $value->id,
							'visibility' => 'visible',
							'description' => $value->Description,
							'post_excerpt' => $value->Description,
							'images' => $imageurlarray
						);

						$prodid = $WCproducts->add_product($data);
						wp_update_post( array('ID' =>  $prodid, 'post_excerpt' => $value->Description ) );

						if($value->DaySunday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Sundays');
						}elseif ($value->DayMonday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Mondays');
						}elseif ($value->DayTuesday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Tuesdays');
						}elseif ($value->DayWednesday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Wednesdays');
						}elseif ($value->DayThursday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Thursdays');
						}elseif ($value->DayFriday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Fridays');
						}elseif ($value->DaySaturday){
							update_post_meta( $prodid, 'wpcf-week-day', 'Saturdays');
						}
						update_post_meta( $prodid, 'wpcf-level', $value->Level);
						update_post_meta( $prodid, 'wpcf-level', $value->Level);
						update_post_meta( $prodid, 'wpcf-email-staff', $value->Staff->Email);
						update_post_meta( $prodid, 'wpcf-name-staff', $value->Staff->Name);
						update_post_meta( $prodid, 'wpcf-address-staff', $value->Staff->Address);
						update_post_meta( $prodid, 'wpcf-city-staff', $value->Staff->City);
						update_post_meta( $prodid, 'wpcf-state-staff', $value->Staff->State);
						update_post_meta( $prodid, 'wpcf-country-staff', $value->Staff->Country);
						update_post_meta( $prodid, 'wpcf-postalcode-staff', $value->Staff->PostalCode);
						update_post_meta( $prodid, 'wpcf-mindbody-staff-id', $value->Staff->ID);
						update_post_meta( $prodid, 'wpcf-firstname-staff', $value->Staff->FirstName);
						update_post_meta( $prodid, 'wpcf-lastname-staff', $value->Staff->LastName);
						update_post_meta( $prodid, 'wpcf-bio-staff-html', $value->Staff->Bio);
						//SCHEDULES
						update_post_meta( $prodid, 'wpcf-program-id', $value->ClassDescription->Program->ID);
						update_post_meta( $prodid, 'wpcf-program-name', $value->ClassDescription->Program->Name);
						update_post_meta( $prodid, 'wpcf-program-scheduletype', $value->ClassDescription->Program->ScheduleType);
						update_post_meta( $prodid, 'wpcf-starttime', date( "H:i",strtotime( $value->StartTime ) ) );
						update_post_meta( $prodid, 'wpcf-endtime', date("H:i",strtotime($value->EndTime)));
						update_post_meta( $prodid, 'wpcf-startdate', $stripStartDate);
						update_post_meta( $prodid, 'wpcf-enddate', $stripEndDate);
						update_post_meta( $prodid, 'wpcf-course-length', $courselength);
						update_post_meta( $prodid, 'wpcf-enrollment-course', 'yes');

						//additional info 
						update_post_meta( $prodid, 'wpcf-prerequisites-class', $value->ClassDescription->Prereq);
						wp_set_object_terms( $prodid, 'enrollment-courses', 'product_cat' );
						wp_set_object_terms( $prodid, ' Lessons and Events', 'product_cat', true );

						if($value->Staff->isMale){
							update_post_meta( $prodid, 'wpcf-ismale', 'true');
						}else{
							update_post_meta( $prodid, 'wpcf-ismale', 'false');
						}
						if($value->staffMobilePhone !== NULL){	
							update_post_meta( $prodid, 'wpcf-mobilephone-staff', $value->Staff->MobilePhone);
						}else{
							update_post_meta( $prodid, 'wpcf-mobilephone-staff', '403-253-6977');
						}
				}
				if(!in_array($value->title, $completedresults)){
					$completedresults[$index] = $value->title;
				}
				$index++;
		}
	}
?>
	<script>ARRAYFULL = <?php  echo $jsonit; ?>;'\n'</script>
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
								<input type="hidden" value="Y" />
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
			<?php if($_GET ["settings-updated"] == 'true') {?>
			<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">
							<h3><span>Classes updated/imported</span></h3>
							<div class="inside">
								<?php
									foreach ($completedresults as $key => $value) {
											echo "<h3>" . $value ."</h3>" ;
									}
								?>
							</div>
						</div>
					</div>				
			</div>
			<?php } ?>
		</div> 
		<br class="clear">
	</div> <!-- #poststuff -->