<?php 
require_once("insertRDF.php");
require_once("deleteRDF.php");
$file="ann.json";
$content = file_get_contents($file);
//Creazione di expression
$data = json_decode(file_get_contents("php://input"),true);
$url = $data['link']; 

$mURL = getURL($url); //INDIRIZZO senza nome
$files = explode("/", $url);
$item = $files[count($files) - 1];
$arr = explode(".", $item);
$Work = "";
if(count($arr) > 1)
	for($i = 0; $i < count($arr) - 1; $i++)
		$Work = $Work.$arr[$i];
else
	if(count($arr) > 0)
		$Work = $arr[0];
$mainExp = $Work."_ver1";
///////////////////////////////////////////////////
$vars = explode("|", $content);
foreach($vars as $c){
	print "a";
	$obj = json_decode($c);
	switch($obj->azione->value){
		case "I":
			CreateNewAnnotation($obj);
			break;
		break;
		case "D":
			DELETEAnnotation($obj);
		break;
	}	
}
file_put_contents($file, ""); //Pulisco il file
$read = GetData($url);
file_put_contents("annotation.json", $read);
return;

function DELETEAnnotation($obj){
	global $item, $mURL;
	$id = strrev( (int)strrev( $obj->ann->value ) );
	switch($obj->predicate->value){
		case "http://purl.org/dc/terms/title":
			DeleteTitle($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
			break;
		case "http://purl.org/dc/terms/creator":
			DeleteAuthors($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value, $obj->key->value);
			break;
		case "http://prismstandard.org/namespaces/basic/2.0/doi":
			print DeleteDoi($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
			break;
		case "http://purl.org/spar/fabio/hasPublicationYear":
			DeletePublicationYear($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
			break;
		case "http://purl.org/spar/fabio/hasURL":
			DeleteURL($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
			break;
		case "http://purl.org/spar/cito/cites":
			DeleteCites($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value, $obj->key->value);
			break;
		case "http://schema.org/comment":
			DeleteComment($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
			break;
		case "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes":
			DeleteRethoric($item, $mURL, $obj->label->value, $id, $obj->at->value, $obj->by->value, $obj->id->value, $obj->start->value, $obj->end->value, $obj->subject->value, $obj->object->value, $obj->bLabel->value);
		break;
	}
}
function CreateNewAnnotation($obj){
	
	global $mainExp, $item, $mURL;
	$Exp = $mainExp;
	try{
		$mE = $obj->subject->value;
		if($mE == "cited") 
			$Exp."_cited".GetCiteIndex($obj->subject->value);
		else
			if(strpos(substr($mE, -8), "cited") !== false){
				$cit = substr($mE, strrpos($mE, "_"));
				$Exp.= "_".$cit;
		}
	}
	catch(Exception $ex){
	}
	
	
	switch($obj->predicate->value){
		case "http://purl.org/dc/terms/title":
			CreateTitle($Exp, $item, Normalize(html_entity_decode($obj->object->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL,  Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://purl.org/dc/terms/creator":
			print CreateAuthors($Exp, $item, Normalize(html_entity_decode($obj->key->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL, Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://prismstandard.org/namespaces/basic/2.0/doi":
			CreateDoi($Exp, $item, Normalize(html_entity_decode($obj->object->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL, Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://purl.org/spar/fabio/hasPublicationYear":
			CreatePublicationYear($Exp, $item, Normalize(html_entity_decode($obj->object->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL, Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://purl.org/spar/fabio/hasURL":
			CreateUrl($Exp, $item, Normalize(html_entity_decode($obj->object->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL, Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://purl.org/spar/cito/cites":
			$citExpression = $Exp."_cited".GetCiteIndex($obj->subject->value);
			CreateCities("", $citExpression, $Exp, $item, Normalize(html_entity_decode($obj->key->value)), $obj->id->value,  $obj->start->value, $obj->end->value, $mURL,  Normalize(html_entity_decode($obj->bLabel->value)));
			break;
		case "http://schema.org/comment":
			CreateComment($Exp, $item, Normalize(html_entity_decode($obj->object->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL);
			break;
		case "http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#denotes":
			CreateRethoric($Exp, $item, DecodeRethoric($obj->object->value), Normalize(html_entity_decode($obj->bLabel->value)), $obj->start->value, $obj->end->value, $obj->id->value, $mURL);
		break;
	}
}
function DecodeRethoric($what){
	switch($what){
		case "http://purl.org/spar/deo/Introduction": return "deo:Introduction";
		case "http://www.w3.org/2004/02/skos/core#Concept": return "skos:Concept";
		case "http://salt.semanticauthoring.org/ontologies/sro#Abstract": return "sro:Abstract";
		case "http://purl.org/spar/deo/Materials": return "deo:Materials";
		case "http://purl.org/spar/deo/Methods": return "deo:Methods";
		case "http://purl.org/spar/deo/Results": return "deo:Results";
		case "http://salt.semanticauthoring.org/ontologies/sro#Discussion": return "sro:Discussion";
		case "http://salt.semanticauthoring.org/ontologies/sro#Conclusion": return "sro:Conclusion";
	}
	return "";
}
?>