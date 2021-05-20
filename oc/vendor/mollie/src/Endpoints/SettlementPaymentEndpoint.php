<?php

namespace Mollie\Api\Endpoints;

use Mollie\Api\Resources\BaseCollection;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Resources\PaymentCollection;
class SettlementPaymentEndpoint extends \Mollie\Api\Endpoints\CollectionEndpointAbstract
{
    protected $resourcePath = "settlements_payments";
    /**
     * @inheritDoc
     */
    protected function getResourceObject()
    {
        return new \Mollie\Api\Resources\Payment($this->client);
    }
    /**
     * @inheritDoc
     */
    protected function getResourceCollectionObject($count, $_links)
    {
        return new \Mollie\Api\Resources\PaymentCollection($this->client, $count, $_links);
    }
    /**
     * Retrieves a collection of Payments from Mollie.
     *
     * @param $settlementId
     * @param string $from The first payment ID you want to include in your list.
     * @param int $limit
     * @param array $parameters
     *
     * @return BaseCollection|PaymentCollection
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function pageForId($settlementId, $from = null, $limit = null, array $parameters = [])
    {
        $this->parentId = $settlementId;
        return $this->rest_list($from, $limit, $parameters);
    }
}
