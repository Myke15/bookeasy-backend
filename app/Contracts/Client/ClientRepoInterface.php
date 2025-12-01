<?php

namespace App\Contracts\Client;

use App\Models\Client\Client;

interface ClientRepoInterface
{
    /**
     * find or create a new client.
     *
     * @param array $data
     * @return Client
     */
    public function findOrCreate(array $data): Client;

}
