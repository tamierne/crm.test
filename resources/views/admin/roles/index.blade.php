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
                            @can('role_create')
                                <a href= {{ route('roles.create') }} type="button" class="btn btn-block btn-success btn-flat">Create new role</a>
                            @endcan
                        </h3>
                        <div class="card-tools">
                            {{ $roles->links() }}
                        </div>

                        @include('admin.layouts.includes.messages')

                    </div>

                    <div class="card-body p-0">
                        @if(count($roles) == 0)
                            <h3 class="card-title ml-5">You've no roles yet... somehow</h3>
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Available actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($roles as $role)
                                    @if($role->deleted_at)
                                        <tr style="background-color: #2a2d31">
                                    @else
                                        <tr>
                                            @endif
                                            <td>{{ $role->name }}</td>
                                            <td>@foreach ($role->permissions as $permission)
                                                    {{ $permission->name }}<br>
                                                @endforeach
                                            </td>
                                            <td>@foreach ($role->users as $user)
                                                    <a href= {{ route('users.edit', $user->id) }}>{{ $user->name }}</a><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if(auth()->user()->can('role_edit') || auth()->user()->id == $user->id)
                                                    <a href= {{ route('roles.edit', $role->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                                @endif
                                                @if (auth()->user()->id !== $user->id)
                                                    @if($role->deleted_at)
                                                        @can('role_wipe')
                                                            <form action="{{ route('roles.wipe', $role->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Wipe</button>
                                                            </form>
                                                        @endcan
                                                        @can('role_restore')
                                                            <form action="{{ route('roles.restore', $role->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>
                                                            </form>
                                                        @endcan
                                                    @else
                                                        @can('role_delete')
                                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
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
