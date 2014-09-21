<?php
require_once("mbApi.php");

class MBFinderService extends MBAPIService
{	
	function __construct($debug = false)
	{
		$endpointUrl = "https://" . GetApiHostname() . "/0_5/FinderService.asmx";
		$wsdlUrl = $endpointUrl . "?wsdl";
	
		$this->debug = $debug;
		$option = array();
		if ($debug)
		{
			$option = array('trace'=>1);
		}
		$this->client = new soapclient($wsdlUrl, $option);
		$this->client->__setLocation($endpointUrl);
	}
	
	/**
	 * Returns the raw result of the MINDBODY SOAP call.
	 * @param int $PageSize
	 * @param int $CurrentPage
	 * @param string $XMLDetail
	 * @param string $Fields
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @return object The raw result of the SOAP call
	 */
	public function GetClassesWithinRadius($searchLatitude, $searchLongitude, $searchRadius, $startDateTime, $endDateTime, $searchLocationID = null, $searchClassID = null, $searchText = null, $searchOption = null, $searchDomain = null, $PageSize = null, $CurrentPage = null, $XMLDetail = XMLDetail::Full, $Fields = NULL, SourceCredentials $credentials = null)
	{		
		$additions = array();
		$additions['SearchLatitude'] = $searchLatitude;
		$additions['SearchLongitude'] = $searchLongitude;
		$additions['SearchRadius'] = $searchRadius;
		$additions['SearchLocationID'] = $searchLocationID;
		$additions['StartDateTime'] = $startDateTime->format(DateTime::ATOM);
		$additions['EndDateTime'] = $endDateTime->format(DateTime::ATOM);
		if (isset($searchClassID))
		{
			$additions['SearchClassID'] = $searchClassID;
		}
		if (isset($searchText))
		{
			$additions['SearchText'] = $searchText;
		}
		if (isset($searchOption))
		{
			$additions['SearchOption'] = $searchOption;
		}
		if (isset($searchDomain))
		{
			$additions['SearchDomain'] = $searchDomain;
		}
		
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try
		{
			$result = $this->client->GetClassesWithinRadius($params);
		}
		catch (SoapFault $fault)
		{
			DebugResponse($this->client);
			// <xmp> tag displays xml output in html
			echo '</xmp><br/><br/> Error Message : <br/>', $fault->getMessage(); 
		}
		
		if ($this->debug)
		{
			DebugRequest($this->client);
			DebugResponse($this->client, $result);
		}
		
		return $result;
	}
	
	/**
	 * Returns the raw result of the MINDBODY SOAP call.
	 * @param int $PageSize
	 * @param int $CurrentPage
	 * @param string $XMLDetail
	 * @param string $Fields
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @return object The raw result of the SOAP call
	 */
	public function AddOrUpdateFinderUsers($updateAction = null, $email, $password, $firstName, $lastName, $partnerID, $locationID = null, $PageSize = null, $CurrentPage = null, $XMLDetail = XMLDetail::Full, $Fields = NULL, SourceCredentials $credentials = null)
	{		
		$additions = array();
		if (isset($updateAction))
		{
			$additions['UpdateAction'] = $updateAction;
		}
		$additions['PartnerID'] = $partnerID;
		
		$finderUser = array();
		$finderUser['Email'] = $email;
		$finderUser['Password'] = $password;
		$finderUser['FirstName'] = $firstName;
		$finderUser['LastName'] = $lastName;
		
		$additions['FinderUsers'] = $finderUser;
		
		if (isset($locationID))
		{
			$additions['LocationID'] = $locationID;
		}
		
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try
		{
			$result = $this->client->AddOrUpdateFinderUsers($params);
		}
		catch (SoapFault $fault)
		{
			DebugResponse($this->client);
			// <xmp> tag displays xml output in html
			echo '</xmp><br/><br/> Error Message : <br/>', $fault->getMessage(); 
		}
		
		if ($this->debug)
		{
			DebugRequest($this->client);
			DebugResponse($this->client, $result);
		}
		
		return $result;
	}
	
	/**
	 * Returns the raw result of the MINDBODY SOAP call.
	 * @param int $PageSize
	 * @param int $CurrentPage
	 * @param string $XMLDetail
	 * @param string $Fields
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @return object The raw result of the SOAP call
	 */
	public function FinderCheckoutShoppingCart($mbfClassID, $partnerID, $searchLatitude, $searchLongitude, $paymentInfo, $test = false, $PageSize = null, $CurrentPage = null, $XMLDetail = XMLDetail::Full, $Fields = NULL, SourceCredentials $credentials = null, UserCredentials $usercredentials = null)
	{		
		$additions = array();
		$additions['MBFClassID'] = $mbfClassID;
		$additions['SearchLatitude'] = $searchLatitude;
		$additions['SearchLongitude'] = $searchLongitude;
		$additions['PaymentInfo'] = $paymentInfo;
		$additions['PartnerID'] = $partnerID;
		if (isset($test))
		{
			$additions['Test'] = $test;
		}
		
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields, $this->GetUserCredentials($usercredentials));
		
		$this->client->__setLocation("https://" . GetApiHostname() . "/0_5/FinderService.asmx"); //Setting the Endpoint to use SSL
		
		try
		{
			$result = $this->client->FinderCheckoutShoppingCart($params);
		}
		catch (SoapFault $fault)
		{
			DebugResponse($this->client);
			// <xmp> tag displays xml output in html
			echo '</xmp><br/><br/> Error Message : <br/>', $fault->getMessage(); 
		}
		
		if ($this->debug)
		{
			DebugRequest($this->client);
			DebugResponse($this->client, $result);
		}
		
		return $result;
	}
}

