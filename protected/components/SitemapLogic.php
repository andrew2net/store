<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SitemapLogic
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
class SitemapLogic
{
    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER = 'never';
    protected $items = array();
    /**
     * @param $url
     * @param string $changeFreq
     * @param float $priority
     * @param int $lastmod
     */
    public function addUrl($url, $changeFreq=self::DAILY, $priority=0.5, $lastMod=0)
    {
        $host = Yii::app()->request->hostInfo;
        $item = array(
            'loc' => $host . $url,
            'changefreq' => $changeFreq,
            'priority' => $priority
        );
        if ($lastMod)
            $item['lastmod'] = date(DATE_W3C, $lastMod);
        $this->items[] = $item;
    }
    /**
     * @param CActiveRecord[] $model
     * @param string $changeFreq
     * @param float $priority
     */
    public function addUrlsByModel($model, $changeFreq=self::DAILY, $priority=0.5)
    {
        $host = Yii::app()->request->hostInfo;
        $urls = $model->getUrlsForSitemap();
        foreach ($urls as $url=>$update_time)
        {
            $item = [
                'loc' => $host . $url,
                'changefreq' => $changeFreq,
                'priority' => empty($url) ? 1 : $priority,
                'lastmod' => date(DATE_W3C, $update_time)
            ];
            $this->items[] = $item;
        }
    }
    /**
     * @param CActiveRecord[] $models
     * @param string $changeFreq
     * @param float $priority
     */
    public function addModel($models, $changeFreq=self::DAILY, $priority=0.5)
    {
        $host = Yii::app()->request->hostInfo;
        foreach ($models as $model)
        {
            $item = array(
                'loc' => $host . $model->getUrl(),
                'changefreq' => $changeFreq,
                'priority' => empty($url) ? 1 : $priority
            );
            if ($model->hasAttribute('update_time'))
                $item['lastmod'] = date(DATE_W3C, strtotime($model->update_time));
            $this->items[] = $item;
        }
    }
    /**
     * @return string XML code
     */
    public function render()
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns','"http://www.sitemaps.org/schemas/sitemap/0.9"');
        foreach($this->items as $item)
        {
            $url = $dom->createElement('url');
            foreach ($item as $key=>$value)
            {
                $elem = $dom->createElement($key);
                $elem->appendChild($dom->createTextNode($value));
                $url->appendChild($elem);
            }
            $urlset->appendChild($url);
        }
        $dom->appendChild($urlset);
        return $dom->saveXML();
    }
}