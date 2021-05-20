<?php

namespace Mollie\Api\Resources;

class ShipmentCollection extends \Mollie\Api\Resources\BaseCollection
{
    /**
     * @return string
     */
    public function getCollectionResourceName()
    {
        return 'shipments';
    }
}
