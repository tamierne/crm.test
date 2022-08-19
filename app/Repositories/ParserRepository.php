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
        $address = $request->validated('url');

        $htmlString = file_get_contents($address);
        $allMetaTags = get_meta_tags($address);

        $description = array_key_exists('description', $allMetaTags)
            ? $allMetaTags['description']
            : 'No description';

        $htmlDom = new \DOMDocument;
        @$htmlDom->loadHTML($htmlString);
        $titleNode = $htmlDom->getElementsByTagName('title');

        $title = $titleNode->item(0)->nodeValue;

        $result = [
            'title' => $title,
            'description' => $description,
            ];

        dd(json_encode($result));

        return json_encode($result);
    }

    public function store(ParserCreateRequest $request)
    {
        return Parser::create([
            'url' => $request->url,
            'user_id' => auth()->user()->id,
        ]);
    }
}
