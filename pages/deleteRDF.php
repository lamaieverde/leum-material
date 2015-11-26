<?php
require_once("../vendor/autoload.php" );
require_once("utils.php");
function DeleteAuthors($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel, $author_label){
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
		DELETE DATA {
		".Insert."
		{
		  <ann-autore$annotation_ID> a oa:Annotation ; 
									rdfs:label '$label';
									oa:annotatedBy <$by>;
								    oa:annotatedAt '$at';
									oa:hasBody <body-autore$annotation_ID>;
								    oa:hasTarget  <target-autore$annotation_ID>.
		<target-autore$annotation_ID> a oa:SpecificResource;
									oa:hasSource <$item> ;
									oa:hasSelector <selector-autore$annotation_ID>.
		<selector-autore$annotation_ID> a oa:FragmentSelector ;
									  rdf:value '$id' ;
									  oa:start '$start'^^xsd:nonNegativeInteger ;
									  oa:end '$end'^^xsd:nonNegativeInteger.
		<body-autore$annotation_ID> a rdf:Statement;
									rdf:subject <$subject>;
									rdf:predicate dcterms:creator;
									rdf:object <$object>;
									rdfs:label '$bLabel'^^xsd:string.
							
		<$object> a foaf:Person;
				  rdfs:label '$author_label'^^xsd:string .
				}
			}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteTitle($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX dlib: <$uri>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX dcterms: <http://purl.org/dc/terms/>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-titolo$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-titolo$annotation_ID>;
			  							oa:hasTarget  <target-titolo$annotation_ID>. 
				<target-titolo$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-titolo$annotation_ID>.
				<selector-titolo$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-titolo$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate dcterms:title;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeletePublicationYear($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX dlib: <$uri>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX rsc: <http://vitali.web.cs.unibo.it/raschietto/>\nPREFIX fabio: <http://purl.org/spar/fabio/>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-annodp$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-annodp$annotation_ID>;
			  							oa:hasTarget  <target-annodp$annotation_ID>. 
				<target-annodp$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-annodp$annotation_ID>.
				<selector-annodp$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-annodp$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate fabio:hasPublicationYear;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteDoi($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX prism: <http://prismstandard.org/namespaces/basic/2.0/>\nPREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX dlib: <$uri>\nPREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX fabio: <http://purl.org/spar/fabio/>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-doi$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-doi$annotation_ID>;
			  							oa:hasTarget  <target-doi$annotation_ID>. 
				<target-doi$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-doi$annotation_ID>.
				<selector-doi$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-doi$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate prism:doi;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteUrl($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX dlib: <$uri>\nPREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/person/>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX prism: <http://prismstandard.org/namespaces/basic/2.0/>\nPREFIX fabio: <http://purl.org/spar/fabio/>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-url$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-url$annotation_ID>;
			  							oa:hasTarget  <target-url$annotation_ID>. 
				<target-url$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-url$annotation_ID>.
				<selector-url$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-url$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate fabio:hasURL;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteCites($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel,$cite){
	$Prefix =  "PREFIX cito: <http://purl.org/spar/cito/>\nPREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX dlib: <$uri>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
				<ann-cito$annotation_ID> a oa:Annotation;
											rdfs:label \"label\";
											oa:hasTarget <target-cito$annotation_ID>;
											oa:annotatedBy <$by> ;
											oa:annotatedAt \"$at\";
											oa:hasBody <body-cito$annotation_ID>.
				<target-cito$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-cito$annotation_ID>. 
				<selector-cito$annotation_ID> a oa:FragmentSelector ;
												rdf:value \"$id\";
												oa:start \"$start\"^^xsd:nonNegativeInteger ;
												oa:end \"$end\"^^xsd:nonNegativeInteger.
				<body-cito$annotation_ID> a rdf:Statement ; 
											rdf:subject <$subject>;
											rdf:predicate cito:cites; 
											rdf:object <$object> ;
											rdfs:label \"$bLabel\".
				<$object> rdfs:label \"$cite\".
			}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteComment($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX dlib: <http://www.dlib.org/dlib/november14/beel/>\nPREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX schema: <http://schema.org/>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-commento$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-commento$annotation_ID>;
			  							oa:hasTarget  <target-commento$annotation_ID>. 
				<target-commento$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-commento$annotation_ID>.
				<selector-commento$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-commento$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate  schema:comment;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
function DeleteRethoric($item, $uri, $label, $annotation_ID, $at, $by, $id, $start, $end, $subject, $object, $bLabel){
	$Prefix = "PREFIX sem: <http://www.ontologydesignpatterns.org/cp/owl/semiotics.owl#>\nPREFIX schema: <http://schema.org/>\nPREFIX oa: <http://www.w3.org/ns/oa#>\nPREFIX dlib: <http://www.dlib.org/dlib/march15/moulaison/>\nPREFIX rsch: <http://vitali.web.cs.unibo.it/raschietto/>\nPREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>\nPREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>\nPREFIX deo: <http://purl.org/spar/deo/>\nPREFIX xsd: <http://www.w3.org/2001/XMLSchema#>\nPREFIX skos: <http://www.w3.org/2004/02/skos/core#>\nPREFIX sro: <http://salt.semanticauthoring.org/ontologies/sro#>";
	$Query = $Prefix."
		DELETE DATA {
		".Insert."
		{
			  <ann-retorica$annotation_ID> a oa:Annotation ; 
										rdfs:label \"$label\";
										oa:annotatedBy <$by> ;
										oa:annotatedAt \"$at\" ;
										oa:hasBody <body-retorica$annotation_ID>;
			  							oa:hasTarget  <target-retorica$annotation_ID>. 
				<target-retorica$annotation_ID> a oa:SpecificResource;
											oa:hasSource <$item> ;
											oa:hasSelector <selector-retorica$annotation_ID>.
				<selector-retorica$annotation_ID> a oa:FragmentSelector;
											  rdf:value \"$id\" ;
											  oa:start \"$start\"^^xsd:nonNegativeInteger ;
											  oa:end \"$end\"^^xsd:nonNegativeInteger.
			  <body-retorica$annotation_ID> a rdf:Statement;
										rdf:subject <$subject>;
										rdf:predicate sem:denotes;
										rdf:object \"$object\";
										rdfs:label	\"$bLabel\".
									}
								}";
	$answer = Update($Query);
	//print "<xmp>$Query</xmp>";
}
?>