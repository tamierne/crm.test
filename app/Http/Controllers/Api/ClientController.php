<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClientResourse;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    public function index()
    {
        return ClientResourse::collection(Client::paginate(10));
    }

    public function show(Client $client)
    {
        return new ClientResourse($client);
    }
}
