<?php
	
class FeedService {
	private $log;
	
	public function __construct(Logger $logger) {
		$this->log = $logger;
	}

	public function getFeedName($requestUri) {
		$pattern = "@^/fetch/(\w+)$@";
		$ret = preg_match($pattern, $requestUri, $matches);
		
		if ($ret === FALSE) {
			throw new Exception("Regex error occurred.");
		} elseif ($ret === 0) {
			throw new Exception("Regex did not match subject.");
		} elseif ($ret !== 1) {
			throw new Exception("Unexpected regex return value: $ret");
		} elseif (count($matches) < 2) {
			throw new Exception("Regex had no matches.");
		}
		
		$feedName = $matches[1];
		return $feedName;	
	}
	
	public function fetchFeed($url) {
		$this->log->log("Fetching feed: $url");
		$doc = simplexml_load_file($url);
		if ($doc === FALSE) {
			throw new Exception('Unable to load XML');
		}
		return $doc;
	}
	
	public function fetchPageContents($url) {
		$this->log->log("\tFetching URL: $url");
		$contents = file_get_contents($url);
		if ($contents === FALSE) {
			die;
			throw new Exception('Unable to load page contents');
		}
		return $contents;
	}
	
	public function getLinkFromEntry(SimpleXMLElement $entry) {
		if (!isset($entry->link)) {
			throw new Exception("Link element was not present");
		}
		
		$url = $entry->link['href'] ? $entry->link['href'] : (string)$entry->link;
		if (!$url) {
			throw new Exception("Unable to detect link URL.");
		}
		
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
		
		$this->log->log("\tFound image URL: $imageUrl");
		return $imageUrl;
	}
}