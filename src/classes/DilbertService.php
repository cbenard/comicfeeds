<?php
namespace Comicfeeds;
	
class DilbertService extends GenericComicService {
	const FEED_URI = 'http://dilbert.com/feed';
	const PATTERN = '/data-image="(.+?)"/';
	const ENTRY_SEARCH_TEXT = "Comic for ";
	
	public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
		parent::__construct("dilbert", $feedService, $logger, $store);
	}
	
	private function getConfig() {
		$config = array();
		
		$config[] = new FeedFetchOptions('default', self::FEED_URI, self::ENTRY_SEARCH_TEXT, self::PATTERN);
		
		// Local SDK on Windows at least incorrectly doesn't include XSLTProcessor (php_xsl.dll)
		if (class_exists('XSLTProcessor')) {
			$rss = new FeedFetchOptions('rss', self::FEED_URI, self::ENTRY_SEARCH_TEXT, self::PATTERN);
			$rss->shouldTranslateAtomToRss = true;
			$config[] = $rss;
		}

		return $config;
	}
	
	public function fetchAllAndStore() {
		$config = $this->getConfig();
		
		foreach ($config as $currentConfig) {
			$this->fetchAndStore($currentConfig);
		}
	}
}