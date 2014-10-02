<?php
require_once("../includes/appointmentService.php");
require_once("../includes/clientService.php");
require_once("../includes/saleService.php");

if (!isset($_POST['submit'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
	<head>
		<title>Book a client into appointment workflow</title>
		<link rel="stylesheet" type="text/css" href="../styles/site.css" />
	</head>
	<body>
	<form method="post" action="bookClientIntoAppointment.php">
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
$appointmentService = new MBAppointmentService();
$appointmentService->SetDefaultCredentials($creds);

$clientService = new MBClientService();
$clientService->SetDefaultCredentials($creds);

$saleService = new MBSaleService();
$saleService->SetDefaultCredentials($creds);

// get a list of upcoming classes
$result = $appointmentService->GetBookableItems(array(23), array(), array(), null, null);

$timeHtml = '<table><tr><td>Location Name</td><td>Staff Name</td><td>Start Time</td></tr>';
$times = toArray($result->GetBookableItemsResult->ScheduleItems->ScheduleItem);
foreach ($times as $time)
{
	$timeHtml .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $time->Location->Name, $time->Staff->FirstName, $time->StartDateTime);
}
$timeHtml .= '</table>';

// pick a time to sign a client into
$time = $times[0]->StartDateTime;
?>	
		<h2>Time List</h2>
		<?php echo($timeHtml) ?>
		
		<p>Pick a time to book a client into: <?php echo($time) ?></p>
		
<?php
// validate the current user's MB login information to identify the client
$result = $clientService->ValidateLogin("miketyson", "test1234");

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

// find out if they have a service to pay for this appointment
$result = $clientService->GetClientServices($client->ID, array(), array(23));
$serviceHtml = '<table><tr><td>ID</td><td>Name</td></tr>';
if ($result->GetClientServicesResult->ResultCount > 0) {
	
}
$serviceHtml .= '</table>';
if ($result->GetClientServicesResult->ResultCount == 0) {
	$serviceHtml .= '<p>No services exist for this type.</p>';
}
?>
		<h2>Selected Service</h2>
		<?php echo($serviceHtml) ?>
<?php

// find service
if ($result->GetClientServicesResult->ResultCount == 0) {
	$result = $saleService->GetServices(array(), array(23));
	$buyserviceHTML = '<table><tr><td>ID</td><td>Name</td><td>Count</td></tr>';
	if ($result->GetServicesResult->ResultCount > 0) {
		$services = toArray($result->GetServicesResult->Services->Service);
		foreach ($services as $service) {
			if (isset($service->ID)) {
				$buyserviceHTML .= sprintf('<tr><td>%d</td><td>%s</td><td>%d</td></tr>', $service->ID, $service->Name, $service->Count);
			}
			else {
				$buyserviceHTML .= sprintf('<tr><td></td><td>%s</td><td>%d</td></tr>', $service->Name, $service->Count);
			}
		}
	}
	$buyserviceHTML .= '</table>';
	?>
			<h2>Pick a Service</h2>
			<?php echo($buyserviceHTML) ?>
	<?php
	if ($result->GetServicesResult->ResultCount > 0) {
		$service = $services[0];
		?>
			<p>Picked service: <?php echo($service->ID) ?></p>
		<?php
		$appointment->Location->ID = $times[0]->Location->ID;
		$appointment->Staff->ID = $times[0]->Staff->ID;
		$appointment->SessionType->ID = $times[0]->SessionType->ID;
		$appointment->StartDateTime = $time;
		$item->ID = $service->ID;
		$cartItem->Appointments = array($appointment);
		$cartItem->Quantity = 1;
		$cartItem->Item = toComplexType($item, 'Service');
		$payment->CreditCardNumber = 81524245458;
		$payment->Amount = 2;
		$payment->BillingAddress = '123 Happy Ln';
		$payment->BillingCity = 'San Luis Obispo';
		$payment->BillingState = 'CA';
		$payment->BillingPostalCode = '93405';
		$payment->ExpYear = 2014;
		$payment->ExpMonth = 7;
		$payment->BillingName = 'Mike Tyson';
		$paymentItem = toComplexType($payment, 'CreditCardInfo');
		
		$result = $saleService->CheckoutShoppingCart(null, $client->ID, true, array($cartItem), array($paymentItem));
		
		if ($result->CheckoutShoppingCartResult->Status == 'Success') {
			?><h2>Done</h2><?php
		}
	}
}
else {
	// try to sign them up for the appointment
	$appointment->Location->ID = $times[0]->Location->ID;
	$appointment->Staff->ID = $times[0]->Staff->ID;
	$appointment->SessionType->ID = $times[0]->SessionType->ID;
	$appointment->Client->ID = $client->ID;
	$appointment->StartDateTime = $time;

	$result = $appointmentService->AddOrUpdateAppointments(array($appointment));

	$appointmentHtml = '<table><tr><td>Action</td><td>Message</td><td>GUID</td></tr>';
	if (count($result->AddOrUpdateAppointmentsResult->Appointments) > 0)
	{
		$appointments = toArray($result->AddOrUpdateAppointmentsResult->Appointments->Appointment);
		foreach ($appointments as $appointment)
		{
			$message = toArray($appointment->Messages);
			$appointmentHtml .= sprintf('<tr><td>%s</td><td>%s</td><td></td></tr>', $appointment->Action, $message[0]->string);
		}
		$appointmentHtml .= '</table>';
	} 
?>
		<h2>Results</h2>
		<?php echo($appointmentHtml) ?>
<?php
}
}
?>
	</body>
</html>