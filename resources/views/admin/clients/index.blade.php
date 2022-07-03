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
                        @if(request()->get('status') == 'active')
                            <a href= {{ route('clients.index') }} type="button" class="btn btn-block btn-info btn-flat">View all clients</a>
                        @else
                            <a href= {{ route('clients.index', ['status' => 'active']) }} type="button" class="btn btn-block btn-info btn-flat">View active clients</a>
                        @endif
                    </h3>
                        <div class="card-tools">
                            {{ $clients->links() }}
                        </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
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
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->VAT }}</td>
                                    <td>{{ $client->address }}</td>
                                    <td>@foreach ($client->projects as $project)
                                        <a href= {{ route('projects.edit', $project->id) }}>{{ $project->title }}</a><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('client_edit')
                                            <a href= {{ route('clients.edit', $client->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        @endcan
                                        @can('client_delete')
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
