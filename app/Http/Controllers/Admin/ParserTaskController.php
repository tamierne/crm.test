<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ParserCreateRequest;
use App\Models\ParserTask;
use App\Repositories\ParserTaskRepository;
use App\Repositories\StatusRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParserTaskController extends BaseController
{
    private StatusRepository $statusRepository;
    private ParserTaskRepository $parserTaskRepository;

    public function __construct(
        StatusRepository $statusRepository,
        ParserTaskRepository $parserTaskRepository
    )
    {
        $this->statusRepository = $statusRepository;
        $this->parserTaskRepository = $parserTaskRepository;
    }

    public function index(): View
    {
        $parsers = $this->parserTaskRepository->getAllItemsWithPaginate();
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
        $this->parserTaskRepository->store($request->validated('url'));

        return redirect()->back()->with('message', 'URL successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ParserTask  $parser
     * @return \Illuminate\Http\Response
     */
    public function show(ParserTask $parser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParserTask  $parser
     * @return \Illuminate\Http\Response
     */
    public function edit(ParserTask $parser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ParserTask  $parser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParserTask $parser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParserTask  $parser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParserTask $parser)
    {
        $this->authorize('parser_delete');

        $parser->delete();
        return redirect()->back()->with('message', 'Successfully deleted');
    }

    public function restore($id): RedirectResponse
    {
        $this->authorize('parser_restore');

        $parser = $this->parserTaskRepository->getItemById($id);

        $parser->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }

    /**
     * Force delete the specified resource
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function wipe($id): RedirectResponse
    {
        $this->authorize('parser_wipe');

        $parser = $this->parserTaskRepository->getItemById($id);

        $parser->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }

    public function forceParse()
    {
        $this->parserTaskRepository->parseAllToJson();

        return redirect()->back()->with('message', 'Done');
    }
}
