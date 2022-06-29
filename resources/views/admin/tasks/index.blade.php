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
                        <a href= {{ route('tasks.create') }} type="button" class="btn btn-block btn-success btn-flat">Create new project</a>
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
                                {{-- <th>EDIT/DELETE</th> --}}
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
                                    {{-- <td>{{ $project->status }}</td> --}}
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
