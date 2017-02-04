<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 05/02/2017
 * Time: 3:20 AM
 */


use Djaney\Crawler\Regex\RegexCrawler;


class RegexCrawlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::testPattern
     */
    public function testCorrectPattern(){
        $crawler = new RegexCrawler();
        $this->assertTrue($crawler->testPattern('Test pattern', '/^Test pattern$/'));
        $this->assertTrue($crawler->testPattern('Test pattern', '/^Test/'));
        $this->assertTrue($crawler->testPattern('Test pattern', '/pattern$/'));
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::testPattern
     */
    public function testWrongPattern(){
        $crawler = new RegexCrawler();
        $this->assertFalse($crawler->testPattern('Test pattern', '/^Test Pattern$/'));
        $this->assertFalse($crawler->testPattern('Test pattern', '/Test$/'));
        $this->assertFalse($crawler->testPattern('Test pattern', '/^pattern/'));
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::getDom
     */
    public function testDomCrawling(){
        libxml_use_internal_errors(true);
        $crawler = new RegexCrawler();
        $list = $crawler->getDom('<book><title>Book title</title></book>', '//book/title');
        $this->assertEquals('Book title', $list[0]->nodeValue);
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::getDom
     */
    public function testDomCrawlingEmpty(){
        libxml_use_internal_errors(true);
        $crawler = new RegexCrawler();
        $list = $crawler->getDom('<book><title>Book title</title></book>', '//book/titles');
        foreach($list as $l){
            throw new \Exception('Code should not reach this');
        }
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::fetchHtml
     */
    public function testUrlFetching(){
        $url = 'https://gist.githubusercontent.com/djaney/632f230a77f7ea8eff635d1bc93671f2/raw/01043d84c25ff122f8450195ee1364c73be1ce00/dummpy_for_unit_test.txt';
        $crawler = new RegexCrawler();
        $this->assertEquals('Used for unit test',$crawler->fetchHtml($url));
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::testInnerHtml
     */
    public function testTestInnerHtml(){
        $url = 'https://gist.githubusercontent.com/djaney/bb4103ac4a1f287b4dda75684c4eb044/raw/c2f2112f95da1f16873f09298e86160cd609c304/dummy_html.html';
        $crawler = new RegexCrawler();
        $this->assertTrue($crawler->testInnerHtml($url, '//html/body/h1','/^used for/i'));
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::testInnerHtml
     */
    public function testTestInnerHtmlNoDom(){
        $url = 'https://gist.githubusercontent.com/djaney/bb4103ac4a1f287b4dda75684c4eb044/raw/c2f2112f95da1f16873f09298e86160cd609c304/dummy_html.html';
        $crawler = new RegexCrawler();
        $this->assertFalse($crawler->testInnerHtml($url, '//html/body/h2','/^used for/i'));
    }

    /**
     * @covers \Djaney\Crawler\Regex\RegexCrawler::testInnerHtml
     */
    public function testTestInnerHtmlNoMatch(){
        $url = 'https://gist.githubusercontent.com/djaney/bb4103ac4a1f287b4dda75684c4eb044/raw/c2f2112f95da1f16873f09298e86160cd609c304/dummy_html.html';
        $crawler = new RegexCrawler();
        $this->assertFalse($crawler->testInnerHtml($url, '//html/body/h1','/^used forxxx/i'));
    }
}
