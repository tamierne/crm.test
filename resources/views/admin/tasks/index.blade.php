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
                        <a href= {{ route('tasks.create') }} type="button" class="btn btn-block btn-success btn-flat">Add new task</a>
                    </h3>
                        <div class="card-tools">
                            {{ $tasks->links() }}
                        </div>
                </div>

                <div class="card-body p-0">
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
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->deadline }}</td>
                                    <td>{{ $task->project->title }}</td>
                                    <td>{{ $task->user->name }}</td>
                                    <td>{{ $task->status->name }}</td>
                                    <td>
                                        <a href= {{ route('tasks.edit', $task->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        <form action="{{ route('tasks.destroy', $project->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button>
                                        </form>
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
