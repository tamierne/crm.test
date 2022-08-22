<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ParserCreateRequest;
use App\Models\ParserTask;
use Illuminate\Http\Request;

class ParserTaskRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return ParserTask::all();
    }

    public function getAllItemsWithPaginate()
    {
        return ParserTask::with([
            'user:id,name',
            'status:id,name',
        ])
            ->withTrashed()
            ->simplePaginate('10');
    }

    public function getItemById(int $id)
    {
        return ParserTask::withTrashed()->findOrFail($id);
    }

    public function getAllUnparsedTasks()
    {
        return ParserTask::where('status_id', '1')->get();
    }

    public function parseToJson(ParserTask $parserTask)
    {
        //event (started)
        $parserTask->status_id = 2;
        $parserTask->started_at = now();

//        try {
            $htmlString = file_get_contents($parserTask->url);
            $allMetaTags = get_meta_tags($parserTask->url);

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

            $parserTask->result = $result;
            $parserTask->finished_at = now();

            $parserTask->status_id = 4;

            $parserTask->save();

//        } catch () {
//
//        }

        //event(finished)

        return json_encode($result);
    }

    public function store($url)
    {
        return ParserTask::create([
            'url' => $url,
            'user_id' => auth()->user()->id,
        ]);

        //event(added to queue)
    }
}
