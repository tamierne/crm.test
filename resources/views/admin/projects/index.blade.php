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
                        @can('project_create')
                        <a href= {{ route('projects.create') }} type="button" class="btn btn-block btn-success btn-flat">Create new project</a>
                        @endcan
                    </h3>
                        <div class="card-tools">
                            {{ $projects->links() }}
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href= {{ route('projects.index') }} type="button" class="btn btn-block btn-info btn-flat">View all projects</a>
                            </div>
                        @foreach ($statusList as $status)
                            <div class="col">
                                <a href= {{ route('projects.index', ['status' => $status->name]) }} type="button" class="btn btn-block btn-info btn-flat">View all {{ $status->name }} projects</a>
                            </div>
                        @endforeach
                            <div class="col">
                                <a href= {{ route('projects.index', ['filter' => 'Deleted']) }} type="button" class="btn btn-block btn-info btn-flat">View all deleted projects</a>
                            </div>
                        </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if (count($projects) == 0)
                        <h5 class="card-title m-5">There's no projects</h5>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Deadline</th>
                                    <th>Client</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Available actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ Str::limit($project->description, 50, '...') }}</td>
                                        <td>{{ $project->deadline }}</td>
                                        <td>{{ $project->client->name }}</td>
                                        <td>{{ $project->user->name }}</td>
                                        <td>{{ $project->status->name }}</td>
                                        <td>
                                            @can('project_edit')
                                                <a href= {{ route('projects.edit', $project->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                            @endcan
                                            @if($project->deleted_at)
                                                @can('project_wipe')
                                                    <form action="{{ route('projects.wipe', $project->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Wipe</button>
                                                    </form>
                                                @endcan
                                                @can('project_restore')
                                                    <form action="{{ route('projects.restore', $project->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('project_delete')
                                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" @if(count($project->tasks) != 0) onclick="alert('This project has unfinished tasks!')" @endif class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
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
