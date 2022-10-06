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
                    <h3 class="card-title">Edit role</h3>
                </div>

                @include('admin.layouts.includes.messages')

            <form class="form-horizontal" method="POST" action="{{ route('roles.update', $role->id) }}">
                @method('PATCH')
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Role name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $role->name }}" disabled>
                    </div>
                    <label>Role permissions</label>
                    @foreach($permissions as $permission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="{{ $permission->name }}" name="permissions[]" value="{{ $permission->name }}"
                               @foreach($role->permissions as $rolePermission)
                                    @if ($rolePermission->id == $permission->id) checked @endif
                                @endforeach
                                />
                        <label for="{{ $permission->name }}" class="form-check-label">{{ $permission->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-default float-right">Cancel</a>
                </div>
            </form>
            </div>
        </div>
    </section>
</div>
@endsection
