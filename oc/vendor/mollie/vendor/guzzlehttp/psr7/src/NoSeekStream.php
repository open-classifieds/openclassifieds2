<?php

namespace _PhpScopereac699eb1a3f\GuzzleHttp\Psr7;

use _PhpScopereac699eb1a3f\Psr\Http\Message\StreamInterface;
/**
 * Stream decorator that prevents a stream from being seeked.
 *
 * @final
 */
class NoSeekStream implements \_PhpScopereac699eb1a3f\Psr\Http\Message\StreamInterface
{
    use StreamDecoratorTrait;
    public function seek($offset, $whence = \SEEK_SET)
    {
        throw new \RuntimeException('Cannot seek a NoSeekStream');
    }
    public function isSeekable()
    {
        return \false;
    }
}
