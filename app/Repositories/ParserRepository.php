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

    public function getItemById(int $id)
    {
        return Parser::withTrashed()->findOrFail($id);
    }

    public function parseToJson($url)
    {

        $htmlString = file_get_contents($url);
        $allMetaTags = get_meta_tags($url);

        $description = array_key_exists('description', $allMetaTags)
            ? $allMetaTags['description']
            : 'No description';

        $htmlDom = new \DOMDocument;
        @$htmlDom->loadHTML($htmlString);
        $titleNode = $htmlDom->getElementsByTagName('title');

        $title = is_null($titleNode->item(0)->nodeValue)
            ? 'No title'
            : $titleNode->item(0)->nodeValue;

        $result = [
            'title' => $title,
            'description' => $description,
            ];

        return json_encode($result);
    }

    public function store($url)
    {
        return Parser::create([
            'url' => $url,
            'user_id' => auth()->user()->id,
            'result' => $this->parseToJson($url),
        ]);
    }
}
