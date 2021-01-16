<?php
namespace Comicfeeds;

class FeedFetchOptions {
	public $feedUri;
	public $pattern;
	public $entrySearchText;
	public $name;
	public $shouldTranslateAtomToRss;
	
	public function __construct($name, $feedUri, $entrySearchText, $pattern) {
		$this->name = $name;
		$this->feedUri = $feedUri;
		$this->entrySearchText = $entrySearchText;
		$this->pattern = $pattern;
	}
}