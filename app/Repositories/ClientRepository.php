<?php

namespace App\Repositories;

use App\Events\Client\ClientUpdated;
use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;

class ClientRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Client::all(['id', 'name']);
    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        return Client::with([
            'projects:id,title,client_id',
            'media',
            ])
            ->withTrashed()
            ->simplePaginate('10');
    }

    /**
     * @param $id
     * @return Client
     */
    public function getItemById($id): Client
    {
        return Client::withTrashed()->findOrFail($id);
    }

    /**
     * @return Paginator
     */
    public function getActiveClientsWithPaginate(): Paginator
    {
        return Client::activeClients()->simplePaginate('10');
    }

    /**
     * @return Collection
     */
    public function getActiveClients(): Collection
    {
        return Client::activeClients()->get();
    }

    /**
     * @param ClientCreateRequest $request
     * @return RedirectResponse
     */
    public function storeClient(ClientCreateRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        ClientUpdated::dispatch($client);

        return redirect()->back();
    }
}
