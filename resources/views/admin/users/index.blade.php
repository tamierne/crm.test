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
                        @can('user_create')
                            <a href= {{ route('users.create') }} type="button" class="btn btn-block btn-success btn-flat">Create new user</a>
                        @endcan
                    </h3>
                    <div class="card-tools">
                        {{ $users->links() }}
                    </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Assigned Projects</th>
                                <th>Tasks</th>
                                <th>Available actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>@foreach ($user->projects as $project)
                                        <a href= {{ route('projects.edit', $project->id) }}>{{ $project->title }}</a><br>
                                        @endforeach
                                    </td>
                                    <td>@foreach ($user->tasks as $task)
                                        <a href= {{ route('tasks.edit', $task->id) }}>{{ $task->title }}</a><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('user_edit')
                                            <a href= {{ route('users.edit', $user->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        @endcan
                                        @can('user_delete')
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
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
