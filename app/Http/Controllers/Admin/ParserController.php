<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ParserCreateRequest;
use App\Models\Parser;
use App\Repositories\ParserRepository;
use App\Repositories\StatusRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParserController extends BaseController
{
    private StatusRepository $statusRepository;
    private ParserRepository $parserRepository;

    public function __construct(StatusRepository $statusRepository, ParserRepository $parserRepository)
    {
        $this->statusRepository = $statusRepository;
        $this->parserRepository = $parserRepository;
    }

    public function index(): View
    {
        $parsers = $this->parserRepository->getAllItemsWithPaginate();
        return view('admin.parsers.index', [
            'statusList' => $this->statusRepository->getAllItems(),
            'parsers' => $parsers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(ParserCreateRequest $request)
    {
        $this->parserRepository->store($request);
//        $this->parserRepository->parse($request);

        //event(notification)
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parser  $parser
     * @return \Illuminate\Http\Response
     */
    public function show(Parser $parser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parser  $parser
     * @return \Illuminate\Http\Response
     */
    public function edit(Parser $parser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parser  $parser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parser $parser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parser  $parser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parser $parser)
    {
        //
    }
}
