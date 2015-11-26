<?php
ob_start();
require_once("utils.php");

//$data = json_decode(file_get_contents("php://input")) ;
$user = $_GET['user'];
$pass = $_GET['password'];

$pass = md5($pass);
$query = "PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX vcard: <http://www.w3.org/2006/vcard/ns#>
PREFIX schema: <http://schema.org/>  
SELECT ?person ?name ?email ?pass
FROM <".GetGraph.">
WHERE
{?person foaf:openid \"$user\";

vcard:hasKey \"$pass\";
foaf:name ?name;
schema:email ?email.
}";

$result = DirectSELECT($query);
$r = json_decode($result)->results->bindings;
if(count($r) == 0)
	echo "Dati di login non corretti!";
else
{
	$person = json_decode($result)->results->bindings[0]->person->value;
	$email = json_decode($result)->results->bindings[0]->email->value;
	$name = json_decode($result)->results->bindings[0]->name->value;
	setcookie("email", $email, time() + 86400, "/");
	setcookie("name", $name, time() + 86400, "/");
	setcookie("person", $person, time() + 86400, "/");
	echo '';
}
ob_end_flush();
?>