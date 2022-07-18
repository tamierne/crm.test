<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class ClientRepository extends MainRepository
{
    public function getAllItems()
    {
        return Client::all(['id', 'name']);
    }

    public function getAllItemsWithPaginate()
    {
        return Client::simplePaginate('10');
    }

    public function getItemById($id)
    {
        return Client::withTrashed()->findOrFail($id);
    }

    public function getActiveClientsWithPaginate()
    {
        return Client::activeClients()->simplePaginate('10');
    }

    public function getActiveClients()
    {
        return Client::activeClients()->get();
    }

    public function storeClient(ClientCreateRequest $request)
    {
        $client = Client::create($request->validated());

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
    }
}
