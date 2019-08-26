# kennedytedesco/simple-stream-filter [![Build Status](https://travis-ci.org/KennedyTedesco/simple-stream-filter.svg?branch=master)](https://travis-ci.org/KennedyTedesco/simple-stream-filter)

Painless stream filtering in PHP. The Stream Filter API it's a bit obscure. What about making a custom filter using just an anonymous function?

**Example:**

```php
<?php

use SimpleStreamFilter\Filter;

$stream = fopen('file.txt', 'rb');

Filter::append($stream, static function ($chunk = null) {
    return \strip_tags($chunk);
});

fpassthru($stream);

fclose($stream);
```

Or, if you want to:

```php
<?php

use SimpleStreamFilter\Filter;

final class StripTagsFilter
{
    public function __invoke($chunk)
    {
        return \strip_tags($chunk);
    }
}

$stream = fopen('file.txt', 'rb');

Filter::append($stream, new StripTagsFilter);

fpassthru($stream);

fclose($stream);
```

## Install

PHP 7.2 or greater required.

```bash
$ composer require kennedytedesco/simple-stream-filter:^0.2
```

## Credits

This project is like a lightweight version of the awesome [php-stream-filter](https://github.com/clue/php-stream-filter).
