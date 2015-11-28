<?php
require_once('utils.php');
$data = json_decode(file_get_contents("php://input"),true) ; //Variabili passati tramite POST
$url = $data['link']; //INDIRIZZO DA LEGGERE
if(strtolower(substr($url, 0, 3)) == "www") $url = "http://".$url;
$mURL = getURL($url); //INDIRIZZO senza nome
$doc = new DOMDocument();
libxml_use_internal_errors(true);
try{
	$doc->loadHTMLFile($url); //Carico il file
}
catch(Exception $ex){
	echo "Nessuna connesione a Internet.";
	exit;
}

if(strtolower(substr($url, 0, 3)) == "www") $url = "http://".$url;

$xpath = new DOMXPath($doc); 
$isOUR = "";
if(strpos($url, "www.dlib.org") !== false) $isOUR = "dlib";
elseif(strpos($url, "rivista-statistica.unibo.it") !== false) $isOUR = "RS";
elseif (strpos($url, "almatourism.unibo.it") !== false) $isOUR = "AM";
elseif (strpos($url, "antropologiaeteatro.unibo.it") !== false) $isOUR = "AT";

try
{
	$doc = AddIDs($doc->getElementsByTagName('html')->item(0), "");
}
catch(Exception $e){
	echo "Indirizzo non raggiungibile.";
	exit;
}

switch($isOUR){
	case "dlib": case "dlib2":
		$contentTable = $doc->getElementsByTagName('table')->item(8); // Prendo la tabella di posizione 8
	break;
	case "RS": case "AM": case "AT": 
		$contentTable = $xpath->query("//*[@id='div1_div2_div2_div3']", $doc)->item(0);
	break;
	default:
		$contentTable = $xpath->query("//*[@id='div1_div2_div2_div3']", $doc)->item(0);
		if($contentTable == null)
			$contentTable = $doc->getElementsByTagName('body')->item(0);
	break;
}


if($contentTable != null)
{
	$domElemsToRemove = array(); 
	$scripts = $contentTable->getElementsByTagName('script');
	foreach ($scripts as $script){$domElemsToRemove[] = $script; }
	foreach( $domElemsToRemove as $domElement ) {$domElement->parentNode->removeChild($domElement);  }
	foreach($xpath->query('//a[@href][not(starts-with(@href,"#"))]',$contentTable) as $link) 
	{$link->setAttribute('target','_blank'); }
	$xml = $contentTable->ownerDocument->saveHTML($contentTable);
	$xml = preg_replace('/((?:href|src) *= *[\'"](?!#)(?!(http|ftp|\/\/)))/i', "$1$mURL", $xml);
	echo $xml;
}
else
	echo "URI NON SOPPORTATO.";
?>