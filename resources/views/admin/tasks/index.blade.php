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
                        @can('task_create')
                            <a href= {{ route('tasks.create') }} type="button" class="btn btn-block btn-success btn-flat">Add new task</a>
                        @endcan
                    </h3>
                        <div class="card-tools">
                            {{ $tasks->links() }}
                        </div>
                        <div class="row">
                                <div class="col">
                                    <a href= {{ route('tasks.index') }} type="button" class="btn btn-block btn-info btn-flat">View all tasks</a>
                                </div>
                            @foreach ($statusList as $status)
                                <div class="col">
                                    <a href= {{ route('tasks.index', ['status' => $status->name]) }} type="button" class="btn btn-block btn-info btn-flat">View all {{ $status->name }} tasks</a>
                                </div>
                            @endforeach
                                <div class="col">
                                    <a href= {{ route('tasks.index', ['filter' => 'Deleted']) }} type="button" class="btn btn-block btn-info btn-flat">View all deleted tasks</a>
                                </div>
                        </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if (count($tasks) === 0)
                        <h5 class="card-title m-5">There're no tasks</h5>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Deadline</th>
                                    <th>Project</th>
                                    <th>Assigned user</th>
                                    <th>Status</th>
                                    <th>Available actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $task->id }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ Str::limit($task->description, 50, '...') }}</td>
                                        <td>{{ $task->deadline_parsed }}</td>
                                        <td>{{ $task->project->title }}</td>
                                        <td>{{ $task->user->name }}</td>
                                        <td>{{ $task->status->name }}</td>
                                        <td>
                                            @can('task_edit')
                                                <a href= {{ route('tasks.edit', $task->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                            @endcan
                                            @if($task->deleted_at)
                                                @can('task_wipe')
                                                    <form action="{{ route('tasks.wipe', $task->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Wipe</button>
                                                    </form>
                                                @endcan
                                                @can('task_restore')
                                                    <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>
                                                    </form>
                                                @endcan
                                            @else
                                                @can('task_delete')
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
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
