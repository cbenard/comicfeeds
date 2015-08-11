<?php
	
class DoonesburyService extends GenericComicService {
	const FEED_URI = 'http://feeds.feedburner.com/uclick/doonesbury?format=xml';
	const PATTERN = '/<span class=\'zoom_link\'>.+?<img.+?src="(.+?)"/';

	const ENTRY_SEARCH_TEXT = ", 2";
	
	public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
		parent::__construct("doonesbury", $feedService, $logger, $store);
	}
	
	private function getConfig() {
		$config = array();
		
		$config[] = new FeedFetchOptions('default', self::FEED_URI, self::ENTRY_SEARCH_TEXT, self::PATTERN);
		
		return $config;
	}
	
	public function fetchAllAndStore() {
		$config = $this->getConfig();
		
		foreach ($config as $currentConfig) {
			$this->fetchAndStore($currentConfig);
		}
	}
}