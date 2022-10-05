<?php

namespace App\Http\Controllers\Admin;

use App\Events\Client\ClientUpdated;
use App\Http\Requests\Admin\ClientIndexRequest;
use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends BaseController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Display a listing of the resource.
     * @param ClientIndexRequest $request
     * @return View
     */

    public function index(ClientIndexRequest $request): View
    {

        if($request->get('status') == 'active') {
            $clients = $this->clientRepository->getActiveClientsWithPaginate();
        } else {
            $clients = $this->clientRepository->getAllItemsWithPaginate();
        }

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param ClientCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ClientCreateRequest $request): RedirectResponse
    {
        $this->clientRepository->storeClient($request);

        return redirect()->back()->with('message', 'Client successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Client $client
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Client $client): View
    {
        $this->authorize('client_edit');

        $photos = $client->getMedia('avatar');
        return view('admin.clients.edit', [
            'client' => $client,
            'photos' => $photos,
        ]);
    }

    /**
     * Update the specified resource in storage
     * @param ClientUpdateRequest $request
     * @param Client $client
     * @return RedirectResponse
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function update(ClientUpdateRequest $request, Client $client): RedirectResponse
    {
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        $client->update($request->validated());

        return redirect()->back()->with('message', 'Successfully saved!');
    }

    /**
     * Soft delete the specified resource from storage.
     * @param Client $client
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('client_delete');

        $client->delete();

        return redirect()->back()->with('message', 'Successfully deleted');
    }

    /**
     * Restore the specified resource
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('client_restore');

        $client = $this->clientRepository->getItemById($id);

        $client->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }
}
