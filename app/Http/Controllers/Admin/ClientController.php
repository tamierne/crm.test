<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('client_access');

        if($request->get('status') == 'active') {
            $clients = $this->clientRepository->getActiveClientsWithPaginate();
        } else {
            $clients = $this->clientRepository->getAllItemsWithPaginate();
        }

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('client_create');

        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request)
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $this->authorize('client_edit');

        $photos = $client->getMedia('avatar');
        return view('admin.clients.edit', ['client' => $client, 'photos' => $photos]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        $client->update($request->validated());
        return redirect()->back()->with('message', 'Successfully saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $this->authorize('client_delete');

        $client->delete();
        return redirect()->back()->with('message', 'Successfully deleted');
    }
}
