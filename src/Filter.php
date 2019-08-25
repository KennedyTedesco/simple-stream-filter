<?php

declare(strict_types=1);

namespace SimpleStreamFilter;

final class Filter
{
    private static $callbackFilterName;

    /**
     * @param resource $stream
     * @param callable $callback
     * @param int $readWrite
     * @return resource
     */
    public static function append($stream, callable $callback, int $readWrite = \STREAM_FILTER_ALL)
    {
        self::assertResource($stream);

        $resource = \stream_filter_append($stream, self::registeredFilter(), $readWrite, $callback);

        if ($resource === false) {
            throw new \RuntimeException('Unable to append the filter.');
        }

        return $resource;
    }

    /**
     * @param resource $stream
     * @param callable $callback
     * @param int $readWrite
     * @return resource
     */
    public static function prepend($stream, callable $callback, int $readWrite = \STREAM_FILTER_ALL)
    {
        self::assertResource($stream);

        $resource = \stream_filter_prepend($stream, self::registeredFilter(), $readWrite, $callback);

        if ($resource === false) {
            throw new \RuntimeException('Unable to prepend the filter.');
        }

        return $resource;
    }

    /**
     * @param resource $filter
     */
    public static function remove($filter): void
    {
        self::assertResource($filter);

        if (\stream_filter_remove($filter) === false) {
            throw new \RuntimeException('Unable to remove given filter');
        }
    }

    private static function registeredFilter(): string
    {
        if (self::$callbackFilterName === null) {
            $return = \stream_filter_register(
                self::$callbackFilterName = 'simple-stream-callback-filter',
                CallbackFilter::class
            );

            if ($return === false) {
                throw new \RuntimeException('Unable to register the filter.');
            }
        }

        return self::$callbackFilterName;
    }

    private static function assertResource($resource): void
    {
        if (!\is_resource($resource)) {
            throw new \InvalidArgumentException('Must be a valid resource.');
        }
    }
}
