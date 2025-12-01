<?php

namespace App\Repositories\Client;

use App\Contracts\Client\ClientRepoInterface;
use App\Models\Client\Client;

class ClientRepository implements ClientRepoInterface
{
    /**
     * Find or create a new client.
     *
     * @param array $data
     * @return Client
     */
    public function findOrCreate(array $data): Client
    {
        return Client::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name']]
        );
    }
}
