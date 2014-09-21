<?php
require_once("../includes/classService.php");
require_once("../includes/clientService.php");

if (!isset($_POST['submit'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
	<head>
		<title>Book a client into class workflow</title>
		<link rel="stylesheet" type="text/css" href="../styles/site.css" />
	</head>
	<body>
	<form method="post" action="bookClientIntoClass.php">
		Source Name:
		<input type="text" size="25" name="sName"/><br/>
		Password:
		<input type="password" size="25" name="password"/><br/>
		SiteID:
		<input type="text" size="5" name="siteID" value="-99"/><br/>
		<input type="submit" value="submit" name="submit"/>
	</form>
<?php
} else {
$sourcename = $_POST["sName"];
$password = $_POST["password"];
$siteID = $_POST["siteID"];

// initialize default credentials
$creds = new SourceCredentials($sourcename, $password, array($siteID));

// initialize the services you are going to use
$classService = new MBClassService(true);
$classService->SetDefaultCredentials($creds);

$clientService = new MBClientService(true);
$clientService->SetDefaultCredentials($creds);

// get a list of upcoming classes
$result = $classService->GetClasses(array(), array(), array(), new DateTime("2010-11-28"), new DateTime("2010-12-04"), null, 10, 0);

$classHtml = '<table><tr><td>ID</td><td>Name</td><td>Start Time</td></tr>';
$classes = toArray($result->GetClassesResult->Classes->Class);
foreach ($classes as $class)
{
	$classHtml .= sprintf('<tr><td>%d</td><td>%s</td><td>%s</td></tr>', $class->ID, $class->ClassDescription->Name, $class->StartDateTime);
}
$classHtml .= '</table>';

// pick a classID to sign a client into
$classID = $classes[0]->ID;
?>	
		<h2>Class List</h2>
		<?php echo($classHtml) ?>
		
		<p>Pick a classID to sign a client into: <?php echo($classID) ?></p>
		
<?php
// validate the current user's MB login information to identify the client
$result = $clientService->ValidateLogin("foxmccloud", "foxmccloud1");

$clientHtml = '<table><tr><td>ID</td><td>Name</td><td>GUID</td></tr>';
$client = $result->ValidateLoginResult->Client;
$guid = $result->ValidateLoginResult->GUID;
if ($client != null)
{
	$clientHtml .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $client->ID, $client->FirstName . " " . $client->LastName, $guid);
}
$clientHtml .= '</table>';
?>
		<h2>Selected Client</h2>
		<?php echo($clientHtml) ?>
<?php
// try to sign them up for the class
$result = $classService->AddClientsToClasses(array($client->ID), array($classID));
/*

$classHtml = '<table><tr><td>ID</td><td>Name</td><td>GUID</td></tr>';
if (count($result->AddClientsToClassesResult->Classes) > 0)
{
	$classes = toArray($result->AddClientsToClassesResult->Classes->Class);
	foreach ($classes as $class)
	{
		$classHtml .= sprintf('<tr><td>%d</td><td>%s</td><td>%s</td></tr>', $class->ID, $class->ClassDescription->Name, $class->StartDateTime);
	}
	$classHtml .= '</table>';
}

// find out if they have a service to pay for this class

// if they do, sign them up

// if they don't, purchase a service to pay for it
*/
}
?>
	</body>
</html>