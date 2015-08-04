<?php
	
class PennyArcadeService extends GenericComicService {
	const FEED_URI = 'http://penny-arcade.com/feed';
	const PATTERN = '/<div.+img src="(.+?)"/';
	const ENTRY_SEARCH_TEXT = "Comic: ";
	
	public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
		parent::__construct("pennyarcade", $feedService, $logger, $store);
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