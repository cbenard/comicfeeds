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

		if (isset($doc->entry)) {
			$entries = $doc->entry;
		} else if (isset($doc->channel) && isset($doc->channel->item)) {
			$entries = $doc->channel->item;
		}
		
		if (!isset($entries)) {
			throw new Exception('Unable to detect <item> or <entry> nodes.');
		}
		
		$count = count($entries);

		if ($count < 1) {
			throw new Exception("No entries found in feed {$this->feed}.");
		}

		for ($i = $count - 1; $i >= 0; $i--) {
			$entry = $entries[$i];
			if (strpos($entry->title, $this->entrySearchText) === FALSE) {
				unset($entries[$i]);
			} else {
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
		$url = $this->feedService->getLinkFromEntry($entry);
		
		$this->log->log("\tFetching URL: $url");
		$contents = $this->feedService->fetchPageContents($url);
		
		$imageUrl = $this->feedService->getImageUrl($contents, $this->pattern);
		
		$newContents = "<img src=\"$imageUrl\"/>";
		
		return $newContents;
	}
}