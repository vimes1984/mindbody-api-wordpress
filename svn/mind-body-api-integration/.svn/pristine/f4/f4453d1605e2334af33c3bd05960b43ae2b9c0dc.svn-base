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

/* Short and sweet */
define('WP_USE_THEMES', false);

$getdomain =  $_SERVER['DOCUMENT_ROOT'];
require('../../../wp-config.php');


require_once(dirname(__FILE__). "/includes/classService.php");

$no_exists_value = get_option( 'mind_body_options' );
$sourcename = $no_exists_value['sourcename'];
$password = $no_exists_value['password'];
$siteID = $no_exists_value['siteID'];

$Username = $no_exists_value['Username'];
$Passworduser = $no_exists_value['Passworduser'];

// initialize default credentials
$creds = new SourceCredentials($sourcename, $password, array($siteID));

$user = new UserCredentials($Username, $Passworduser, array($siteID));

$classService = new MBClassService();

if(isset( $_GET['start'] ) && !empty( $_GET['start'] )){ 
  $StartClassDateTime = new DateTime($_GET['start']);
  $EndClassDateTime = new DateTime($_GET['end']);

}else{
  $StartClassDateTime = NULL;
  $EndClassDateTime = NULL;
}

$classService->SetDefaultCredentials($creds);
$classService->SetDefaultUserCredentials($user);

// initialize default credentials


$result = $classService->GetClasses(array(), array(), array(), $StartClassDateTime, $EndClassDateTime, NULL, 150, 1);

$resultget = $result->GetClassesResult->Classes->Class;
$resultafter = array();
/*
*
*Traverse array using shortcode level variable if it's set!
*
*
*/
$i= 0;
foreach ($resultget as $class) {
  $resultafter[$i] = new StdClass;
  $resultafter[$i]->title = $class->ClassDescription->Name;
  $resultafter[$i]->description =  $class->ClassDescription->Description;
  $resultafter[$i]->id = $class->ID;
  $resultafter[$i]->start = $class->StartDateTime;
  $resultafter[$i]->end = $class->EndDateTime;
    $i ++;
} 
/****
  *
  *Convert our returned array  
  *to json ready for angularJS...
  *
  ****/
$jsonitfull = json_encode($resultafter);

echo $jsonitfull;