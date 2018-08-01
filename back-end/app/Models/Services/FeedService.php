<?php

namespace App\Models\Services;



class FeedService
{
    /**
     * @var string RSS Feed url
     */
    protected $feedUrl = 'https://medium.com/feed/@zantepay';

    public function __construct()
    {
    }

    /**
     * Get feed items
     */
    public function getItems() {
        $rss = [];

        try {
            $feedItems = $this->read();
        } catch (\Exception $e) {
            $feedItems = [];
        }

        foreach ($feedItems as $feedItem) {
            $rss[] = [
                'title' => $feedItem['title'],
                'link' => $feedItem['link'],
                'published' => date('Y / d / m', strtotime($feedItem['pubDate'])),
                'datetime' => date('Y / m / d', strtotime($feedItem['pubDate']))
            ];
        }

        $slicePosition = ceil(count($rss) / 2);

        return [
            'leftColumnRss' => array_slice($rss, 0, $slicePosition),
            'rightColumnRss' => array_slice($rss, $slicePosition)
        ];
    }

    /**
     * Read RSS Feed
     */
    protected function read() {
        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($this->feedUrl);

        $feedItems = $xmlDoc->getElementsByTagName('item');

        $rssFeed = [];

        foreach ($feedItems as $feedItem) {
            $rssFeed[] = [
                'title' => $feedItem->getElementsByTagName('title')->item(0)->nodeValue,
                'link' => $feedItem->getElementsByTagName('link')->item(0)->nodeValue,
                'pubDate' => $feedItem->getElementsByTagName('pubDate')->item(0)->nodeValue,
            ];
        }

        return $rssFeed;
    }
}
