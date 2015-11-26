<?php 
require_once( "../vendor/autoload.php" );
require_once("utils.php");
require_once("insertRDF.php");
$data = json_decode(file_get_contents("php://input"),true) ;

$name1 = $data['newname'];
$email1 = $data['newemail'];
$key1 = $data['newpass'];
$passkey1 = md5($key1);

function CreateRegister($name,$email,$passkey){
$PREFIXRegister ="PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>\nPREFIX foaf: <http://xmlns.com/foaf/0.1/>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX vcard: <http://www.w3.org/2006/vcard/ns#>\nPREFIX schema: <http://schema.org/>";
$Register= $PREFIXRegister."
INSERT DATA{
	".Insert."
	{
		<mailto:$email> a foaf:Person;   
    										foaf:name '$name';
    										schema:email '$email';
    										foaf:openid '$email';
    										vcard:hasKey '$passkey'.
    									}
   }";
$answer = Update($Register);
return $answer;
};
CreateRegister($name1,$email1,$passkey1);
?>