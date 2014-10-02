<?php
require_once("mbApi.php");

class MBSaleService extends MBAPIService{	
	function __construct($debug = false){
		$endpointUrl = "https://" . GetApiHostname() . "/0_5/SaleService.asmx";
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
	public function GetServices(array $programIDs = array(), array $sessionTypeIDs = array(), array $serviceIDs = array(), int $classID = null, int $classScheduleID = null, $sellOnline = FALSE, int $locationID = null, SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = NULL, $CurrentPage = NULL, $Fields = NULL){
		$additions = array();
		$additions['LocationID'] = '';
		$additions['HideRelatedPrograms'] = '';
		if (isset($programIDs))
		{
			$additions['ProgramIDs'] = $programIDs;
		}
		if (isset($sessionTypeIDs))
		{
			$additions['SessionTypeIDs'] = $sessionTypeIDs;
		}
		if (isset($serviceIDs))
		{
			$additions['ServiceIDs'] = $serviceIDs;
		}
		if (isset($classID))
		{
			$additions['ClassID'] = $classID;
		}
		if (isset($classScheduleID))
		{
			$additions['ClassScheduleID'] = $classScheduleID;
		}
		if (isset($locationID))
		{
			$additions['LocationID'] = $locationID;
		}
		if (isset($sellOnline))
		{
			$additions['SellOnline'] = $sellOnline;
		}
		
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try {
			$result = $this->client->GetServices($params);
		} 
		catch (SoapFault $fault)
		{
			DebugResponse($this->client, $fault->getMessage());
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
	 * GetSales() cleans up the result of the raw SOAP call. PHP::SOAP does some wierd things that
	 * make parsing difficult; mainly single results vs. multiple results are handled differently. 
	 * This function takes the raw result and cleans it up, returning an array of Client objects,
	 * with all their fields initialized
	 * @param int $saleID The exact sale ID
	 * @param DateTime $saleStartDate The start date to retrieve sales
	 * @param DateTime $saleEndDate The end date to retrieve sales
	 * @param int $paymentMethodID The ID of the payment method that must have been used on the sales.
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @param string $XMLDetail
	 * @param int $PageSize
	 * @param int $CurrentPage
	 * @param string $Fields
	 * @return An array of Sale objects that match the current filter settings
	 */
	public function GetSales($saleID, $saleStartDate, $saleEndDate, $paymentMethodID, SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = NULL, $CurrentPage = NULL, $Fields = NULL) {
		$result = $this->GetSalesRaw($saleID, $saleStartDate, $saleEndDate, $paymentMethodID, $credentials, $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		$properties = get_object_vars($result->GetSalesResult->Sales);
		if (empty($properties)) {
			$return = array();
		}
		else if (is_array($result->GetSalesResult->Sales->Sale)) {
			// Multiple results returned
			foreach ($result->GetSalesResult->Sales->Sale as $sale) {
				$return[] = Sale::ConvertFromstdClass($sale);
			}
		}
		else {
			// Only a single result
			$return[] = Sale::ConvertFromstdClass($result->GetSalesResult->Sales->Sale);
		}
		
		if ($this->debug) {
			echo('<h2>GetSales Result</h2><pre>');
			print_r($return);
			echo("</pre>");
		}
		
		return $return;
	}
	
	/**
	 * GetSalesRaw() is identical to GetSales(), but returns the raw result of the MINDBODY SOAP call.
	 * @param int $saleID The exact sale ID
	 * @param DateTime $saleStartDate The start date to retrieve sales
	 * @param DateTime $saleEndDate The end date to retrieve sales
	 * @param int $paymentMethodID The ID of the payment method that must have been used on the sales.
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @param string $XMLDetail
	 * @param int $PageSize
	 * @param int $CurrentPage
	 * @param string $Fields
	 * @return object The raw result of the SOAP call
	 */
	public function GetSalesRaw($saleID, $saleStartDate, $saleEndDate, $paymentMethodID, SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = NULL, $CurrentPage = NULL, $Fields = NULL){
		$additions = array();
		if (isset($saleID)) {
			$additions['SaleID'] = $saleID;
		}
		if (isset($saleStartDate)) {
			$additions['StartSaleDateTime'] = $saleStartDate->format(DateTime::ATOM);
		}
		if (isset($saleEndDate)) {
			$additions['EndSaleDateTime'] = $saleEndDate->format(DateTime::ATOM);
		}
		if (isset($paymentMethodID)) {
			$additions['PaymentMethodID'] = $paymentMethodID;
		}
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try {
			$result = $this->client->GetSales($params);
		} catch (SoapFault $fault) {
			// <xmp> tag displays xml output in html
			echo 'Request : <br/><xmp>',
			$this->client->__getLastRequest(),
			'</xmp><br/><br/> Error Message : <br/>',
			$fault->getMessage(); 
		}
		
		if ($this->debug) {
			echo 'Request : <br/><xmp>', $this->client->__getLastRequest(), '</xmp><br/><br/>';
			echo('<h2>GetSales Result</h2><pre>');
			print_r($result);
			echo("</pre>");
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
	public function CheckoutShoppingCart($cartID = null, $clientID, $Test = false, array $cartItems, array $payments, SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = NULL, $CurrentPage = NULL, $Fields = NULL){
		$additions = array();
		$additions['ClientID'] = $clientID;
		$additions['CartItems'] = $cartItems;
		$additions['Payments'] = $payments;
		
		if (isset($cartID))
		{
			$additions['CartID'] = $cartID;
		}
		if (isset($Test))
		{
			$additions['Test'] = $Test;
		}
		
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		$this->client->__setLocation("https://" . GetApiHostname() . "/0_5/SaleService.asmx"); //Setting the Endpoint to use SSL
		
		try {
			$result = $this->client->CheckoutShoppingCart($params);
		} 
		catch (SoapFault $fault)
		{
			DebugResponse($this->client, $fault->getMessage());
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
	 * @param $ProductIDs array(string => "")
	 * @param $SearchText string 
	 * @param $SearchDomain string 
	 * @param $CategoryIDs array(int => "")
	 * @param $SubCategoryIDs array(int => "")
	 * @param $SellOnline  Boolean
	 * @param $LocationID  int
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @return object The raw result of the SOAP call
	 */
	public function GetProducts($ProductIDs = array(), $SearchText = NULL, $SearchDomain = NULL, $CategoryIDs =  array(), $SubCategoryIDs = array(), $SellOnline = false, $LocationID = NULL,  SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = 150, $CurrentPage = 0){
		$additions = array();
		if (isset($ProductIDs)) {
			$additions['ProductIDs'] = $ProductIDs;
		}
		if (isset($SearchText)) {
			$additions['SearchText'] = $SearchText;
		}
		if (isset($SearchDomain)) {
			$additions['SearchDomain'] = $SearchDomain;
		}
		if (isset($CategoryIDs)) {
			$additions['CategoryIDs'] = $CategoryIDs;
		}
		if (isset($SubCategoryIDs)) {
			$additions['SubCategoryIDs'] = $SubCategoryIDs;
		}
		if (isset($SellOnline)) {
			$additions['SellOnline'] = $SellOnline;
		}
		if (isset($LocationID)) {
			$additions['LocationID'] = $LocationID;
		}				
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try {
			$result = $this->client->GetProducts($params);
		} catch (SoapFault $fault) {
			// <xmp> tag displays xml output in html
			echo 'Request : <br/><xmp>',
			$this->client->__getLastRequest(),
			'</xmp><br/><br/> Error Message : <br/>',
			$fault->getMessage(); 
		}
		
		if ($this->debug) {
			echo 'Request : <br/><xmp>', $this->client->__getLastRequest(), '</xmp><br/><br/>';
			echo('<h2>GetSales Result</h2><pre>');
			print_r($result);
			echo("</pre>");
		}
		
		return $result;
	}
	/**
	 * Returns the raw result of the MINDBODY SOAP call.
	 * @param $PackageIDs List of Int32	An ID filter on packages.
	 * @param $SellOnline  Boolean
	 * @param SourceCredentials $credentials A source credentials object to use with this call
	 * @return object The raw result of the SOAP call
	 */
	public function GetPackages($PackageIDs  = array(), $SellOnline = false,  SourceCredentials $credentials = null, $XMLDetail = XMLDetail::Full, $PageSize = 150, $CurrentPage = 0){
		$additions = array();
		if (isset($PackageIDs )) {
			$additions['PackageIDs '] = $PackageIDs ;
		}
		if (isset($SellOnline)) {
			$additions['SellOnline'] = $SellOnline;
		}				
		$params = $this->GetMindbodyParams($additions, $this->GetCredentials($credentials), $XMLDetail, $PageSize, $CurrentPage, $Fields);
		
		try {
			$result = $this->client->GetPackages($params);
		} catch (SoapFault $fault) {
			// <xmp> tag displays xml output in html
			echo 'Request : <br/><xmp>',
			$this->client->__getLastRequest(),
			'</xmp><br/><br/> Error Message : <br/>',
			$fault->getMessage(); 
		}
		
		if ($this->debug) {
			echo 'Request : <br/><xmp>', $this->client->__getLastRequest(), '</xmp><br/><br/>';
			echo('<h2>GetSales Result</h2><pre>');
			print_r($result);
			echo("</pre>");
		}
		
		return $result;
	}

}