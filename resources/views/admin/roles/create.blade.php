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
                    <h3 class="card-title">Create role</h3>
                </div>

                @include('admin.layouts.includes.messages')

            <form class="form-horizontal" method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Role name</label>
                        <input type="text" class="form-control" name="name" id="title" placeholder="Enter title">
                    </div>
                    <label for="permissions">Select Permissions</label>
                    <select name="permissions[]" id="permissions" multiple class="form-control">
                        @foreach($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->id }} {{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Create</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default float-right">Cancel</a>
                </div>
            </form>
            </div>
        </div>
    </section>
</div>
@endsection
