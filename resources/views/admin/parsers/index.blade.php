@extends('admin.layouts.layout')

@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">

</div>
</section>
<!-- /.container-fluid -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('parsers.store') }}" method="POST">
                        @csrf
                        <h3 class="row">
                            <div>
                                <button type="submit" class="btn btn-block btn-success btn-flat">Parse URL</button>
                            </div>
                            <div class="col">
                                <input type="text" name="url" class="form-control" id="parserCreate" placeholder="Enter URL to parse">
                            </div>
                        </h3>
                    </form>
                        <div class="card-tools">
                            {{ $parsers->links() }}
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href= {{ route('parsers.index') }} type="button" class="btn btn-block btn-info btn-flat">View all</a>
                            </div>
                            @foreach ($statusList as $status)
                                <div class="col">
                                    <a href= {{ route('parsers.index', ['status' => $status->name]) }} type="button" class="btn btn-block btn-info btn-flat">View all {{ $status->name }} URLs</a>
                                </div>
                            @endforeach
                            <div class="col">
                                <a href= {{ route('parsers.index', ['filter' => 'Deleted']) }} type="button" class="btn btn-block btn-info btn-flat">View all deleted URLs</a>
                            </div>
                        </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if (count($parsers) == 0)
                        <h5 class="card-title m-5">You have no parsed URLs yet</h5>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Added by</th>
                                    <th>URL</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                    <th>Date added</th>
                                    <th>Date finished</th>
                                    <th>Available action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parsers as $parser)
                                    <tr>
                                        <td>{{ $parser->user->name }}</td>
                                        <td>{{ $parser->url }}</td>
                                        <td>{{ $parser->result }}</td>
                                        <td>{{ $parser->status->name }}</td>
                                        <td>{{ $parser->created_at }}</td>
                                        <td>{{ $parser->updated_at }}</td>
                                        <td>
                                            @role('super-admin')
                                                <form action="{{ route('parsers.destroy', $parser->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                                </form>
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
