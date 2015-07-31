<?php
	
class FeedService {
	private $log;
	
	public function __construct(Logger $logger) {
		$this->log = $logger;
	}
	
	public function fetchFeed($url) {
		$doc = simplexml_load_file($url);
		if ($doc === FALSE) {
			throw new Exception('Unable to load XML');
		}
		return $doc;
	}
	
	public function fetchPageContents($url) {
		$contents = file_get_contents($url);
		if ($contents === FALSE) {
			throw new Exception('Unable to load page contents');
		}
		return $contents;
	}
	
	public function getLinkFromEntry(SimpleXMLElement $entry) {
		$url = $entry->link['href'];
		return $url;
	}
	
	public function getImageUrl($contents, $regexPattern) {
		$ret = preg_match($regexPattern, $contents, $matches);
		
		if ($ret === FALSE) {
			throw new Exception("Regex error occurred.");
		} elseif ($ret === 0) {
			throw new Exception("Regex did not match subject.");
		} elseif ($ret !== 1) {
			throw new Exception("Unexpected regex return value: $ret");
		} elseif (count($matches) < 2) {
			throw new Exception("Regex had no matches.");
		}
		
		$imageUrl = $matches[1];
		
		return $imageUrl;
	}
}