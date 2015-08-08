<?php
	
abstract class GenericComicService {
	protected $feedService;
	protected $log;
	protected $majorName;
	protected $store;

	public function __construct(
		$name, FeedService $feedService,
		Logger $logger, StorageService $store) {

		$this->feedService = $feedService;
		$this->log = $logger;
		$this->majorName = $name;
		$this->store = $store;
	}
	
	abstract public function fetchAllAndStore();
	
	protected function fetchAndStore(FeedFetchOptions $config) {
		$filename = "feed_" . $this->majorName . '_' . $config->name;
		$xml = $this->fetch($config);
		$xml = $this->sanitizeXml($xml);
		$this->store->save($filename, $xml);
	}
	
	// From: http://stackoverflow.com/a/29418829/448
	protected function sanitizeXml($rawContents) {
		$rawContents = preg_replace('/<\?xml\-[^>]+\?>\r?\n?/im', '', $rawContents);
		return $rawContents;
	}
	
	protected function fetch(FeedFetchOptions $config) {
		$doc = $this->feedService->fetchFeed($config->feedUri);

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
			if (strpos($entry->title, $config->entrySearchText) === FALSE) {
				unset($entries[$i]);
			} else {
				$this->log->log("Processing $entry->title...");
				$newContents = $this->getEntryContents($config, $entry);
				
				if (isset($entry->description)) {
					$entry->description = $newContents;
				}
				
				if (isset($entry->content)) {
					$entry->content = $newContents;
				}
				
				// if (isset($entry->content)) {
				// 	unset($entry->content);
				// }
				
				// Feedly and others don't like CDATA
				// $domNode = dom_import_simplexml($entry->description);
				// $owner = $domNode->ownerDocument;
				// $domNode->appendChild($owner->createCDATASection($newContents));
				
				// Append escaped instead
				// $entry->description = $newContents;
				
				$this->log->log("\tDone.\n");
			}
		}
		print_r($doc);
		$xmlString = $doc->asXml();
		
		if ($config->shouldTranslateAtomToRss) {
			$xmlString = $this->translateAtomToRss($xmlString);
		}
		
		return $xmlString;
	}
	
	// From: http://atom.geekhood.net/
	protected function translateAtomToRss($input) {
		$chan = new DOMDocument();
		$chan->loadXML($input); /* load channel */
		$sheet = new DOMDocument();
		$sheet->load(__DIR__ . '/../atom2rss.xsl'); /* use stylesheet from this page */
		$processor = new XSLTProcessor();
		$processor->registerPHPFunctions();
		$processor->importStylesheet($sheet);
		$result = $processor->transformToXML($chan); /* transform to XML string (there are other options - see PHP manual)  */
		
		return $result;
	}
	
	private function getEntryContents(FeedFetchOptions $config, SimpleXMLElement $entry) {
		$url = $this->feedService->getLinkFromEntry($entry);
		
		$contents = $this->feedService->fetchPageContents($url);
		
		$imageUrl = $this->feedService->getImageUrl($contents, $config->pattern);
		
		$newContents = "<img src=\"$imageUrl\"/>";
		
		return $newContents;
	}
}