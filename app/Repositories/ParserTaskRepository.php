<?php

namespace App\Repositories;

use App\Events\UrlParser\UrlParserAdded;
use App\Events\UrlParser\UrlParserFinished;
use App\Events\UrlParser\UrlParserStarted;
use App\Jobs\UrlParserJob;
use App\Models\ParserTask;
use App\Models\Status;
use Throwable;

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
            ->orderbyDesc('created_at')
            ->simplePaginate('10');
    }

    public function getItemById(int $id)
    {
        return ParserTask::withTrashed()->findOrFail($id);
    }

    public function getAllUnparsedTasks()
    {
        return ParserTask::where('status_id', Status::STATUS_QUEUED)->get();
    }

    public function parseToJson(ParserTask $parserTask)
    {

        $parserTask->update([
            'status_id' => Status::STATUS_PROCESSING,
            'started_at' => now(),
        ]);

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

            $parserTask->update([
                'status_id' => Status::STATUS_COMPLETED,
                'result' => $result,
                ]);

            UrlParserFinished::dispatch($parserTask);

//        } catch () {
//
//        }

        return json_encode($result);
    }

    public function parseAllToJson()
    {
        $tasks = $this->getAllUnparsedTasks();
        if (!$tasks->isEmpty()) {
            foreach ($tasks as $task) {
                $this->parseToJson($task);
            }
        }
        return true;
    }

    public function store($url)
    {
        try {
            $parserTask = ParserTask::create([
                'url' => $url,
                'user_id' => auth()->user()->id,
            ]);

            UrlParserJob::dispatch($parserTask);
            UrlParserAdded::dispatch($parserTask);
        } catch (Throwable $e) {
            dump($e->getMessage());
        }

        return back();
    }
}
