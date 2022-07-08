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
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Edit client</h3>
                </div>

                @include('admin.layouts.includes.messages')

            <form class="form-horizontal" method="POST" action="{{ route('clients.update', $client->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Company name</label>
                        <input type="text" class="form-control" name="name" id="title" value="{{ $client->name }}">
                    </div>
                    <div class="form-group">
                        <label for="VAT">VAT</label>
                        <input type="text" class="form-control" name="VAT" id="VAT" value="{{ $client->VAT }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{ $client->address }}">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Client's avatar</label>
                            <div class="row">
                                @foreach ($photos as $photo)
                                    <div class="m-2">
                                        <a href="{{ $photo->getUrl() }}">
                                            <img class="img-thumbnail" src="{{ $photo->getUrl('preview') }}" alt="{{ $photo->name }}" width="150px">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        <input type="file" class="form-control" name="avatar" id="avatar" placeholder="Add profile photo?">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default float-right">Cancel</a>
                </div>
            </form>
            <div class="card-body">
                <div class="card-header">
                    <h3 class="card-title">Client's projects list</h3>
                </div>
                @if (count($client->projects) == 0)
                    <h5 class="card-title m-5">This client has no projects yet</h5>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Deadline</th>
                                <th>Assigned user</th>
                                <th>Status</th>
                                <th>Available actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client->projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td><a href= {{ route('tasks.edit', $project->id) }}>{{ $project->title }}</a></td>
                                    <td>{{ Str::limit($project->description, 50, '...') }}</td>
                                    <td>{{ $project->deadline }}</td>
                                    <td>{{ $project->user->name }}</td>
                                    <td>{{ $project->status->name }}</td>
                                    <td>
                                        @can('project_edit')
                                            <a href= {{ route('projects.edit', $project->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        @endcan
                                        @can('project_delete')
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                            </form>
                                        @endcan
                                        @if($project->deleted_at)
                                            @can('project_restore')
                                                <form action="{{ route('projects.restore', $project->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>
                                                </form>
                                            @endcan
                                        @endif
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
