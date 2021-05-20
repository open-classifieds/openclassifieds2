<?php

namespace Mollie\Api\Resources;

class MethodCollection extends \Mollie\Api\Resources\BaseCollection
{
    /**
     * @return string
     */
    public function getCollectionResourceName()
    {
        return "methods";
    }
}
