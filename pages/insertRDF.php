<?php
require_once("../vendor/autoload.php" );
require_once("utils.php");
require_once("readRDF.php");

$annotation_ID = countAnnotations();

function removeNewLine($str){
	$str = str_replace("\t", '', $str);
	$str = str_replace("\r", '', $str);
	$str = str_replace("\n", '', $str);
	return $str;
}

function CreateResource($uri, $name, $sName, $vName, $title){
	$Prefix = "PREFIX dlib: <$uri> 
			   PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>
			   PREFIX frbr: <http://purl.org/vocab/frbr/core#>
			   PREFIX fabio: <http://purl.org/spar/fabio/>
		       PREFIX foaf: <http://xmlns.com/foaf/0.1/>";
	$Query = $Prefix."
	INSERT DATA{
	".Insert."
				{
				  dlib:$name a frbr:item; a fabio:item.
				  dlib:$sName a fabio:Work; a fabio:work; fabio:hasPortrayal dlib:$name.
				  dlib:$sName frbr:realization dlib:$vName.
				  dlib:$vName a fabio:Expression; foaf:name \"$title\"; a frbr:expresion; fabio:hasRepresentation dlib:$name.
				}
	}";
	$answer = Update($Query);
	return $answer;
}
function CreateAuthors($expression, $item, $author, $start, $end, $target, $uri, $label="asdf"){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$author = Normalize($author);
	$author = removeNewLine($author);
	$mail = getMail();
	$time = getTime();
	$aut = str_replace(".", "", $author);
	$aut = trim($aut);
	$n = explode(' ', $aut);
	$rsch = strtolower(substr($n[0], 0, 1)."-".$n[count($n) - 1]);
	if($label == "asdf") $label = "Un autore del documento è $author";
	$Prefix = "
			PREFIX oa: <http://www.w3.org/ns/oa#>
			PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			PREFIX dlib: <$uri> 
			PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/person/>
			PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			PREFIX dcterms: <http://purl.org/dc/terms/>
			PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>";
	$Query = $Prefix."
		INSERT DATA {
		".Insert."
		{
		  <ann-autore$annotation_ID> a oa:Annotation ; 
									rdfs:label \"Autore\";
									oa:annotatedBy <$mail> ;
								    oa:annotatedAt \"$time\";
									oa:hasBody <body-autore$annotation_ID>;
								    oa:hasTarget  <target-autore$annotation_ID>.
		<target-autore$annotation_ID> a oa:SpecificResource;
									oa:hasSource dlib:$item ;
									oa:hasSelector <selector-autore$annotation_ID>.
		<selector-autore$annotation_ID> a oa:FragmentSelector ;
									  rdf:value \"$target\" ;
									  oa:start \"$start\"^^xsd:nonNegativeInteger ;
									  oa:end \"$end\"^^xsd:nonNegativeInteger.
		 <body-autore$annotation_ID> a rdf:Statement;
									rdf:subject dlib:$expression;
									rdf:predicate dcterms:creator;
									rdf:object rsch:$rsch;
									rdfs:label \"$label\"^^xsd:string.
							
		rsch:$rsch a foaf:Person;
				  rdfs:label \"$author\"^^xsd:string .
		}}";
	$answer = Update($Query);
	return $answer;
}
function CreateTitle($expression, $item, $title, $start, $end, $target, $uri, $label = "Questo è il titolo del articolo."){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$title = Normalize($title);
	$title = removeNewLine($title);
	$mail = getMail();
	$time = getTime();
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>
			   PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			   PREFIX dlib: <$uri> 
			   PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			   PREFIX dcterms: <http://purl.org/dc/terms/>
			   PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>";
	$Query = $Prefix."
			INSERT DATA{
			".Insert."
			{
			  <ann-titolo$annotation_ID> a oa:Annotation ; 
										rdfs:label \"Titolo\";
										oa:annotatedBy <$mail> ;
										oa:annotatedAt \"$time\" ;
										oa:hasBody <body-titolo$annotation_ID>;
			  							oa:hasTarget  <target-titolo$annotation_ID>. 
				<target-titolo$annotation_ID> a oa:SpecificResource;
											oa:hasSource dlib:$item ;
											oa:hasSelector <selector-titolo$annotation_ID>.
				<selector-titolo$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$target\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-titolo$annotation_ID> a rdf:Statement;
										rdf:subject dlib:$expression;
										rdf:predicate dcterms:title;
										rdf:object \"$title\";
										rdfs:label	\"$label\".
									}
								}";
	$answer = Update($Query);
	return $answer;
}
function CreatePublicationYear($expression, $item, $year, $start, $end, $target, $uri, $label="asdf"){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	if(!is_numeric($year)) return;
	if($label == "asdf") $label = "L'anno di pubblicazione è $year";
	$mail = getMail();
	$time = getTime();
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>
			   PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			   PREFIX dlib: <$uri> 
			   PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/person/>
			   PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			   PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			   PREFIX rsc: <http://vitali.web.cs.unibo.it/raschietto/>
			   PREFIX fabio: <http://purl.org/spar/fabio/>";
	$Query = $Prefix."
			INSERT DATA{
			".Insert."
			{
			  <ann-annodp$annotation_ID> a oa:Annotation ; 
										rdfs:label \"Anno di pubblicazione\";
									  	oa:annotatedBy <$mail> ;
										oa:annotatedAt \"$time\";
			  							oa:hasTarget <target-annodp$annotation_ID>;
			  							oa:hasBody <body-annodp$annotation_ID>.
			<target-annodp$annotation_ID>	a oa:SpecificResource;
											oa:hasSource dlib:$item ;
											oa:hasSelector <selector-annodp$annotation_ID>.
				<selector-annodp$annotation_ID> a oa:FragmentSelector ;
											  rdf:value \"$target\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-annodp$annotation_ID> a rdf:Statement;
										rdf:subject dlib:$expression;
										rdf:predicate fabio:hasPublicationYear;
										rdf:object \"$year\"^^xsd:date;
										rdfs:label \"$label\".
									}
								}";
	$answer = Update($Query);
	return $answer;
}
function CreateDoi($expression, $item, $doi, $start, $end, $target, $uri, $label = "Questo è un DOI"){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$doi = Normalize($doi);
	$doi = removeNewLine($doi);
	$mail = getMail();
	$time = getTime();
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>
			   PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			   PREFIX dlib: <$uri> 
			   PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/person/>
			   PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
			   PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
			   PREFIX prism: <http://prismstandard.org/namespaces/basic/2.0/>";
	$Query = $Prefix."
					INSERT DATA
					{
					".Insert."
					{
						<ann-doi$annotation_ID> a oa:Annotation ;
											  rdfs:label \"DOI\";
											  oa:annotatedBy <$mail> ;
											  oa:annotatedAt \"$time\";
											  oa:hasTarget <target-doi$annotation_ID>;
											  oa:hasBody <body-doi$annotation_ID>.
						<target-doi$annotation_ID>  a oa:SpecificResource;
													  oa:hasSource dlib:$item ;
													  oa:hasSelector <selector-doi$annotation_ID>.
						<selector-doi$annotation_ID> a oa:FragmentSelector ;
													rdf:value \"$target\" ;
													oa:start \"$start\"^^xsd:nonNegativeInteger ;
													oa:end \"$end\"^^xsd:nonNegativeInteger.
						<body-doi$annotation_ID> a rdf:Statement ;
												  rdf:subject dlib:$expression ; 
												  rdf:predicate prism:doi ;
												  rdf:object \"$doi\"^^xsd:string;
												  rdfs:label \"$label\".
												}
											}";
	$answer = Update($Query);
	return $answer;
}
function CreateUrl($expression, $item, $url, $start, $end, $target, $uri, $label="Questo è un indirizzo"){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$mail = getMail();
	$time = getTime();
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>
				PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				PREFIX dlib: <$uri>
				PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				PREFIX fabio: <http://purl.org/spar/fabio/>";
	$Query = "INSERT DATA {
	".Insert."
	{
	  <ann-url$annotation_ID> a oa:Annotation ;
								  rdfs:label \"URL\";
								  oa:annotatedBy <$mail> ;
								  oa:annotatedAt \"$time\";
								  oa:hasTarget <target-url$annotation_ID>;
								  oa:hasBody <body-url$annotation_ID>.
	<target-url$annotation_ID> a oa:SpecificResource;
						          oa:hasSource dlib:$item ;
						          oa:hasSelector <selector-url$annotation_ID>. 
	<selector-url$annotation_ID> a oa:FragmentSelector ;
				                    rdf:value \"$target\" ;
				                    oa:start \"$start\"^^xsd:nonNegativeInteger ;
				                    oa:end \"$end\"^^xsd:nonNegativeInteger.
	<body-url$annotation_ID> a rdf:Statement ;
						          rdf:subject dlib:$expression ;
						          rdf:predicate fabio:hasURL ;
						          rdf:object \"$url\"^^xsd:anyURL;
						          rdfs:label \"$label\"^^xsd:string.
	}}";
		$answer = Update($Prefix.$Query);
		return $answer;
}
function CreateCities($title, $citExpression,$expression, $item, $cite, $target, $start, $end, $uri, $label = "asdf"){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$title = Normalize($title);
	$title = removeNewLine($title);
	$cite = Normalize($cite);
	$cite = removeNewLine($cite);
	if($label == "asdf") $label = "Questo articolo cita $title";
	
	$mail = getMail();
	$time = getTime();
	$Prefix =  "PREFIX cito: <http://purl.org/spar/cito/>
				PREFIX oa: <http://www.w3.org/ns/oa#>
				PREFIX dlib: <$uri>
				PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>";
	$Query = $Prefix."
			INSERT DATA {
			".Insert."
				{
				<ann-cito$annotation_ID> a oa:Annotation;
											rdfs:label \"Citazione\";
											oa:hasTarget <target-cito$annotation_ID>;
											oa:annotatedBy <$mail> ;
											oa:annotatedAt \"$time\";
											oa:hasBody <body-cito$annotation_ID>.
				<target-cito$annotation_ID> a oa:SpecificResource;
											oa:hasSource dlib:$item ;
											oa:hasSelector <selector-cito$annotation_ID>. 
				<selector-cito$annotation_ID> a oa:FragmentSelector ;
												rdf:value \"$target\";
												oa:start \"$start\"^^xsd:nonNegativeInteger ;
												oa:end \"$end\"^^xsd:nonNegativeInteger.
				<body-cito$annotation_ID> a rdf:Statement ; 
											rdf:subject dlib:$expression;
											rdf:predicate cito:cites; rdf:object dlib:$citExpression ;
											rdfs:label \"$label\".
				dlib:$citExpression rdfs:label \"$cite\".
			}
		}";
	$answer = Update($Query);
	return $answer;
}
function CreateComment($expression, $item, $comment, $start, $end, $target, $uri){
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$mail = getMail();
	$time = getTime();
	$subject =  $item."#".$target;
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>
				PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
				PREFIX dlib: <$uri>
				PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				PREFIX schema: <http://schema.org/>";
	$Query = "INSERT DATA {
	".Insert."
	{
		<ann-commento$annotation_ID> a oa:Annotation;
								  rdfs:label      \"Commento\";
								  oa:annotatedAt  \"$time\";
								  oa:annotatedBy  <$mail> ;
								  oa:hasTarget <target-commento$annotation_ID>;
								  oa:hasBody <body-commento$annotation_ID>.
		<target-commento$annotation_ID> a oa:SpecificResource;
										oa:hasSource    dlib:$item;
	                					oa:hasSelector <selector-commento$annotation_ID>.
	    <selector-commento$annotation_ID> a oa:FragmentSelector ;
	                               rdf:value \"$target\" ;
	                               oa:end \"$start\"^^xsd:nonNegativeInteger ;
								   oa:start \"$end\"^^xsd:nonNegativeIntegeroa.
		<body-commento$annotation_ID> a rdf:Statement ;
						              rdf:subject    <dlib:$subject>;
						              rdf:predicate  schema:comment;
						              rdf:object \"$comment\"^^xsd:string;
						          }
						      }";
	$answer = Update($Prefix.$Query);
		return $answer;
}
function CreateRethoric($expression, $item, $object, $label, $start, $end, $target, $uri){
	//object: deo:Introduction, skos:Concept, sro:Abstract, deo:Materials, deo:Methods, deo:Results, sro:Discussion, sro:Conclusion
	global $annotation_ID;
	$annotation_ID = $annotation_ID + 1;
	$mail = getMail();
	$time = getTime();
	$subject =  $item."#".$target;
	$Prefix = "  PREFIX sem: <http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#> 
				PREFIX schema: <http://schema.org/>
				PREFIX oa: <http://www.w3.org/ns/oa#> 
				PREFIX dlib: <$uri> 
				PREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/> 
				PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> 
				PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
				PREFIX deo: <http://purl.org/spar/deo/>
				PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
				PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
				PREFIX sro: <http://salt.semanticauthoring.org/ontologies/sro#>";
	$Query = " INSERT DATA {
	".Insert."
	{
		<ann-retorica$annotation_ID> a oa:Annotation ; 
									rdfs:label \"Retorica\";
	  								oa:hasTarget <target-retorica$annotation_ID>;
	  								oa:annotatedBy <$mail> ;
									oa:annotatedAt \"$time\";
									oa:hasBody <body-retorica$annotation_ID>.
	    <target-retorica$annotation_ID> a oa:SpecificResource;
									    oa:hasSource dlib:$item ;
									    oa:hasSelector <selector-retorica$annotation_ID>.
		<selector-retorica$annotation_ID> a oa:FragmentSelector ;
									      rdf:value \"$target\" ;
									      oa:start \"$start\"^^xsd:nonNegativeInteger ;
									      oa:end \"$end\"^^xsd:nonNegativeInteger.
		<body-retorica$annotation_ID> a rdf:Statement ; 
							         rdf:subject <dlib:$subject>;
							         rdf:predicate sem:denotes;
							         rdf:object $object;
							         rdfs:label \"$label\".
							     }
							 }";
	$answer = Update($Prefix.$Query);
	return $answer;
}

function InsertDlib($content, $uri, $item, $ArtTitle){
//Work = Nome Senza Estensione
//Exp = Nome Con _ver1
$arr = explode(".", $item);
$Work = "";
if(count($arr) > 1)
	for($i = 0; $i < count($arr) - 1; $i++)
		$Work = $Work.$arr[$i];
else
	if(count($arr) > 0)
		$Work = $arr[0];
$Exp = $Work."_ver1";
	
$get = CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
if($get != null) return;
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
$doc = new DOMDocument();	
$doc->loadHTML($content);
$xpath = new DOMXPath($doc);
//Titolo dell'articolo
$title = $doc->getElementsByTagName('h3')->item(1);
if($title != NULL){
	CreateTitle($Exp, $item, $title->nodeValue, 0, strlen($title->nodeValue), $title->getAttribute('id'), $uri);
}

//Autori dell'articolo
//$aut = $xpath->query("//table[@cellpadding='6']//b[1]");

$audID = 'form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p2';
$autArray = $xpath->query("//p[@id='$audID']/text()");
$autStart = 0;
function Authors($autori, $del_Len, $autStartTmp, $Exp, $item, $autStart, $audID, $uri){
	foreach($autori as $node){
		if(is_array($node) && count($node) > 1) {
			$autStartTmp=Authors($node, 1, $autStartTmp, $Exp, $item, $autStart, $audID, $uri) + 5; 
			continue;}
		if(is_array($node)) {
			$autStartTmp=Authors($node, 5, $autStartTmp, $Exp, $item, $autStart, $audID, $uri) + 5; 
			continue;}
			
		$end = 0;
		$autStartTmp = $autStartTmp + (strlen($node) - strlen(ltrim($node)));
		$end = strlen($node) - strlen(rtrim($node));
		$node = trim($node);
		$node = Normalize($node);
		//print $node." ".$autStart.$autStartTmp."<br>";
		CreateAuthors($Exp, $item, $node, $autStart + $autStartTmp, $autStart + $autStartTmp + strlen($node), $audID, $uri);
		$autStartTmp = $autStartTmp + strlen($node) + $end + $del_Len;
	}
	return $autStartTmp - $del_Len;
}
for($i = 0; $i < $autArray->length; $i++){
	$aut = $autArray->item($i);
	if($i != 0 && (strlen($aut->nodeValue) - strlen(ltrim($aut->nodeValue))) != 2) {
		$autStart = $autStart + strlen($aut->nodeValue);
		continue;}
	if($Work == "11kristianto")
	{
		$node = $aut->nodeValue;
		$node = Normalize($node);
		$pos1 = strpos($node, " ");
		$pos2 = strpos($node, " ", $pos1 + 1);
		if($i != 0)
			$node = substr($node, 0, $pos2);
		else
		{
			$pos3 = strpos($node, " ", $pos2 + 1);
			$node = substr($node, 0, $pos3);
		}
		$node = substr($node, 0, strlen($node) - 1);
		CreateAuthors($Exp, $item, $node, $autStart, $autStart + strlen($node), $audID, $uri);
	}
	else
	{
		$aut = MultiDelimiter(array(" and ", ","), $aut->nodeValue);
		Authors($aut, 5, 0, $Exp, $item, $autStart, $audID, $uri);
	}
	$autStart = $autStart + strlen($autArray->item($i)->nodeValue);
	$i++;
	$autStart = $autStart + strlen($autArray->item($i)->nodeValue);
}

//Abstract
$astratto = $xpath->query("//h3[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_h33']/following-sibling::p[1]")->item(0);
if($astratto != null)
CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);

//URL
$url = $xpath->query("//a[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p3_a1']")->item(0);
if($url != null)
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));

$url = $xpath->query("//a[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p1_a1']")->item(0);
if($url != null)
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));



//Anno di pubblicazione dell'articolo
$target = $xpath->query("//p[1]")->item(0);
$AllStr = $xpath->query("//p[1]//text()[(following::br)]")->item(0);
$publicationYear = $AllStr != null ? substr($AllStr->nodeValue, -4) : "";
CreatePublicationYear($Exp, $item, $publicationYear, strlen($AllStr->nodeValue) - 4, strlen($AllStr->nodeValue), $target->getAttribute('id'), $uri); 

//DOI dell'articolo
$target = $xpath->query("//p[2]")->item(0);
$target_text = Normalize($target->nodeValue);
$aux = $xpath->query("//p[2]//text()[(preceding::br)]");
$doi = Normalize(substr($aux->item($aux->length - 1)->nodeValue, 4));
CreateDoi($Exp, $item, $doi, strpos($target_text, $doi), strpos($target_text, $doi) + strlen($doi), $target->getAttribute('id'), $uri);

//Citazioni dell'articolo
$cities = $xpath->query("//h3[text()='References'][1]/following-sibling::p[count(.|//h3[text()='About the Authors'][1]/preceding-sibling::p)=count(//h3[text()='About the Authors'][1]/preceding-sibling::p)]");
$i = 1;
file_put_contents("log.txt", $cities->length);
foreach($cities as $cite){
	$citExp = $Exp."_cited".$i;
	$title = $cite->getElementsByTagName('i');
	$title = $title->length > 0 ? $title->item(0) : NULL;
	if($title != NULL){
		CreateCities($title->nodeValue, $citExp, $Exp, $item, $cite->nodeValue, $cite->getAttribute('id'), 0, strlen(Normalize($cite->nodeValue)), $uri);
		CreateTitle($citExp, $item, $title->nodeValue, 0, strlen(Normalize($title->nodeValue)), $title->getAttribute('id'), $uri);
		$DOI = $cite->getElementsByTagName('a');
		$DOI = $DOI->length > 1 ? $DOI->item(1) : NULL;
		if($DOI != NULL)
			CreateDoi($citExp, $item, $DOI->nodeValue, 0, strlen($DOI->nodeValue), $DOI->getAttribute('id'), $uri);
		//Anno
		$node = Normalize($cite->nodeValue);
		$anni = explode(",", $node);
		$positionStart = 0;
		foreach($anni as $anno){
			if((strlen(trim($anno)) == 4) || (strlen(trim($anno)) == 5 && substr(trim($anno), -1) == ".")){
				//manipulazione anno
				$positionStart += strlen($anno) - strlen(ltrim($anno));
				$anno = trim($anno);
				if(substr($anno, -1) == ".")
					$anno = substr($anno, -1);
				//Inserimento anno
				CreatePublicationYear($citExp, $item, $anno, $positionStart, $positionStart + strlen($anno), $cite->getAttribute('id'), $uri); 
				break;
			}
			else
				$positionStart += strlen($anno) + 1; //+1 per la virgola
		}	
		//Autori
		$valoriSplit = explode(" ", $node);
		$save = false;
		$authorToSave = "";
		$positionStart = 0;
		$positionEnd = 0;
		$span = 0;
		foreach($valoriSplit as $autori){
			// se sono 2 caratteri e l'ultimo = '.', oppure se sono 5 caratteri e il 3 = '-' e ultimo = '.'
			if((strlen(trim($autori)) == 2 && substr(trim($autori), -1) == ".") || (strlen(trim($autori)) == 5 && substr(trim($autori), -1) == "." && substr(trim($autori), 2, 1) == "-")){
				$save = true;
				$authorToSave .= $autori." ";
			}
			else{
				if($save == true){
					$authorToSave = $authorToSave.$autori;
					if(substr($authorToSave,-1) == "," || substr($authorToSave,-1) == "."){
						$authorToSave = substr($authorToSave, 0, strlen($authorToSave) - 1);
						$span = 1; //la virgola o il punto
					}
					$positionEnd = $positionStart + strlen($authorToSave);
					CreateAuthors($citExp, $item, $authorToSave, $positionStart, $positionEnd, $cite->getAttribute('id'), $uri);
					//print $positionStart." ".$positionEnd." ".$authorToSave."<br>";
					$positionStart = $positionEnd + $span + 1; //lo spazio
					$span = 0;
					$save = false;
					$authorToSave = "";
				}
				else
					$positionStart += strlen($autori) + 1; //Per lo split di spazio
			}
		}
	}
	$i++;	
}
}

function InsertJournals($content, $uri, $item, $ArtTitle){
//Work = Nome Senza Estensione
//Exp = Nome Con _ver1
$Work = $item;
$Exp = $Work."_ver1";
	
$get = CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
if($get != null) return;
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
$doc = new DOMDocument();	
$doc->loadHTML($content);
$xpath = new DOMXPath($doc);

//Titolo dell'articolo
$title = $doc->getElementsByTagName('h3')->item(0);
if($title != NULL){
	CreateTitle($Exp, $item, $title->nodeValue, 0, strlen($title->nodeValue), $title->getAttribute('id'), $uri);
}

//Autori articolo
$aut= $xpath->query("//em[@id='div1_div2_div2_div3_div3_em1']")->item(0);
$autTarget = $aut->getAttribute('id');
$aut = explode(",", $aut->nodeValue);
$start = 0;
foreach($aut as $node){
	$var = trim($node);
	CreateAuthors($Exp, $item, $var, $start, $start + strlen($var), $autTarget, $uri);
	$start += strlen($var) + 2;
}


//Abstract
$astratto = $xpath->query("//p[@id='div1_div2_div2_div3_div4_div1_p1']")->item(0);
if($astratto != null)
CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);
else{
	$astratto = $xpath->query("//div[@id='div1_div2_div2_div3_div4_div1']")->item(0);
	CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);
}


//URL
$url = $xpath->query("//a[@id='div1_div2_div2_div3_div6_a1']")->item(0);
if($url != null)
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));



//Anno di pubblicazione dell'articolo
$target = $xpath->query("//p[@id='div1_div2_div2_div3_p3']")->item(0);
$AllStr=$target;
$publicationYear = substr($AllStr->nodeValue, -18, -14);
CreatePublicationYear($Exp, $item, $publicationYear, strlen($AllStr->nodeValue) - 18, strlen($AllStr->nodeValue) - 14, $target->getAttribute('id'), $uri); 


//DOI dell'articolo
$target = $xpath->query("//a[@id='div1_div2_div2_div3_a1']")->item(0);
$doi=$target->nodeValue;
CreateDoi($Exp, $item, $doi, 0, strlen($doi), $target->getAttribute('id'), $uri);

//Citazioni dell'articolo
$cities=$xpath->query("//div[@id='div1_div2_div2_div3_div7_div1']/p");
$i=1;
foreach($cities as $cite){
	$citExp=$Exp."_cited".$i;
	$parentesi=strpos($cite->nodeValue, ')');
	if(!is_numeric(substr($cite->nodeValue, $parentesi - 1, 1))) $parentesi -= 1;
	$year=substr($cite->nodeValue,$parentesi-4,4); 							//fare il caso di (2004b)
	$intera=trim(substr($cite->nodeValue, $parentesi+3, strlen($cite->nodeValue)));
	$endtitlevirgola=strpos($intera, ',');
	$endtitlepunto=strpos($intera, '.');
	if($endtitlevirgola<$endtitlepunto)
		$title=substr($intera, 0, $endtitlevirgola);
	else
		$title=substr($intera, 0, $endtitlepunto); 		

	
	$fineautore=strpos($cite->nodeValue, '(');    //D. J. DONNELL, A. BUJA, W. STUETZLE (1994). 
	if($fineautore){
		$fineautore=$fineautore-2;
		$autori=substr($cite->nodeValue,0,$fineautore+1);
		$arrayautori = explode(",", $autori);
		$start=0;
		foreach($arrayautori as $node){  //PROVARE: per la posizione senza usare start cerca $node nella stringa totale e poi sottrai la pos - strlen $node
			$var = trim($node); 
			print($var);
			print("|\n");
			print($start + strlen($var));
			CreateAuthors($citExp, $item, $var, $start, $start + strlen($var), $cite->getAttribute('id'), $uri);
			$start += strlen($var) + 2;
			}
	}	
	
	if($title!=NULL){
		CreateCities($title, $citExp, $Exp, $item, $cite->nodeValue, $cite->getAttribute('id'), 0, strlen($cite->nodeValue), $uri);
		CreateTitle($citExp, $item, $title, strpos($cite->nodeValue, $title), strlen($title) + strpos($cite->nodeValue, $title), $cite->getAttribute('id')/*correggere*/, $uri);
		CreatePublicationYear($citExp, $item, $year,$parentesi-4, $parentesi, $cite->getAttribute('id'),$uri);
	
	}
	$i++;
}


}
function InsertJournalsAM($content, $uri, $item, $ArtTitle){	//montesquieu
//Work = Nome Senza Estensione
//Exp = Nome Con _ver1
$Work = $item;
$Exp = $Work."_ver1";

$get = CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
if($get != null) return;
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
$doc = new DOMDocument();	
$doc->loadHTML($content);
$xpath = new DOMXPath($doc);

//Titolo dell'articolo
$title = $doc->getElementsByTagName('h3')->item(0);
if($title != NULL){
	 CreateTitle($Exp, $item, $title->nodeValue, 0, strlen($title->nodeValue), $title->getAttribute('id'), $uri);
}

//Autori articolo
$aut= $xpath->query("//em[@id='div1_div2_div2_div3_div3_em1']")->item(0);
$autTarget = $aut->getAttribute('id');
$var=trim($aut->nodeValue);
CreateAuthors($Exp, $item, $var, 0, strlen($var), $autTarget, $uri);



//Abstract
$astratto = $xpath->query("//div[@id='div1_div2_div2_div3_div4_div1']")->item(0);
CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);



//URL
$url = $xpath->query("//a[@id='div1_div2_div2_div3_div6_a1']")->item(0);
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));
$url = $xpath->query("//a[@id='div1_div2_div2_div3_div9_div2_p1_a1']")->item(0);
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));
$url = $xpath->query("//a[@id='div1_div2_div2_div3_div9_div2_p1_a2']")->item(0);
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));




//Anno di pubblicazione dell'articolo
/*
$target=$xpath->query("//div[@id='div1_div2_div2_div3']")->item(0);
$ab = $xpath->query("//div[@id='div1_div2_div2_div3']//text()[(preceding::br[@id='div1_div2_div2_div3_br5'])]")->item(0);
//$abb= $xpath->query("//*[@id="div1_div2_div2_div3"]/text()[2]")->item(0);
$str="2015";
$pos=strrpos($target->nodeValue, $str); //sempre ultimo 2015 nel testo
CreatePublicationYear($Exp, $item, $str, $pos-4, $pos, $target->getAttribute('id'), $uri); 
*/

//DOI dell'articolo
$target = $xpath->query("//a[@id='div1_div2_div2_div3_a1']")->item(0);
$doi=$target->nodeValue;
CreateDoi($Exp, $item, $doi, 0, strlen($doi), $target->getAttribute('id'), $uri);
}



function InsertJournalsAT($content, $uri, $item, $ArtTitle){
//Work = Nome Senza Estensione
//Exp = Nome Con _ver1
$Work = $item;
$Exp = $Work."_ver1";

$get = CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
if($get != null) return;
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
$doc = new DOMDocument();	
$doc->loadHTML($content);
$xpath = new DOMXPath($doc);

//Titolo dell'articolo
$title = $doc->getElementsByTagName('h3')->item(0);
if($title != NULL){
	 CreateTitle($Exp, $item, $title->nodeValue, 0, strlen($title->nodeValue), $title->getAttribute('id'), $uri);
}

//Autori articolo
$aut= $xpath->query("//em[@id='div1_div2_div2_div3_div3_em1']")->item(0);
$autTarget = $aut->getAttribute('id');
$aut = explode(",", $aut->nodeValue);
$start = 0;
foreach($aut as $node){
	$var = trim($node);
	 CreateAuthors($Exp, $item, $var, $start, $start + strlen($var), $autTarget, $uri);
	$start += strlen($var) + 2;
}

//Abstract
$astratto = $xpath->query("//div[@id='div1_div2_div2_div3_div4_div1']")->item(0);
if($astratto != null)
CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);



//URL
$url = $xpath->query("//a[@id='div1_div2_div2_div3_div6_a1']")->item(0);
if($url != null)
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));



//Anno di pubblicazione dell'articolo
$ab = $xpath->query("//p[@id='div1_div2_div2_div3_p1']")->item(0);
$publicationYear = substr($ab->nodeValue, -4, 4);
 CreatePublicationYear($Exp, $item, $publicationYear, strlen($ab->nodeValue) - 4, strlen($ab->nodeValue), $ab->getAttribute('id'), $uri); 


//DOI dell'articolo
$target = $xpath->query("//a[@id='div1_div2_div2_div3_a1']")->item(0);
$doi=$target->nodeValue;
 CreateDoi($Exp, $item, $doi, 0, strlen($doi), $target->getAttribute('id'), $uri);


}




function InsertStandart($content, $uri, $item, $ArtTitle){
$arr = explode(".", $item);
$Work = "";
if(count($arr) > 1)
	for($i = 0; $i < count($arr) - 1; $i++)
		$Work = $Work.$arr[$i];
else
	if(count($arr) > 0)
		$Work = $arr[0];
$Exp = $Work."_ver1";

CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
GetUrlName($uri);
CreateTitle($Exp, $item, GetUrlName($uri), 0, 0, "", $uri);
}




function InsertDlib2($content, $uri, $item, $ArtTitle){
//Work = Nome Senza Estensione
//Exp = Nome Con _ver1
$Work = $item;
$Exp = $Work."_ver1";

$get = CreateResource($uri, $item, $Work, $Exp, $ArtTitle);
if($get != null) return;
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
$doc = new DOMDocument();	
$doc->loadHTML($content);
$xpath = new DOMXPath($doc);

//Titolo dell'articolo
$title = $doc->getElementsByTagName('h3')->item(0);
if($title != NULL){
	 CreateTitle($Exp, $item, $title->nodeValue, 0, strlen($title->nodeValue), $title->getAttribute('id'), $uri);
}

//Autori articolo
$aut= $xpath->query("//p[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p2']/text()[(following::br)]")->item(0);
$str=str_replace(" and ", ",", $aut->nodeValue);
$arr = explode(",", $str);
$start=0;
foreach($arr as $node){
	$var = trim($node);
	$start=strpos($aut->nodeValue, $var); //posizione dell'autore in tutta la frase compreso "and"
	$end=$start+strlen($var);
	CreateAuthors($Exp, $item, $var, $start,($end-1), $autTarget, $uri);
}

//Anno di pubblicazione dell'articolo
$target=$xpath->query("//p[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p1']")->item(0);
$ab = $xpath->query("//p[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p1']/text()[(following::br)]")->item(0);
$publicationYear = substr($ab->nodeValue, -4, 4);
 CreatePublicationYear($Exp, $item, $publicationYear, strlen($ab->nodeValue) - 4, strlen($ab->nodeValue), $target->getAttribute('id'), $uri); 


//DOI dell'articolo
$target=$xpath->query("//p[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p2']")->item(0);
$ab = $xpath->query("//p[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p2']/text()[(preceding::br)]");
foreach($ab as $node){ //scorre all'ultimo br dato che il DOI è sempre dopo l'ultimo br
$ab=$node;
}
$doi=$ab->nodeValue;
$start=strpos($target->nodeValue,$doi);
 CreateDoi($Exp, $item, $doi, $start, strlen($doi), $target->getAttribute('id'), $uri);


//Abstract
$astratto = $xpath->query("//h3[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_h33']/following-sibling::p[1]")->item(0);
CreateRethoric($Exp, $item, "sro:Abstract", "Questo è l' astratto dell'articolo.", 0, strlen($astratto->nodeValue), $astratto->getAttribute('id'), $uri);

//URL
$url = $xpath->query("//a[@id='form1_table3_tr1_td1_table5_tr1_td1_table1_tr1_td2_p3_a1']")->item(0);
CreateUrl($Exp, $item, Normalize($url->nodeValue), 0, strlen(Normalize($url->nodeValue)), $url->getAttribute('id'), $uri, "Questo testo rappresenta l' indirizzo:".$url->getAttribute('href'));


//Citazioni dell'articolo
$target = $xpath->query("//*[(preceding::h3[text()=\"References\"||text()=\"Notes\"])]");
$i=0;
foreach($target as $node){
	$citExp=$Exp."_cited".$i;
	if($node->nodeName!='p')
		break;
	else{
		$pos=strpos($node->nodeValue,'"');
		$t=substr($node->nodeValue, $pos);
		$pos=strpos($t,'"');
		//$title=substr($t, $pos);
		CreateCities($t, $citExp, $Exp, $item, $node->nodeValue, $node->getAttribute('id'), 0, strlen($cite->nodeValue), $uri);
		
	}
}
}

function MultiDelimiter($delimiters, $string){
	$arr = explode($delimiters[0], $string);
	array_shift($delimiters);
	if($delimiters != NULL) {
		foreach($arr as $key => $val)
             $arr[$key] = MultiDelimiter($delimiters, $val);
	}
	return $arr;
}
?>
