<?php
require_once("../vendor/autoload.php" );
require_once("utils.php");
require_once("readRDF.php");

function clear()
{
	$answer = Update("CLEAR GRAPH <http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516>");
	return $answer;
}
clear();
print "succes";
 ?>