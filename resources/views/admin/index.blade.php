@extends('admin.layouts.layout')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
          <h4>Welcome home, {{ auth()->user()->name }}!</h4>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Here's your tasks list</h3>

        {{-- {{ @include('admin.layouts.includes.messages') }} --}}

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        @if(count($tasks) == 0)
            <h3 class="card-title ml-5">You've no active tasks! Enjoy!</h3>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Available actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td><a href= {{ route('tasks.edit', $task->id) }}>{{ $task->title }}</a></td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->deadline }}</td>
                            <td>{{ $task->project->title }}</td>
                            <td>{{ $task->status->name }}</td>
                            <td>
                                @can('task_edit')
                                    <a href= {{ route('tasks.edit', $task->id) }} type="button" class="btn btn-block btn-success mt-1 btn-flat">Edit</a>
                                @endcan
                                @can('task_delete')
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
      <!-- /.card-body -->
      <div class="card-footer">
        Footer
      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
@endsection
