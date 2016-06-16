<?php

/**
 * Extracts the daily motion id from a daily motion url.
 * Returns false if the url is not recognized as a daily motion url.
 */
function getDailyMotionId($url)
{
    if (preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $m)) {
        if (isset($m[6])) {
            return $m[6];
        }
        if (isset($m[4])) {
            return $m[4];
        }
        return $m[2];
    }
    return false;
}

/**
 * Extracts the vimeo id from a vimeo url.
 * Returns false if the url is not recognized as a vimeo url.
 */
function getVimeoId($url)
{
    if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $url, $m)) {
        return $m[1];
    }
    return false;
}

/**
 * Extracts the youtube id from a youtube url.
 * Returns false if the url is not recognized as a youtube url.
 */
function getYoutubeId($url)
{
    $parts = parse_url($url);
    if (isset($parts['host'])) {
        $host = $parts['host'];
        if (false === strpos($host, 'youtube') && false === strpos($host, 'youtu.be')) {
            return false;
        }
    }
    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        } elseif (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }
    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }
    return false;
}