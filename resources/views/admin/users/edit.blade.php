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
                    <h3 class="card-title">Edit user's profile</h3>
                </div>

                @include('admin.layouts.includes.messages')

            <form class="form-horizontal" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control select2 select2-hidden-accessible" name="role" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"  @if ($user->hasRole("$role->name")) selected @endif>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="avatar">User's avatar</label>
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
                @if(count($user->tasks) == 0)
                    <h3 class="card-title ml-5">This user has no tasks</h3>
                @else
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
                                        @can('task_edit')
                                            <a href= {{ route('tasks.edit', $task->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        @endcan
                                        @can('task_delete')
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                            </form>
                                        @endcan
                                        @if($task->deleted_at)
                                            @can('task_restore')
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
                @endif
            </div>
            </div>
        </div>
    </section>
</div>
@endsection
