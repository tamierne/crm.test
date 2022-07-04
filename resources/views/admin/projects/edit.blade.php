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
                    <h3 class="card-title">Edit project</h3>
                </div>

                @include('admin.layouts.includes.messages')

                <form class="form-horizontal" method="POST" action="{{ route('projects.update', $project->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ $project->title }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea type="textarea" rows="3" class="form-control" name="description" id="description">{{ $project->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" name="deadline" id="deadline" value="{{ $project->deadline }}">
                        </div>
                        <div class="form-group">
                            <label for="AssignedUser">Assigned user</label>
                            <select class="form-control select2 select2-hidden-accessible" name="user_id" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                @foreach ($usersList as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($user->id == $project->user_id) selected @endif>
                                            {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="AssignedClient">Assigned client</label>
                            <select class="form-control select2 select2-hidden-accessible" name="client_id" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                @foreach ($clientsList as $client)
                                    <option value="{{ $client->id }}"
                                        @if ($client->id == $project->client_id) selected @endif>
                                            {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control select2 select2-hidden-accessible" name="status_id" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                @foreach ($statusList as $status)
                                    <option value="{{ $status->id }}"
                                        @if ($status->id == $project->status_id) selected @endif>
                                            {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-default float-right">Cancel</a>
                    </div>
                </form>
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="card-title">Project tasks list</h3>
                    </div>
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
                            @foreach ($project->tasks as $task)
                                <tr>
                                    <td>{{ $task->id }}</td>
                                    <td><a href= {{ route('tasks.edit', $task->id) }}>{{ $task->title }}</a></td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->deadline }}</td>
                                    <td>{{ $task->user->name }}</td>
                                    <td>{{ $task->status->name }}</td>
                                    <td>
                                        @can('project_edit')
                                            <a href= {{ route('tasks.edit', $task->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        @endcan
                                        @can('project_delete')
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                            </form>
                                        @endcan
                                        @if($task->deleted_at)
                                            @can('project_restore')
                                                <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
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
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
