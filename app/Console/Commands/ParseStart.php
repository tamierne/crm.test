<?php

namespace App\Console\Commands;

use App\Repositories\ParserTaskRepository;
use Illuminate\Console\Command;

class ParseStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse queued URLs';

    private ParserTaskRepository $parserTaskRepository;

    public function __construct(ParserTaskRepository $parserTaskRepository)
    {
        parent::__construct();
        $this->parserTaskRepository = $parserTaskRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->parserTaskRepository->parseAllToJson();
    }
}
