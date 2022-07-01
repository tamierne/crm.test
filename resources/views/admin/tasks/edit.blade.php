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
                    <h3 class="card-title">Edit task</h3>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            <form class="form-horizontal" method="PATCH" action="{{ route('tasks.update', $task->id) }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="{{ $task->title }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="textarea" rows="3" class="form-control" name="description" id="description">{{ $task->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input type="date" class="form-control" name="deadline" id="deadline" value="{{ $task->deadline }}">
                    </div>
                    <div class="form-group">
                        <label for="AssignedUser">Assigned user</label>
                        <select class="form-control select2 select2-hidden-accessible" name="user_id" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            @foreach ($usersList as $user)
                                <option value="{{ $user->id }}"
                                    @if ($user->id == $task->user_id) selected @endif>
                                        {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="AssignedClient">Assigned project</label>
                        <select class="form-control select2 select2-hidden-accessible" name="project_id" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            @foreach ($projectsList as $project)
                                <option value="{{ $project->id }}"
                                    @if ($project->id == $task->project_id) selected @endif>
                                        {{ $project->title }}
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
            </div>
        </div>
    </section>
</div>
@endsection
