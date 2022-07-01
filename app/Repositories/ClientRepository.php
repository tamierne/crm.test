<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
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

    public function storeClient(ClientCreateRequest $request)
    {
        Client::create([
            'name' => $request->name,
            'VAT' => $request->VAT,
            'address' => $request->address,
        ]);
    }
}
