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
                        Recent activity
                    </h3>
                    <div class="card-tools">
                        {{ $activities->links() }}
                    </div>

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if(count($activities) == 0)
                        <h3 class="card-title ml-5">You've no activities yet... somehow</h3>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Event</th>
                                    <th>Launched by</th>
                                    <th>Properties</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $activity)
                                        <tr>
                                            <td>{{ $activity->log_name }}</td>
                                            <td>{{ $activity->description }}</td>
                                            <td>{{ $activity->causer->name }}</td>
                                            <td>{{ $activity->properties }}</td>
                                            <td>{{ $activity->created_at }}</td>
                                            <td>{{ $activity->updated_at }}</td>
{{--                                            <td>--}}
{{--                                                @if(auth()->user()->can('user_edit') || auth()->user()->id == $user->id)--}}
{{--                                                    <a href= {{ route('users.edit', $user->id) }} type="button" class="btn btn-block btn-success btn-flat">Edit</a>--}}
{{--                                                @endif--}}
{{--                                                @if (auth()->user()->id !== $user->id)--}}
{{--                                                    @if($user->deleted_at)--}}
{{--                                                        @can('user_wipe')--}}
{{--                                                            <form action="{{ route('users.wipe', $user->id) }}" method="POST">--}}
{{--                                                                @csrf--}}
{{--                                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Wipe</button>--}}
{{--                                                            </form>--}}
{{--                                                        @endcan--}}
{{--                                                        @can('user_restore')--}}
{{--                                                            <form action="{{ route('users.restore', $user->id) }}" method="POST">--}}
{{--                                                                @csrf--}}
{{--                                                                <button type="submit" class="btn btn-block btn-warning mt-1 btn-flat">Restore</button>--}}
{{--                                                            </form>--}}
{{--                                                        @endcan--}}
{{--                                                    @else--}}
{{--                                                        @can('user_delete')--}}
{{--                                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">--}}
{{--                                                                @csrf--}}
{{--                                                                @method('DELETE')--}}
{{--                                                                <button type="submit" class="btn btn-block btn-danger mt-1 btn-flat">Delete</button>--}}
{{--                                                            </form>--}}
{{--                                                        @endcan--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
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
