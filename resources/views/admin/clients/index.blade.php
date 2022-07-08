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
                    <h3 class="card-title">
                        @can('client_create')
                            <a href= {{ route('clients.create') }} type="button" class="btn btn-block btn-success btn-flat">Add new client</a>
                        @endcan
                    </h3>
                        <div class="card-tools">
                            {{ $clients->links() }}
                        </div>
                        <div class="row">
                            <div class="col">
                                @if(request()->get('status') == 'active')
                                    <a href= {{ route('clients.index') }} type="button" class="btn btn-block btn-info btn-flat">View all clients</a>
                                @else
                                    <a href= {{ route('clients.index', ['status' => 'active']) }} type="button" class="btn btn-block btn-info btn-flat">View active clients</a>
                                @endif
                            </div>
                        </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if (count($clients) == 0)
                        <h5 class="card-title m-5">You have no clients yet</h5>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>VAT</th>
                                    <th>Address</th>
                                    <th>Projects</th>
                                    <th>Available actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $client->name }}
                                            @if (!@empty($client->getFirstMediaUrl('avatar')))
                                                <img class="img-thumbnail" src="{{ $client->getFirstMediaUrl('avatar') }}" width="150px">
                                            @endif
                                        </td>
                                        <td>{{ $client->VAT }}</td>
                                        <td>{{ $client->address }}</td>
                                        <td>@foreach ($client->projects as $project)
                                            <a href= {{ route('projects.edit', $project->id) }}>{{ $project->title }}</a><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('client_edit')
                                                <a href= {{ route('clients.edit', $client->id) }} type="button" class="btn btn-block btn-success mt-1 btn-flat">Edit</a>
                                            @endcan
                                            @can('client_delete')
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                                </form>
                                            @endcan
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
