<?php

use PHPUnit\Framework\TestCase;
use DI\{Container, ContainerBuilder};
use Comicfeeds\FeedService;
use Comicfeeds\Logger;

class FeedServiceTest extends TestCase {
	protected $container;
	
	protected function setUp(): void {
		$builder = new ContainerBuilder();
		// $builder->useAutowiring(true);
		$this->container = $builder->build();		
		$this->container->set(Logger::class, $this->getMockBuilder(Logger::class)->getMock());
	}
	
	protected function tearDown(): void {
		unset($this->container);
	}

	public function test_fetches_xml() {
		$feed = $this->container->get(FeedService::class);
		$url = "http://www.w3schools.com/xml/note.xml";
		
		$doc = $feed->fetchFeed($url);
		
		$this->assertNotNull($doc);
		$this->assertNotNull($doc->note);
	}

	public function test_fetches_page_content() {
		$feed = $this->container->get(FeedService::class);
		$url = "http://google.com/robots.txt";
		
		$contents = $feed->fetchPageContents($url);
		
		$this->assertNotNull($contents);
		$this->assertStringContainsString("User-agent: *", $contents);
		$this->assertStringContainsString("Disallow: ", $contents);
	}

	public function test_parses_rss_link() {
		$feed = $this->container->get(FeedService::class);
		$xml = '<entry><link href="http://www.google.com"/></entry>';
		$entry = new SimpleXMLElement($xml);

		$url = $feed->getLinkFromEntry($entry);
		
		$this->assertEquals("http://www.google.com", $url);
	}

	public function test_parses_atom_link() {
		$feed = $this->container->get(FeedService::class);
		$xml = '<entry><link>http://www.google.com</link></entry>';
		$entry = new SimpleXMLElement($xml);

		$url = $feed->getLinkFromEntry($entry);
		
		$this->assertEquals("http://www.google.com", $url);
	}
	
	public function test_parses_image_url_dilbert() {
		$feed = $this->container->get(FeedService::class);
		$contents = file_get_contents(__DIR__ . '/assets/dilbert.comicpage.html');
		$expectedUrl = "http://assets.amuniversal.com/e0c30550fd6e0132ef1a005056a9545d";
		$pattern = '/data-image="(.+?)"/';
		
		$imageUrl = $feed->getImageUrl($contents, $pattern);
		
		$this->assertEquals($expectedUrl, $imageUrl);
	}
	
	public function test_parses_image_url_pennyarcade() {
		$feed = $this->container->get(FeedService::class);
		$contents = file_get_contents(__DIR__ . '/assets/pennyarcade.comicpage.html');
		$expectedUrl = "http://art.penny-arcade.com/photos/i-Kg5s4qC/0/1050x10000/i-Kg5s4qC-1050x10000.jpg";
		$pattern = '/<div.+img src="(.+?)"/';
		
		$imageUrl = $feed->getImageUrl($contents, $pattern);
		
		$this->assertEquals($expectedUrl, $imageUrl);
	}
}