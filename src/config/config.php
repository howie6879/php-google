<?php
/**
 * Author: howie
 * CreateTime: 24/06/2017 09:34
 * Description: MagicGoogle config
 */

return [
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.95 Safari/537.36',
    'domain' => 'www.google.com',
    'url_search' => "https://%s/search?hl=%s&q=%s&btnG=Search&gbv=1",
    'url_num' => 'https://%s/search?hl=%s&q=%s&btnG=Search&gbv=1&num=%u',
    'url_next' => 'https://%s/search?hl=%s&q=%s&btnG=Search&gbv=1&num=%u&start=%u',
];