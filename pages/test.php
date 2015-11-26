<?php
require_once("../vendor/autoload.php" );

function UpdateEndPoint(){
	//return "http://localhost:3030/twprova/update";
	return "http://tweb2015.cs.unibo.it:8080/data/update?user=ltw1516&pass=retEEf6!";
}
define("Insert", "GRAPH <http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516>");
function Update($Query){
	try{
		$client = new EasyRdf_Sparql_Client(UpdateEndPoint());
		$client->update($Query);
		return null;
	}
	catch(Exception $ex){
		print $ex;
		return $ex;
	}
}
function CreateResource(){
	$Query = "
	INSERT DATA{
	".Insert."
				{
				  <mailto:admin@ltw1516.it>
        a                          <http://xmlns.com/foaf/0.1/Person> ;
        <http://schema.org/email>  'admin@ltw1516.it' ;
        <http://www.w3.org/2006/vcard/ns#hasKey>
                '21232f297a57a5a743894a0e4a801fc3' ;
        <http://xmlns.com/foaf/0.1/name>
                'admin' ;
        <http://xmlns.com/foaf/0.1/openid>
                'admin' .

				}
	}";
	$delete="
	INSERT DATA{
	".Insert."
				{
				  <mailto:admin@ltw1516.it>
        a                          <http://xmlns.com/foaf/0.1/Person> ;
        <http://schema.org/email>  'admin@ltw1516.it' ;
        <http://www.w3.org/2006/vcard/ns#hasKey>
                '21232f297a57a5a743894a0e4a801fc3' ;
        <http://xmlns.com/foaf/0.1/name>
                'admin' ;
        <http://xmlns.com/foaf/0.1/openid>
                'admin@ltw1516.it' .

				}
	}";
	$answer = Update($delete);
	$answer = Update($Query);
	return $answer;
}
CreateResource();
 ?>