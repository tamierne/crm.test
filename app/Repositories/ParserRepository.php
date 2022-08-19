<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ParserCreateRequest;
use App\Models\Parser;
use Illuminate\Http\Request;

class ParserRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Parser::all();
    }

    public function getAllItemsWithPaginate()
    {
        return Parser::with([
            'user:id,name',
            'status:id,name',
        ])
            ->withTrashed()
            ->simplePaginate('10');
    }

    public function getItemById(int $id): Parser
    {
        return Parser::with([
            'user:id,name',
        ])
            ->findOrFail($id);
    }

    public function parse(ParserCreateRequest $request)
    {
        $htmlString = file_get_contents($request->validated());
    }

    public function store(ParserCreateRequest $request)
    {
        return Parser::create([
            'url' => $request->url,
            'user_id' => auth()->user()->id,
        ]);
    }
}
