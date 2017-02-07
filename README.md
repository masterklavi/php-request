
# PHP Request

It includes some functions to easy requesting and parsing data.

## Examples

```PHP
use \phprequest\Request;

print Request::request('https://api.ipify.org/'); // prints your IP address received from https://api.ipify.org/
print PHP_EOL;

print Request::get('https://api.ipify.org?format=json', ['filter' => 'json'])->ip, PHP_EOL;
print Request::get('https://api.ipify.org?format=json', ['filter' => 'json_assoc'])['ip'], PHP_EOL;

// Request::post('http://example.com', ['data' => ['name' => 'John']]);

// Request::multi(['http://example.com', 'http://example.com/1', 'http://example.com/2'], ['concurrency' => 2]);
// Request::multiPost(['http://example.com', ['http://example.com/1', ['filter' => 'json']], 'http://example.com/2'], ['concurrency' => 2]);

```

## Requirements

- PHP version 5.4.0 or higher
- PHP extension `ext-curl` enabled

## Installation 

### Using Composer

Get the package:
```
$ composer require masterklavi/phprequest
```

Include `vendor/autoload.php`:
```PHP
include 'vendor/autoload.php';
use \phprequest\Request;

$data = Request::get('http://www.cbr-xml-daily.ru/daily_json.js', ['filter' => 'json']);
echo 'USD: ', $data->Valute->USD->Value, PHP_EOL;
```

### Manual Installation

Clone git repository:
```
$ git clone https://github.com/masterklavi/phprequest.git
```
or download the package at https://github.com/masterklavi/phprequest/archive/master.zip

Include `autoload.php`:
```PHP
<?php
include 'autoload.php';
use \phprequest\Request;

$data = Request::get('http://www.cbr-xml-daily.ru/daily_json.js', ['filter' => 'json']);
echo 'USD: ', $data->Valute->USD->Value, PHP_EOL;
```

## Methods

### Request::request
Requests the given URL.

#### Syntax
```PHP
mixed Request::request(string $url, array $options = [])
```
##### Parameters
`$url` – URL address  
`$options` – Array of [request options](#request-options)  
##### Return Values
Returns a response (body) as `string` by default. 
Returns a `mixed` value when the `filter` option's set. 
Returns `false` on failure.

### Request::get
Alias of [`Request::request()`](#requestrequest)

### Request::post
Equivalent of [`Request::request($url, ['method' => 'POST'])`](#requestrequest)

### Request::multi
Requests the given URLs (parallel requests using curl multi).

#### Syntax
```PHP
mixed Request::multi(string $urls, array $options = [])
```
##### Parameters
`$url` – Array of URL address strings or arrays of URL addresses and their options (e.g. `[ 'http://a.ru', ['http://b.ru', ['filter' => 'json']] ]`)  
`$options` – Array of [request options](#request-options)  
##### Return Values
Returns an `array` of results for the given urls.
Result may contain: 
- a response (body) as `string` by default
- a `mixed` value when the `filter` option's set 
- `false` on failure

### Request::multiGet
Alias of [`Request::multi()`](#requestmulti)

### Request::multiPost
Equivalent of [`Request::multi($urls, ['method' => 'POST'])`](#requestmulti)

## Request Options

List of curl options:
 
| Name | Type | Default | Description |
|---|---|---|---|
| method | string | `GET` | The method of the HTTP request (GET, POST, HEAD, PUT, DELETE) (see *CURLOPT_POST*, *CURLOPT_CUSTOMREQUEST*) |
| data | string, array |  | Querystring for GET, HEAD and DELETE requests, or request body for others (see *CURLOPT_POSTFIELDS*) |
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
| filter | string, callable |  | The way to prepare body: 'json', 'json_assoc', 'xml', callable (args: `$body`, `$header`) |
| charset | string |  | The charset of requested content (the result will contain 'utf8') |
| attempts | integer | `5` | Number of request attempts |
| concurrency | integer | `10` | Concurrency of requests in `Request::multi()` |

