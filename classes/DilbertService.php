<?php
	
class DilbertService extends GenericComicService {
	public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
		parent::__construct("dilbert_default", $feedService, $logger, $store);
		
		$this->feed = "http://dilbert.com/feed.rss";
		$this->pattern = '/data-image="(.+?)"/';
		$this->entrySearchText = "Comic for ";
	}
}