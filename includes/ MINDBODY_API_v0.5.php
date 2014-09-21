<?php
ini_set("user_agent", "FOOBAR");
class MindBody_API {
	protected $client;
	protected $sourceCredentials = array("SourceName"=>'AccrueMarketing', "Password"=>'VvcW7fbmwIQSavb3uPJME2SFUsM', "SiteIDs"=>array('4639'));
	protected $userCredentials = array("Username"=>'AccrueMarketing', "Password"=>'VvcW7fbmwIQSavb3uPJME2SFUsM', "SiteIDs"=>array('4639'));
	
	// CLIENT SERVICE //
	
	function addOrUpdateClients($params) {
		$this->client = new SoapClient('https://api.mindbodyonline.com/0_5/ClientService.asmx?WSDL', array("soap_version"=>SOAP_1_1,'location'=>'https://api.mindbodyonline.com/0_5/ClientService.asmx', 'trace'=>true));
		$request = array_merge(array("SourceCredentials"=>$this->sourceCredentials, "UserCredentials"=>$this->userCredentials),$params);
		try {
			$result = $this->client->AddOrUpdateClients(array("Request"=>$request));
		} catch (SoapFault $s) {
	    	echo 'ERROR: [' . $s->faultcode . '] ' . $s->faultstring;
		} catch (Exception $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}
	
	function getClients($params) {
		$this->client = new SoapClient('https://api.mindbodyonline.com/0_5/ClientService.asmx?WSDL', array("soap_version"=>SOAP_1_1, 'trace'=>true));
		$request = array_merge(array("SourceCredentials"=>$this->sourceCredentials, "UserCredentials"=>$this->userCredentials),$params);
		try {
			$result = $this->client->GetClients(array("Request"=>$request));
		} catch (SoapFault $s) {
	    	echo 'ERROR: [' . $s->faultcode . '] ' . $s->faultstring;
		} catch (Exception $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}
	
	
	// CLASS SERVICE //
	
	function getClasses($params = array()) {
		$this->client = new SoapClient('https://api.mindbodyonline.com/0_5/ClassService.asmx?WSDL', array("soap_version"=>SOAP_1_1, 'trace'=>true));
		$request = array_merge(array("SourceCredentials"=>$this->sourceCredentials, "UserCredentials"=>$this->userCredentials),$params);
		try {
			$result = $this->client->GetClasses(array("Request"=>$request));
		} catch (SoapFault $s) {
	    	echo 'ERROR: [' . $s->faultcode . '] ' . $s->faultstring;
		} catch (Exception $e) {
	    	echo 'ERROR: ' . $e->getMessage();
		}
		return $result;
	}
	
	function getXMLRequest() {
		return $this->client->__getLastRequest();
	}
	
	function getXMLResponse() {
		return $this->client->__getLastResponse();
	}
	
}