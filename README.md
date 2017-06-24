# php-google
This is an easy Google Searching crawler that you can get anything you want in the page by using it.

During the process of  crawling,you need to pay attention to the limitation from google towards ip address and the warning of exception , so I suggest that you should pause running the program and own the Proxy ip

python - [MagicGoogle](https://github.com/howie6879/MagicGoogle)

### 2.How to Use?
This project can be installed via composer by requiring the `howie6879/php-google` package in `composer.json`:

``` json
{
    "require": {
        "howie6879/php-google": "1.0"
    }
}
```

If you have installed `php-google` in your project, you can get google search results that you need.

**Example**

``` php
# Add boostrap autoload file

require_once '../vendor/autoload.php';
use \howie6879\PhpGoogle\MagicGoogle;

# Or new MagicGoogle()
$magicGoogle = new MagicGoogle('http://127.0.0.1:8118');

# The first page of results
$data = $magicGoogle->search_page('python');

# Get url
$data = $magicGoogle->search_url('python');

foreach ($data as $value) {
    var_dump($value);
}

/** Output
 * string(23) "https://www.python.org/"
 * string(33) "https://www.python.org/downloads/"
 * string(35) "https://docs.python.org/3/tutorial/"
 * string(44) "https://www.python.org/about/gettingstarted/"
 * string(43) "https://wiki.python.org/moin/BeginnersGuide"
 * string(41) "https://www.python.org/downloads/windows/"
 * string(24) "https://docs.python.org/"
 * string(59) "https://en.wikipedia.org/wiki/Python_(programming_language)"
 * string(39) "https://www.codecademy.com/learn/python"
 * string(25) "https://github.com/python"
 * string(38) "https://www.tutorialspoint.com/python/"
 * string(28) "https://www.learnpython.org/"
 * string(44) "https://www.programiz.com/python-programming"
 */
 
# Get {'title','url','text'}
$data = $magicGoogle->search('python', 'en', '1');

foreach ($data as $value) {
    var_dump($value);
}

/** Output
 * array(3) {
 * ["title"]=>
 * string(21) "Welcome to Python.org"
 * ["url"]=>
 * string(23) "https://www.python.org/"
 * ["text"]=>
 * string(54) "The official home of the Python Programming Language. "
 * }
 */

```

You can see [sample.php](./examples/sample.php)

**If  you need a big amount of querie but only having an ip address,I suggest  you can have a time lapse between 5s ~ 30s.**

The reason that it always return empty might be as follows:

```html
<HTML><HEAD><meta http-equiv="content-type" content="text/html;charset=utf-8">
<TITLE>302 Moved</TITLE></HEAD><BODY>
<H1>302 Moved</H1>
The document has moved
<A HREF="https://ipv4.google.com/sorry/index?continue=https://www.google.me/s****">here</A>.
</BODY></HTML>
```
