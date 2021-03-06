<?php
$cpages = $_POST["cpages"];
$n = $_POST["n"];

/* create a dom document with encoding utf8 */
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

/* create the root element of the xml tree */
$parameters = $xml->createElement("parameters");
/* append it to the document created */
$parameters = $xml->appendChild($parameters);

$reqType = $xml->createElement("requestType");
$reqType = $parameters->appendChild($reqType);
$summarize = $xml->createTextNode('summarize');
$summarize = $reqType->appendChild($summarize);

$maxSentences = $xml->createElement("maxSentences");
$maxSentences = $parameters->appendChild($maxSentences);
$numSentences = $xml->createTextNode($n);
$numSentences = $maxSentences->appendChild($numSentences);

$individualSummaries = $xml->createElement("individualSummaries");
$individualSummaries = $parameters->appendChild($individualSummaries);
$true = $xml->createTextNode('TRUE');
$true = $individualSummaries->appendChild($true);    

$docList = $xml->createElement("docList");
$docList = $parameters->appendChild($docList);

foreach ($cpages as $checked) {
	$doc = $xml->createElement("doc");
	$doc = $docList->appendChild($doc);
	$docID = $xml->createElement("docID");
	$docID = $doc->appendChild($docID);
	$cvalue = $xml->createTextNode($checked);
	$cvalue = $docID->appendChild($cvalue);
}

    /* get the xml printed */
    echo $xml->saveXML();
    // $xml->save('summarizeInput.xml');
?> 