<?php

namespace Tests;

use SimpleStreamFilter\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testAppend() : void
    {
        $stream = $this->createStream('<b>HELLO WORLD</b>');

        Filter::append($stream, static function ($chunk = null) {
            return \strip_tags($chunk);
        });

        $this->assertEquals('HELLO WORLD', \fgets($stream));

        \fclose($stream);
    }

    public function testPrependAfterAppend() : void
    {
        $stream = $this->createStream();

        Filter::append($stream, static function ($chunk = null) {
            return $chunk . '%';
        }, \STREAM_FILTER_WRITE);

        Filter::prepend($stream, static function ($chunk = null) {
            return '*' . $chunk;
        }, \STREAM_FILTER_WRITE);

        \fwrite($stream, 'Hello');
        \fwrite($stream, 'World');
        \rewind($stream);

        $this->assertEquals('*Hello%*World%', \stream_get_contents($stream));

        \fclose($stream);
    }

    public function testAppendMultipleFilters() : void
    {
        $stream = $this->createStream('<b>HELLO WORLD</b>');

        Filter::append($stream, static function ($chunk = null) {
            return \strip_tags($chunk);
        });

        Filter::append($stream, static function ($chunk = null) {
            return \mb_strtolower($chunk);
        });

        $this->assertEquals('hello world', \fgets($stream));

        \fclose($stream);
    }

    public function testAppendUsingClassAsCallback() : void
    {
        $stream = $this->createStream('<b>HELLO WORLD</b>');

        Filter::append($stream, new class() {
            public function __invoke($chunk)
            {
                return \strip_tags($chunk);
            }
        });

        $this->assertEquals('HELLO WORLD', \fgets($stream));

        \fclose($stream);
    }

    public function testAppendBuffer() : void
    {
        $stream = $this->createStream();

        Filter::append($stream, static function ($chunk = null) {
            return $chunk . ' | ';
        }, \STREAM_FILTER_WRITE);

        $buffered = '';
        Filter::append($stream, static function ($chunk) use (&$buffered) {
            $buffered .= $chunk;
        }, \STREAM_FILTER_WRITE);

        \fwrite($stream, 'foo');
        \fwrite($stream, 'bar');
        \fwrite($stream, 'baz');
        \rewind($stream);

        $this->assertEquals('foo | bar | baz | ', $buffered);
        $this->assertEquals($buffered, \fgets($stream));

        \fclose($stream);
    }

    public function testRemoveFilter() : void
    {
        $stream = $this->createStream();

        $filter = Filter::append($stream, static function ($chunk) {
            return \mb_strtoupper($chunk);
        }, \STREAM_FILTER_WRITE);

        \fwrite($stream, 'hello ');

        Filter::remove($filter);

        \fwrite($stream, 'world');

        \rewind($stream);

        $this->assertEquals('HELLO world', \stream_get_contents($stream));

        \fclose($stream);
    }

    private function createStream(string $contents = null)
    {
        $stream = \fopen('php://memory', 'rw+b');

        if ($contents) {
            \fwrite($stream, $contents);
            \rewind($stream);
        }

        return $stream;
    }
}
