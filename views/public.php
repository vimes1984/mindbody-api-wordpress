<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruesupport.com
 * @copyright 4-29-2014 Accrue
 */
require_once(plugin_dir_path(__FILE__) . "../includes/classService.php");
//require_once("../includes/clientService.php");
$no_exists_value = get_option( 'mind_body_options' );
$sourcename = $no_exists_value['sourcename'];
$password = $no_exists_value['password'];
$siteID = $no_exists_value['siteID'];
// initialize default credentials
$creds = new SourceCredentials($sourcename, $password, array($siteID));

$classService = new MBClassService();
$classService->SetDefaultCredentials($creds);

$result = $classService->GetClassDescriptions(array(), array(), array(), null, null, 150, 0);

$resultTest = $result->GetClassDescriptionsResult->ClassDescriptions->ClassDescription;



$resultafter = array();
/*
*
*Traverse array using shortcode level variable if it's set!
*
*
*/
if ($level!= ' ') {
	# code...
	$i = '0';
	foreach ($resultTest as $lesson) {
			    if (isset($lesson->Level->Name) && $lesson->Level->Name == $level) {
			    	$i++;
			        $resultafter[$lesson->Name] = $lesson;
			    }	
	}
	
}else{
	foreach ($resultTest as $lesson) {
			        $resultafter[$lesson->Name] = $lesson;	
	}	
}
/****
	*
	*Convert our returned array  
	*to json ready for angularJS...
	*
	****/
	$jsonitfull = json_encode($resultafter);
echo '<pre>';
//var_dump($foo);
//var_dump($resultClass);
//var_dump($resultTest);
//var_dump($resultafter);
echo "</pre>";

?>
<!-- This file is used to markup the public facing aspect of the plugin. -->
	<script type="text/javascript">
			jsonitfull = <?php echo $jsonitfull; ?>;'\n'
		</script>
		<div class="row">
			<div ng-controller="ngAppDemoController">
				<br>
				<br><br>

				<div class="row">
					<h1>search for your Class</h1>
					<h3><input class="twelve columns" ng-model="searchText" placeholder="SEARCH QUERY HERE"></h3>
						<br>
				<br><br>
			</div>
				  <div class="animate-repeat panel" ng-repeat="items in arrayfull | filter:searchText">
				        <div class="row">
					        <div class="four columns">
					        	<h3>Class Name:</h3>
					        	<p>{{items.Name}}</p>
						 	</div>
					       	<div class="four columns">
					         	<h3>Class Description:</h3> 
					         	<p>{{items.Description}}</p> 
					      	</div>
							<div class="four columns">
				         		<h3>Level:</h3> 
				         		<p>{{items.Level.Name}}</p>
							</div>
						</div>
						<div class="row">
					        <div class="four columns">
					        	<h3>Is avaiable:</h3>
								<p>{{items.IsAvailable}}</p>
						 	</div>
					       	<div class="four columns">
					         	<h3>Contact Details:</h3> 
								<h5>Teacher:</h5>
								<p>{{items.Staff.FirstName}} {{items.Staff.LastName}}</p>
					         	<p>Phone: {{items.Staff.MobilePhone}}</p>
					         	<p>{{items.Staff.State}}</p>
					      	</div>
							<div class="four columns">
				         		<h3>Start time:</h3> 
				         		<p>{{items.StartDateTime}}</p>
							</div>			
						</div>
			</div>	
		</div>
			<?php
			//var_dump($resultClass);
/*

			$cdsHtml = '<table><tr><td>ID</td><td>Name</td></tr>';
			$cds = toArray($result->GetClassDescriptionsResult->ClassDescriptions->ClassDescription);
			foreach ($cds as $cd) {
				$cdsHtml .= sprintf('<tr><td>%d</td><td>%s</td></tr>', $cd->ID, $cd->Name);
			}
			$cdsHtml .= '</table>';
				*/
			//echo($cdsHtml); 
			?>
