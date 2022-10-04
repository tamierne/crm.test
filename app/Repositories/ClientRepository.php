<?php

namespace App\Repositories;

use App\Events\Client\ClientUpdated;
use App\Http\Requests\Admin\ClientCreateRequest;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Throwable;

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
        try {
            $client = Client::create([
                'name' => $request->name,
                'VAT' => $request->VAT,
                'address' => $request->address,
                'user_id' => auth()->user()->id,
            ]);

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            ClientUpdated::dispatch($client);

        } catch (Throwable $e) {
            dump($e->getMessage());
        }

        return redirect()->back();
    }
}
