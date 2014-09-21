<?php
require_once("../includes/finderService.php");

if (!isset($_POST['submit'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
	<head>
		<title>Book a client into Finder class workflow</title>
		<link rel="stylesheet" type="text/css" href="../styles/site.css" />
	</head>
	<body>
	<form method="post" action="bookClientIntoFinderClass.php">
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
$usercreds = new UserCredentials('php@test.com', 'test123', array());

// initialize the services you are going to use
$finderService = new MBFinderService(true);
$finderService->SetDefaultCredentials($creds);
$finderService->SetDefaultUserCredentials($usercreds);
$searchLat = 35.238;
$searchLon = -120.621;
$partnerID = 101;
// get a list of upcoming classes
$result = $finderService->GetClassesWithinRadius($searchLat, $searchLon, 20, new DateTime(), new DateTime(), null, null, null, null, null, 15, 0);

$classHtml = '<table><tr><td>ID</td><td>Name</td><td>Start Time</td><td>Price</td><td>Bookable</td></tr>';
$classes = toArray($result->GetClassesWithinRadiusResult->FinderClasses->FinderClass);
foreach ($classes as $class)
{
	$classHtml .= sprintf('<tr><td>%d</td><td>%s</td><td>%s</td><td>%f</td><td>%b</td></tr>', $class->MBFClassID, $class->ClassName, $class->StartDateTime, $class->Price, $class->Bookable);
	if ($class->Bookable) {
		$selectedClass = $class;
	}
}
$classHtml .= '</table>';

// pick a classID to sign a client into
?>	
		<h2>Class List</h2>
		<?php echo($classHtml) ?>
		
		<p>Pick a classID to sign a client into: <?php echo($selectedClass->MBFClassID) ?></p>
		
<?php
// Create MBFUser
$result = $finderService->AddFinderUser('php@test.com', 'test123', 'Php', 'Test', $partnerID);
$client = $result->AddFinderUserResult->Client;
if ($client->Action != "Failed") {
	$clientHtml = '<h2>User Added</h2>';	
}
else {
	$clientHtml = '<h2>User Exist</h2>';
}
echo($clientHtml);

// Book MBFUser into class
$payment->CreditCardNumber = '4111111111111111';
$payment->Amount = $selectedClass->Price;
$payment->BillingAddress = '123 Happy Ln';
$payment->BillingCity = 'San Luis Obispo';
$payment->BillingState = 'CA';
$payment->BillingPostalCode = '93405';
$payment->ExpYear = 2014;
$payment->ExpMonth = 7;
$payment->BillingName = 'PHP Test';
$paymentItem = toComplexType($payment, 'CreditCardInfo');
$result = $finderService->FinderCheckoutShoppingCart($selectedClass->MBFClassID, $partnerID, $searchLat, $searchLon, $paymentItem, true);
}
 
?>

		
	</body>
</html>