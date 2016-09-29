<?php
require 'vendor/autoload.php';

use Goutte\Client;

function curl_get_contents($url) {
    if (!function_exists('curl_init')) { return file_get_contents($url); } // fallback
    $ch = curl_init();
    $options = array(
        CURLOPT_CONNECTTIMEOUT => 1,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HEADER         => false,
        CURLOPT_URL            => $url,
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    if ($response === false) {
        rt(curl_error($ch));
    }
    curl_close($ch);
    return $response;
}

$client  = new Client();
$crawler = $client->request('GET', 'http://www.sueddeutsche.de/');
// get link of current months imageoftheday page
$link    = $crawler->filter('#imageoftheday .header a')->first()->attr('href');

$nextpage = true;

while ($nextpage) {
    $crawler = $client->request('GET', $link);
    if ($crawler->filter('.body figure .image img')->count()) {
        // get img src without resize
        $img = $crawler->filter('.body figure .image img')->first()->attr('data-src');
        $clean_img_link = preg_replace("/_gen\/(.)+/", '', $img);
        // get img caption
        if ($crawler->filter('.body figure .caption .text')->count()) {
            $text = trim($crawler->filter('.body figure .caption .text')->first()->text());
        }
    }
    // get shortlink to img
    $short_link = $crawler->filter('#tinyUrl')->count() ? 'http://' . $crawler->filter('#tinyUrl')->first()->text() : false;
    
    // save img + json containing text+shortlink with unique filename that also sorts the directory
    $id = uniqid('img_');
    file_put_contents('data/' . $id . '.json', json_encode(['text' => $text, 'link' => $short_link]));
    file_put_contents('data/' . $id . '.' . pathinfo($clean_img_link, PATHINFO_EXTENSION), curl_get_contents($clean_img_link));
    
    echo $id . PHP_EOL;
    
    // check for nextpage
    $nextpage = (bool)$crawler->filter('span.navigation .next')->count();
    if ($crawler->filter('span.navigation .next')->count()) {
        $link = $crawler->filter('span.navigation .next')->first()->attr('href');
    } else {
        $nextpage = false;
    }
}
