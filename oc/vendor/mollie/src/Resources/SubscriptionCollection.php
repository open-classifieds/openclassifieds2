<?php

namespace Mollie\Api\Resources;

class SubscriptionCollection extends \Mollie\Api\Resources\CursorCollection
{
    /**
     * @return string
     */
    public function getCollectionResourceName()
    {
        return "subscriptions";
    }
    /**
     * @return BaseResource
     */
    protected function createResourceObject()
    {
        return new \Mollie\Api\Resources\Subscription($this->client);
    }
}
