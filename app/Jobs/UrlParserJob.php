<?php

namespace App\Jobs;

use App\Models\ParserTask;
use App\Repositories\ParserTaskRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UrlParserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ParserTask $parser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ParserTask $parser)
    {
        $this->onQueue('urlparser');
        $this->parser = $parser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ParserTaskRepository $parserTaskRepository)
    {
        $parserTaskRepository->parseToJson($this->parser);
    }
}
