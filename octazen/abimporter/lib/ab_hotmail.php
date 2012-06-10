<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Hotmail & MSN contacts importer

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
//include_once(dirname(__FILE__).'/hotmail2.php');
if (!defined('__ABI')) die('Please include abi.php to use this importer!');

global $_OZ_SERVICES;
$_OZ_SERVICES['hotmail'] = array('type'=>'abi', 'label'=>'Hotmail', 'class'=>'HotmailImporter');

define('HotmailImporter_LIVECONTACT_REGEX',"/<tr>.*?<td class=\"dContactPickerBodyNameCol\">.*?&#x200.;\\s*(.*?)\\s*&#x200.;.*?<\/td>\\s*<td class=\"dContactPickerBodyEmailCol\">\\s*([^<]*?)\\s*<\/td>.*?<\/tr>/ims");
define('HotmailImporter_JSREDIRECT_REGEX',"/window.location.replace\\(\"([^\"]*)\"\\)/i");
define('HotmailImporter_REDIRECT_REGEX',"/url='?([^\"]*)'?/i");
define('HotmailImporter_HOSTURL_REGEX',"/<iframe\\s+id=\"UIFrame\".*?src=\"([^\"]*)/ims");
    


/////////////////////////////////////////////////////////////////////////////////////////
//HotmailImporter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class HotmailImporter extends WebRequestor {
 
    var $LIVECONTACT_REGEX = "/<tr>.*?<td class=\"dContactPickerBodyNameCol\">.*?&#x200.;\\s*(.*?)\\s*&#x200.;.*?<\/td>\\s*<td class=\"dContactPickerBodyEmailCol\">\\s*([^<]*?)\\s*<\/td>.*?<\/tr>/ims";
	var $JSREDIRECT_REGEX = "/window.location.replace\\(\"([^\"]*)\"\\)/i";
	var $REDIRECT_REGEX = "/url='?([^\"]*)'?/i";

	//@api
	function fetchContacts ($login, $password) {

		//Hotmail mobile no longer allows access to hotmail live
		/*
		// Post login form
		$form = new HttpForm;
		$form->addField("__EVENTTARGET", "");
		$form->addField("__EVENTARGUMENT", "");
		$form->addField("LoginTextBox", $login);
		$form->addField("DomainField", "passport.com");
		$form->addField("PasswordTextBox", $password);
		$form->addField("PasswordSubmit", "Sign in");
        $postData = $form->buildPostData();
        
    	$html = $this->httpPost('https://mid.live.com/si/post.aspx?lc=1033&id=71570&ru=http%3a%2f%2fmobile.live.com%2fwml%2fmigrate.aspx%3freturl%3dhttp%253a%252f%252fmobile.live.com%252fhm%252fDefault.aspx%26fti%3dy&mlc=en-US&mspsty=302&mspto=1&tw=14400&kv=2', $postData);
		if (strpos($this->lastUrl,"error.asp")!==false || strpos($html,"AllAnnotations_Error")!==false) {
		 	$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}
		
		*/
		
		
		// Hotmail limits to 16 characters of password
		if (strlen($password)>16) $password=substr($password,0,16);

		
		$html = $this->httpGet("https://login.live.com/ppsecure/post.srf?id=2&svc=mail");
		$form = oz_extract_form_by_name($html,'f1');
		if ($form==null) {
		 	$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find login form');
		}
		$form->setField("login", $login);
		$form->setField("passwd", $password);
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);
		if (strpos($html, 'The e-mail address or password is incorrect')!==FALSE ||
			strpos($html, 'The password is incorrect')!==FALSE ||
			strpos($html, 'Please type your e-mail address in the following format')!==FALSE ||
			strpos($html, 'The .NET Passport or Windows Live ID you are signed into is not supported')!==FALSE ||
			strpos($html, 'alt="Error symbol"')!==FALSE ||
			strpos($html, 'srf_fError=1')!==FALSE) {
		 	$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}

		/////////////////////////////////////////////////////
		//HANDLE REDIRECT TO MAIN PAGE (MBOX page)
		/////////////////////////////////////////////////////
		//@hotmail.com uses javascript redirect
		$location = null;
		if (preg_match(HotmailImporter_JSREDIRECT_REGEX,$html,$matches)==0) {
			//@msn.com uses refresh redirect
			if (preg_match(HotmailImporter_REDIRECT_REGEX,$html,$matches)==0) {
				$this->close();
				return abi_set_error(_ABI_FAILED,'Cannot find redirect instruction');
			}
			$location = $matches[1];
			$html = $this->httpGet($location);
		}
		else {
			$location = $matches[1];
			$html = $this->httpGet($location);
		}

		if (preg_match(HotmailImporter_HOSTURL_REGEX,$html,$matches)) {
		 	$url = htmlentities2utf8($matches[1]);
		 	$html = $this->httpGet($url);
		}
		
		//Skip message at login
		$form = oz_extract_form_by_name($html,"MessageAtLoginForm");
		if ($form!=null) {
			$form->setField("TakeMeToInbox", "Continue");
			$postData = $form->buildPostData();
			$html = $this->httpPost($form->action, $postData);
		}				

		//Fetch contacts from contacts picker		
		$html = $this->httpGet('http://mail.live.com/mail/ContactPickerLight.aspx?n='.rand(0,20000));
		
		if (preg_match(HotmailImporter_HOSTURL_REGEX,$html,$matches)) {
		 	$url = htmlentities2utf8($matches[1]);
		 	$html = $this->httpGet($url);
		}
		
		//$html = $this->httpGet('/mail/ContactPickerLight.aspx?n='.rand(0,20000));
		$al = array();	
        preg_match_all(HotmailImporter_LIVECONTACT_REGEX, $html, $matches, PREG_SET_ORDER);
		foreach ($matches as $val) {
            $name = htmlentities2utf8(trim($val[1]));
            $email = htmlentities2utf8(trim($val[2]));

            //if (abi_valid_email($email)) {
			if (abi_valid_email($email)) {
				$contact = new Contact($name,$email);
				$al[] = $contact;
			}
		}
		return $al;
	}
}

//Hotmail
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["hotmail.com"]='HotmailImporter';
$_DOMAIN_IMPORTERS["msn.com"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.fr"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.it"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.de"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.co.jp"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.co.uk"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.com.ar"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.co.th"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.com.tr"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.es"]='HotmailImporter';
$_DOMAIN_IMPORTERS["msnhotmail.com"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.jp"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.se"]='HotmailImporter';
$_DOMAIN_IMPORTERS["hotmail.com.br"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com.ar"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com.au"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.at"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.be"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.ca"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.cl"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.cn"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.dk"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.fr"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.de"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.hk"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.ie"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.it"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.jp"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.co.kr"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com.my"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com.mx"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.nl"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.no"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.ru"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com.sg"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.co.za"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.se"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.co.uk"]='HotmailImporter';
$_DOMAIN_IMPORTERS["live.com"]='HotmailImporter';
$_DOMAIN_IMPORTERS["windowslive.com"]='HotmailImporter';


?>