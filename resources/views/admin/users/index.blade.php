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
                    @if(count($users) == 0)
                        <h3 class="card-title ml-5">You've no users yet... somehow</h3>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Assigned Projects</th>
                                    <th>Tasks</th>
                                    <th>Available actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    @if($user->deleted_at)
                                        <tr style="background-color: #2a2d31">
                                    @else
                                        <tr>
                                    @endif
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}
                                                @if (!@empty($user->getFirstMediaUrl('avatar')))
                                                    <img class="img-thumbnail" src="{{ $user->getFirstMediaUrl('avatar') }}" width="150px">
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    {{ $role->name }}<br>
                                                @endforeach
                                            </td>
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
                                                @if(auth()->user()->can('user_edit') || auth()->user()->id == $user->id)
                                                    <a href= {{ route('users.edit', $user->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                                @endif
                                                @if (auth()->user()->id !== $user->id)
                                                    @if($user->deleted_at)
                                                        @can('user_wipe')
                                                            <form action="{{ route('users.wipe', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Wipe</button>
                                                            </form>
                                                        @endcan
                                                        @can('user_restore')
                                                            <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>
                                                            </form>
                                                        @endcan
                                                    @else
                                                        @can('user_delete')
                                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                                            </form>
                                                        @endcan
                                                    @endif
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
