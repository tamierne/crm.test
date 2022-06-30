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
                        <a href= {{ route('projects.create') }} type="button" class="btn btn-block btn-success btn-flat">Create new project</a>
                    </h3>
                        <div class="card-tools">
                            {{ $projects->links() }}
                                {{-- <ul class="pagination pagination-sm float-right">
                                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                                </ul> --}}
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
                                <th>Client</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Available actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->description }}</td>
                                    <td>{{ $project->deadline }}</td>
                                    <td>{{ $project->client->name }}</td>
                                    <td>{{ $project->user->name }}</td>
                                    <td>{{ $project->status->name }}</td>
                                    <td>
                                        <a href= {{ route('projects.edit', $project->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>
                                        <a href= {{ route('projects.destroy', $project->id) }} type="button" class="btn btn-block btn-danger btn-flat">Delete</a>
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
