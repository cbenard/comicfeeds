<?php
namespace Comicfeeds;

class PVPService extends GenericComicService {
    const FEED_URI = 'http://www.pvponline.com/feed';
    const PATTERN = '/class="comic-art">.*?<img src="(.+?)"/s';
    const ENTRY_SEARCH_TEXT = "Comic: ";

    public function __construct(FeedService $feedService, Logger $logger, StorageService $store) {
        parent::__construct("pvp", $feedService, $logger, $store);
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