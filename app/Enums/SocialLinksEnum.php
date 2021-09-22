<?php

namespace App\Enums;

class SocialLinksEnum
{
    public const WIKIPEDIA_REGEX = '/(?:http:\/\/)?(?:www\.)?wikipedia\.org\//';
    public const WIKIDATA_REGEX = '/(?:http:\/\/)?(?:www\.)?wikidata\.org\//';
    public const FACEBOOK_REGEX  = '/(?:http:\/\/)?(?:www\.)?facebook\.com\//';
    public const YOUTUBE_REGEX   = '/(?:http:\/\/)?(?:www\.)?youtube\.com\//';
    public const TWITTER_REGEX   = '/(?:http:\/\/)?(?:www\.)?twitter\.com\//';
    public const INSTAGRAM_REGEX = '/(?:http:\/\/)?(?:www\.)?instagram\.com\//';
    public const LINKEDIN_REGEX  = '/(?:http:\/\/)?(?:www\.)?linkedin\.com\//';
    public const VK_REGEX        = '/(?:http:\/\/)?(?:www\.)?vk\.com\//';
    public const WHATSAPP_REGEX  = '/(?:http|https)?:?\/?\/?(?:www\.|chat\.)?whatsapp\.com\/(?:|\/\?)/';
    public const TELEGRAM_REGEX  = '/(?:http:\/\/)?(?:www\.)?t\.me\//';
    public const TIKTOK_REGEX    = '/(?:http|https)?(?:www|vm|m\.)?tiktok.com/';
    public const WEB_URL         = '/(https|http|ftp)\:\/\/|([a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4})|([a-z0-9A-Z]+\.[a-zA-Z]{2,4})|\?([a-zA-Z0-9]+[\&\=\#a-z]+)/';
}
