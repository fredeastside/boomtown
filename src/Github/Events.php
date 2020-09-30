<?php

declare(strict_types=1);

namespace Boomtown\Github;

use Github\Api\AbstractApi;

class Events extends AbstractApi
{
    public function all($organization)
    {
        return $this->get('/orgs/'.rawurlencode($organization).'/events');
    }
}