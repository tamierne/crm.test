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
                    <h3 class="card-title">Create client</h3>
                </div>

                @include('admin.layouts.includes.messages')

            <form class="form-horizontal" method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
                    </div>
                        {{-- <div class="card-header">
                            <h3 class="card-title">Change password?</h3>
                        </div>
                        <div class="form-group">
                            <label for="old_password">Old password</label>
                            <input type="password" class="form-control" name="old_password" id="old_password">
                        </div>
                        <div class="form-group">
                            <label for="new_password">New password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password">
                        </div> --}}
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default float-right">Cancel</a>
                </div>
            </form>
            <div class="card-body">
                <div class="card-header">
                    <h3 class="card-title">Users tasks list</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Available actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td><a href= {{ route('tasks.edit', $task->id) }}>{{ $task->title }}</a></td>
                                <td>{{ Str::limit($task->description, 50, '...') }}</td>
                                <td>{{ $task->deadline }}</td>
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
