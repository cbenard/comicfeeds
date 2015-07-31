<?php
	
class GenericComicService {
	protected $feed;
	protected $feedService;
	protected $log;
	protected $pattern;
	protected $entrySearchText;
	protected $name;
	protected $store;

	public function __construct(
		$name, FeedService $feedService,
		Logger $logger, StorageService $store) {

		$this->feedService = $feedService;
		$this->log = $logger;
		$this->name = $name;
		$this->store = $store;
	}
	
	public function fetchAndStore() {
		$xml = $this->fetch();
		$filename = "feed_" . $this->name;
		$this->store->save($filename, $xml);
	}
	
	protected function fetch() {
		$doc = $this->feedService->fetchFeed($this->feed);
		
		$count = count($doc->entry);
		if ($count < 1) {
			throw new Exception("No entries found in feed {$this->feed}.");
		}

		for ($i = $count - 1; $i >= 0; $i--) {
			$entry = $doc->entry[$i];
			if (strpos($entry->title, $this->entrySearchText) === FALSE) {
				unset($doc->entry[$i]);
			}
			else {
				$this->log->log("Processing $entry->title...");
				
				if (isset($entry->description)) {
					$entry->description = '';
				} else {
					$entry->addChild("description");
				}
				
				if (isset($entry->content)) {
					unset($entry->content);
				}
				
				$newContents = $this->getEntryContents($entry);
				$domNode = dom_import_simplexml($entry->description);
				$owner = $domNode->ownerDocument;
				$domNode->appendChild($owner->createCDATASection($newContents));
				$this->log->log("\tDone.\n");
			}
		}
		print_r($doc);
		return $doc->asXml();
	}
	
	private function getEntryContents(SimpleXMLElement $entry) {
		$url = $entry->link['href'];
		$this->log->log("\tFetching URL: $url");
		$contents = $this->feedService->fetchPageContents($url);
		
		$imageUrl = $this->feedService->getImageUrl($contents, $this->pattern);
		
		$newContents = "<img src=\"$imageUrl\"/>";
		
		return $newContents;
	}
}