<?php
	
class PennyArcadeService extends GenericComicService {
	public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
		parent::__construct("pennyarcade_default", $feedService, $logger, $store);
		
		$this->feed = "http://penny-arcade.com/feed";
		$this->pattern = '/<div.+img src="(.+?)"/';
		$this->entrySearchText = "Comic: ";
	}
}