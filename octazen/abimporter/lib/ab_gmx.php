<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

GMX contacts importer

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this importer!');

global $_OZ_SERVICES;
$_OZ_SERVICES['gmx'] = array('type'=>'abi', 'label'=>'GMX.net', 'class'=>'GmxImporter');

define('GmxImporter_DETAIL_REGEX',"/CUSTOMERNO=(\\d+).*?&(?:amp;)?t=([^&]+)/ims");

/////////////////////////////////////////////////////////////////////////////////////////
//GmxImporter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class GmxImporter extends WebRequestor {
 
 	var $t;
 	var $customerNo;

	//@api
	function logout () 
	{
		$this->httpGet("http://logout.gmx.net");
	}
	
	//@api
	function login ($loginemail, $password) {
		$form = new HttpForm;
		$form->addField("AREA", "1");
		$form->addField("EXT", "redirect");
		$form->addField("EXT2", "");
		$form->addField("id", $loginemail);
		$form->addField("p", $password);
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$postData = $form->buildPostData();
		$html = $this->httpPost("http://service.gmx.net/de/cgi/login", $postData);
		if (strpos($html, 'Diese Kennung wurde vom Systembetreiber gesperrt')!=false) {
		 	$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Account closed by system operator');
		}
		if (strpos($html, '<div class="error">')!=false ||
			strpos($html, 'errormodule')!=false) {
		 	$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}

        if (preg_match(GmxImporter_DETAIL_REGEX,$this->lastUrl,$matches)==0) {
         	//The last url may not be pointing to mainbox, but it may be an advertisement.
	        if (preg_match(GmxImporter_DETAIL_REGEX,$html,$matches)==0) {
			 	$this->close();
				return abi_set_error(_ABI_FAILED,'Cannot find CUSTOMERNO and t');
	        }
		}

		$this->customerNo = urldecode($matches[1]);
		$this->t = urldecode($matches[2]);
		
		return abi_set_success();
	}
	
	
	function fetchCsv () {

		$form = new HttpForm;
		$form->addField("CUSTOMERNO", $this->customerNo);
		$form->addField("t", $this->t);
		$form->addField("site", "importexport");
		$form->addField("language", "english");
		$form->addField("dataformat", "o2002");
		$form->addField("b_export", "Export starten");
		$postData = $form->buildPostArray();
		
		$html = $this->httpPost("http://service.gmx.net/de/cgi/addrbk.fcgi", $postData,'utf-8');

		//Convert character set to utf-8
		if (function_exists('mb_convert_encoding')) $html = mb_convert_encoding($html, "utf-8","ISO-8859-1");
		else if (function_exists('iconf')) $html = iconv('ISO-8859-1', 'utf-8', $html);
		//else, we can't perform the conversion. Return raw form.
		
		//ISO-8859-1

	 	$this->logout();
		return $html;
		/*		
		$res = abi_extract_outlook_csv($html);
		

	 	$this->close();
		return $res;
	 	*/
	}	
	
	//@api
	function fetchContacts ($loginemail, $password) {

		$res = $this->login($loginemail,$password);
		if ($res!=_ABI_SUCCESS) return $res;

		$html = $this->fetchCsv();
		
	 	$this->logout();
	 	
		if (!is_string($html)) {
			return $html;
		}
		$res = abi_extract_outlook_csv($html);
		return $res;
	}
		
	//@api
	function fetchContacts2 ($loginemail, $password) {
	 
		$res = $this->login($loginemail,$password);
		if ($res!=_ABI_SUCCESS) return $res;
	 
		$html = $this->fetchCsv();
		
	 	$this->logout();
		
		if (!is_string($html)) {
			return $html;
		}
		$ce = new OzCsvExtractor;
		$cl = $ce->extract($html);
		
		//Swap fields (for tonline fix)
		$n = count($cl);
		for ($i=0; $i<$n; $i++) {
		 	$c =& $cl[$i];
			$c->swap('EmailAddress','Email2Address');
			$c->swap('EmailDisplayName','Email2DisplayName');
			$c->swap('WebPage','WebPage2');
			$c->swap('MobilePhone','MobilePhone2');
		}
		
		return $cl;
	}
	
	//@api
	function fetchAbContacts () {
		$html = $this->fetchCsv();
		if (!is_string($html)) {
			return $html;
		}
		$ce = new OzCsvExtractor;
		$cl = $ce->extract($html);
		
		//Swap fields (for tonline fix)
		$n = count($cl);
		for ($i=0; $i<$n; $i++) {
		 	$c =& $cl[$i];
			$c->swap('EmailAddress','Email2Address');
			$c->swap('EmailDisplayName','Email2DisplayName');
			$c->swap('WebPage','WebPage2');
			$c->swap('MobilePhone','MobilePhone2');
		}
		
		return olcontactlist_to_abcontactlist($cl);
	}

}

//GMX
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["gmx.net"] = 'GmxImporter';
$_DOMAIN_IMPORTERS["gmx.de"] = 'GmxImporter';
$_DOMAIN_IMPORTERS["gmx.at"] = 'GmxImporter';
$_DOMAIN_IMPORTERS["gmx.ch"] = 'GmxImporter';
$_DOMAIN_IMPORTERS["gmx.eu"] = 'GmxImporter';


?>
