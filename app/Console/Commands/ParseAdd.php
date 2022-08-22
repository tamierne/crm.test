<?php

namespace App\Console\Commands;

use App\Repositories\ParserTaskRepository;
use Illuminate\Console\Command;

class ParseAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:add {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add URL to queue to get title and description tags by given URL';

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
        return $this->parserTaskRepository->store($this->argument('url'));
    }
}
