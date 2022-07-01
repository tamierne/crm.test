<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class ClientRepository extends MainRepository
{
    public function getAllClients()
    {
        return Client::all(['id', 'name']);
    }

    public function getAllClientsWithPaginate()
    {
        return Client::simplePaginate('10');
    }
}
