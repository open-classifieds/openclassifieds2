<?php

namespace _PhpScopereac699eb1a3f\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScopereac699eb1a3f\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
