<?php
require_once("../vendor/autoload.php" );
require_once("utils.php");
$index = 0;
function SearchIfExists($uri, $from = GetGraph){
	$Prefix = "PREFIX frbr: <http://purl.org/vocab/frbr/core#> ";
	$Query = "SELECT DISTINCT * FROM <$from> WHERE {<$uri> ?a frbr:item.}";
	$result = DirectSELECT($Prefix.$Query);
	$result = json_decode($result)->results->bindings;
	return count($result) > 0;
}
function GetCiteIndex($expression){
	$query = "SELECT ?cite
			FROM <http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516>
			WHERE{
				?ann <http://www.w3.org/ns/oa#hasBody> ?body.
				?body 	<http://www.w3.org/1999/02/22-rdf-syntax-ns#subject> <$expression>
						<http://www.w3.org/1999/02/22-rdf-syntax-ns#predicate> <http://purl.org/spar/cito/cites>;
						<http://www.w3.org/1999/02/22-rdf-syntax-ns#object> ?cite.
			}";
	$result = DirectSELECT($query);
	$result = json_decode($result)->results->bindings;
	$number = $result[count($result) - 1]->cite->value;
	return (1+(int)strrev((int)strrev( $number )));
}
function countAnnotations(){
	$query = "SELECT ?ann
			FROM <http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516>
			WHERE{
				?ann <http://www.w3.org/ns/oa#hasBody> ?body.
				?body 	<http://www.w3.org/1999/02/22-rdf-syntax-ns#subject> ?exp;
						<http://www.w3.org/1999/02/22-rdf-syntax-ns#predicate> ?predicate;
						<http://www.w3.org/1999/02/22-rdf-syntax-ns#object> ?cite.
			}";

	$result = DirectSELECT($query);
	$array = json_decode($result)->results->bindings;
	$final = 0;
	foreach($array as $val){
		$temp = (int)strrev((int)strrev($val->ann->value ));
		if($temp > $final)
			$final = $temp;
	}
	return $final + 1;
}
?>
