<?php

header("Content-Type: text/plain");
require_once("common.php");

$feed = "http://dilbert.com/feed.rss";
$doc = simplexml_load_file($feed) or die('Unable to load XML');

$count = count($doc->entry);

for ($i = $count - 1; $i >= 0; $i--) {
	$entry = $doc->entry[$i];
	if (strpos($entry->title, "Comic for ") === FALSE) {
		unset($doc->entry[$i]);
	}
	else {
		echo "Processing $entry->title...\n";
		$newContents = get_entry_content($entry);
		$entry->content = '';
		$entry->content['type'] = "html";
		$domNode = dom_import_simplexml($entry->content);
		$owner = $domNode->ownerDocument;
		$domNode->appendChild($owner->createCDATASection($newContents));
		echo "\tDone.\n";
	}
}

function get_entry_content($entry) {
	$url = $entry->link['href'];
	echo "\tFetching URL: $url\n";
	$contents = file_get_contents($url);
	
	$imageUrl = get_image_url($contents);
	
	$newContents = "<img src=\"$imageUrl\"/>";
	
	return $newContents;
}

function get_image_url($contents) {
	$pattern = '/data-image="(.+?)"/';
	preg_match($pattern, $contents, $matches);
	
	$imageUrl = $matches[1];
	echo "\tFound image URL: $imageUrl\n";
	
	return $imageUrl;
}

$outputXml = $doc->asXml();
print_r($outputXml);

file_put_contents("gs://#default#/feed_dilbert_default", $outputXml);
