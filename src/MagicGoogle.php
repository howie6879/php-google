<?php
/**
 * Author: howie
 * CreateTime: 23/06/2017 21:41
 * Description: Google search results crawler, get google search results that you need.
 */

namespace howie6879\PhpGoogle;

use Exception;
use Sunra\PhpSimple\HtmlDomParser;

class MagicGoogle
{
    function __construct($proxy = '')
    {
        $this->config = require __DIR__ . '/config/config.php';
        $this->proxy = $proxy;
    }

    /**
     * Get the results you want,such as title,description,url
     * @param $query
     * @param string $language
     * @param null $num
     * @param int $start
     * @param int $pause
     * @return \Generator
     */
    function search($query, $language = 'en', $num = null, $start = 0, $pause = 2)
    {
        $data = $this->search_page($query, $language, $num, $start, $pause);
        $html = $this->parse_html($data);
        foreach ($html->find('div.g') as $element)
            yield [
                'title' => $element->find('h3.r>a', 0)->plaintext,
                'url' => $this->filter_href($element->find('h3.r>a', 0)->href),
                'text' => $element->find('span.st', 0)->plaintext
            ];
    }

    /**
     * Get the urls
     * @param $query
     * @param string $language
     * @param null $num
     * @param int $start
     * @param int $pause
     * @return \Generator
     */
    function search_url($query, $language = 'en', $num = null, $start = 0, $pause = 2)
    {
        $data = $this->search_page($query, $language, $num, $start, $pause);
        $html = $this->parse_html($data);
        foreach ($html->find('h3.r') as $element) {
            yield $this->filter_href($element->find('a', 0)->href);
        }
    }

    /**
     * Get the first page of results
     *
     * @param $query
     * @param string $language
     * @param null $num
     * @param int $start
     * @param int $pause
     * @return mixed
     */
    function search_page($query, $language = 'en', $num = null, $start = 0, $pause = 2)
    {
        $domain = $this->get_random_domain();
        $user_agent = $this->get_random_user_agent();
        $query = urlencode($query);
        if (empty($num)) {
            $url = sprintf($this->config['url_search'], $domain, $language, $query);
        } else {
            $url = sprintf($this->config['url_num'], $domain, $language, $query, $num);
        }
        sleep($pause);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $data = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $data = '';
            echo $e->getMessage();
        }
        return $data;
    }

    /**
     * Returns None if the link doesn't yield a valid result
     * Token from https://github.com/MarioVilas/google
     * @param $href
     * @return string
     */
    function filter_href($href)
    {
        if (!empty($href)) {
            try {
                $o = parse_url($href);
                if ($o['host']) {
                    return $href;
                }
                if (strpos($href, "/url?") === 0) {
                    parse_str($o['query'], $link);
                    $href = $link['q'];
                    return $href;
                }
            } catch (Exception $e) {
                $href = '';
                echo $e->getMessage();
            }
        }
        return $href;
    }

    /**
     *Parse HTML by simple_html_dom
     *https://github.com/sunra/php-simple-html-dom-parser
     *
     * @param string $html
     * @return \simplehtmldom_1_5\simple_html_dom
     */
    function parse_html($html)
    {
        return HtmlDomParser::str_get_html($html);
    }

    /**
     * Get a random user agent string
     *
     * @return mixed
     */
    function get_random_user_agent()
    {
        return $this->get_data('user_agents.txt', $this->config['user_agent']);
    }

    /**
     * Get a random domain string
     *
     * @return mixed
     */
    function get_random_domain()
    {
        return $this->get_data('all_domain.txt', $this->config['domain']);
    }

    /**
     * Get data from a file
     *
     * @param $filename
     * @param $default
     * @return mixed
     */
    function get_data($filename, $default)
    {
        $path = __DIR__ . '/data/' . $filename;
        $data = [];
        try {
            $file = fopen($path, "r");
            while (!feof($file)) {
                $data[] = fgets($file);
            }
            fclose($file);
            $result = $data[array_rand($data)];
        } catch (Exception $e) {
            $result = $default;
            echo $e->getMessage();
        }
        return str_replace(array("\r\n", "\r", "\n"), "", $result);
    }
}