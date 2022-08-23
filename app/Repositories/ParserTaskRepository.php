<?php

namespace App\Repositories;

use App\Events\UrlParserAdded;
use App\Events\UrlParserFinished;
use App\Events\UrlParserStarted;
use App\Models\ParserTask;

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

        $parserTask->status_id = 2;
        $parserTask->started_at = now();
        $parserTask->save();

        UrlParserStarted::dispatch($parserTask);

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

//            UrlParserFinished::dispatch($parserTask);

//        } catch () {
//
//        }

        //event(finished)

        return json_encode($result);
//        return back();
    }

    public function parseAllToJson()
    {
        $tasks = $this->getAllUnparsedTasks();
        if (!$tasks->isEmpty()) {
            foreach ($tasks as $task) {
                $this->parseToJson($task);
            }
        }
        return 0;
    }

    public function store($url)
    {
        $parserTask = ParserTask::create([
            'url' => $url,
            'user_id' => auth()->user()->id,
        ]);

        UrlParserAdded::dispatch($parserTask);

        return back();
    }
}
