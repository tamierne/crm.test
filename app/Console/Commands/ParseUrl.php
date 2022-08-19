<?php

namespace App\Console\Commands;

use App\Repositories\ParserRepository;
use Illuminate\Console\Command;

class parseUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:url {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get title and description tags by given URL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ParserRepository $parser)
    {
        $this->line($parser->parseToJson($this->argument('url')));
    }
}
