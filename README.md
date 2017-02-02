
# PHP Request

It includes some functions to easy requesting and parsing data.


### Request::get

#### Examples

```PHP
use \phprequest\Request;

print_r(  Request::get('https://github.com/')  );
print_r(  Request::get('http://www.cbr-xml-daily.ru/daily_json.js', ['format' => 'json'])  );
```

#### Syntax

`mixed \phprequest\Request::get(string $url, array $options = [])`

List of curl options:
 
| Name | Type | Default | Description |
|---|---|---|---|
| follow | boolean | `false` | Follow HTTP redirections (see *CURLOPT_FOLLOWLOCATION*) |
| encoding | string |  | The contents of the "Accept-Encoding: " header (see *CURLOPT_ENCODING*) |
| timeout | integer | `300` | The timeout of one request (see *CURLOPT_TIMEOUT*) |
| cookie | string |  | The contents of the "Cookie: " header (see *CURLOPT_COOKIE*) |
| headers | array |  | An array of HTTP headers (see *CURLOPT_HTTPHEADER*) |
| referer | string |  | The contents of the "Referer: " header (see *CURLOPT_REFERER*) |

List of special options:

| Name | Type | Default | Description |
|---|---|---|---|
| allowed_codes | array | `[200]` | Allowed HTTP codes |
| allow_empty | boolean | `false` | Allows empty body of the HTTP response |
| format | string, callable |  | The way to prepare body: 'json', 'json_assoc', 'xml', callable (args: `$body`, `$header`) |
| charset | string |  | The charset of requested content (the result will contain 'utf8') |
| attempts | integer | `5` | The number of request attempts |

#### Requirements

- PHP version 5.4.0 or higher
- PHP extension `ext-curl` enabled

#### Installation 

##### Using Composer

Get the package:
```
$ composer require masterklavi/phprequest
```

Include `vendor/autoload.php`:
```PHP
include 'vendor/autoload.php';
use \phprequest\Request;

$data = Request::get('http://www.cbr-xml-daily.ru/daily_json.js', ['format' => 'json']);
echo 'USD: ', $data->Valute->USD->Value, PHP_EOL;
```

#### Manual Installation

Clone git repository:
```
$ git clone https://github.com/masterklavi/phprequest.git
```
or download the package from https://github.com/masterklavi/phprequest/archive/master.zip

Include `autoload.php`:
```PHP
<?php
include 'autoload.php';
use \phprequest\Request;

$data = Request::get('http://www.cbr-xml-daily.ru/daily_json.js', ['format' => 'json']);
echo 'USD: ', $data->Valute->USD->Value, PHP_EOL;
```
