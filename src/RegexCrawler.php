<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 05/02/2017
 * Time: 2:39 AM
 */

namespace Djaney\Crawler\Regex;


class RegexCrawler
{

    /**
     * Test if the pattern matches the content of the innerHTML indicated in the path
     * @param $url
     * @param $query path of the element to be tested
     * @param $pattern pattern to match
     * @return bool
     */
    public function testInnerHtml($url, $query, $pattern)
    {
        $html = $this->fetchHtml($url);
        $domList = $this->getDom($html, $query);

        foreach ($domList as $elem) {
            if ( $this->testPattern($elem->nodeValue, $pattern) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $html
     * @param $query
     * @return \DOMNodeList
     */
    public function getDom($html, $query)
    {
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        $xpath = new \DOMXpath($doc);

        return $xpath->query($query);
    }

    /**
     * @param $url
     * @return string
     */
    public function fetchHtml($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * @param $text
     * @param $pattern
     * @return bool
     */
    public function testPattern($text, $pattern){
        $result = preg_match ( $pattern, $text);
        return $result !== false && $result > 0;
    }



}