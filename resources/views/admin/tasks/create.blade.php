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
                    <h3 class="card-title">Create task</h3>
                </div>
            <form class="form-horizontal">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="textarea" rows="3" class="form-control" id="description" placeholder="Enter description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input type="date" class="form-control" id="deadline" placeholder="Select deadline">
                    </div>
                    <div class="form-group">
                        <label for="AssignedUser">Assigned user</label>
                        <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            @foreach ($usersList as $user)
                                <option value="{{ $user->name }}">
                                    {{-- @if ($user->name == $user->name) selected @endif> --}}
                                        {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="AssignedClient">Assigned client</label>
                        <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            @foreach ($projectsList as $project)
                                <option value="{{ $project->title }}">
                                    {{-- @if ($client->name == $item->category_id) selected @endif> --}}
                                        {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Create</button>
                        <a href="{{ url()->previous() }}" type="submit" class="btn btn-default float-right">Cancel</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </section>
</div>
@endsection
