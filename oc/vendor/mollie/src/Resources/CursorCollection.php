<?php

namespace Mollie\Api\Resources;

use Mollie\Api\MollieApiClient;
abstract class CursorCollection extends \Mollie\Api\Resources\BaseCollection
{
    /**
     * @var MollieApiClient
     */
    protected $client;
    /**
     * @param MollieApiClient $client
     * @param int $count
     * @param \stdClass $_links
     */
    public final function __construct(\Mollie\Api\MollieApiClient $client, $count, $_links)
    {
        parent::__construct($count, $_links);
        $this->client = $client;
    }
    /**
     * @return BaseResource
     */
    protected abstract function createResourceObject();
    /**
     * Return the next set of resources when available
     *
     * @return CursorCollection|null
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public final function next()
    {
        if (!$this->hasNext()) {
            return null;
        }
        $result = $this->client->performHttpCallToFullUrl(\Mollie\Api\MollieApiClient::HTTP_GET, $this->_links->next->href);
        $collection = new static($this->client, $result->count, $result->_links);
        foreach ($result->_embedded->{$collection->getCollectionResourceName()} as $dataResult) {
            $collection[] = \Mollie\Api\Resources\ResourceFactory::createFromApiResult($dataResult, $this->createResourceObject());
        }
        return $collection;
    }
    /**
     * Return the previous set of resources when available
     *
     * @return CursorCollection|null
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public final function previous()
    {
        if (!$this->hasPrevious()) {
            return null;
        }
        $result = $this->client->performHttpCallToFullUrl(\Mollie\Api\MollieApiClient::HTTP_GET, $this->_links->previous->href);
        $collection = new static($this->client, $result->count, $result->_links);
        foreach ($result->_embedded->{$collection->getCollectionResourceName()} as $dataResult) {
            $collection[] = \Mollie\Api\Resources\ResourceFactory::createFromApiResult($dataResult, $this->createResourceObject());
        }
        return $collection;
    }
    /**
     * Determine whether the collection has a next page available.
     *
     * @return bool
     */
    public function hasNext()
    {
        return isset($this->_links->next->href);
    }
    /**
     * Determine whether the collection has a previous page available.
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return isset($this->_links->previous->href);
    }
}
