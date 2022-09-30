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
                    @if(count($activities) !== 0)
                        <div class="card-tools">
                            {{ $activities->links() }}
                        </div>
                    @endif

                @include('admin.layouts.includes.messages')

                </div>

                <div class="card-body p-0">
                    @if(count($activities) === 0)
                        <h5 class="card-header">You've no activities yet... somehow</h5>
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
                                            @isset($activity->causer->name)
                                                <td>{{ $activity->causer->name }}</td>
                                            @else
                                                <td>No data</td>
                                            @endisset
                                            <td>{{ $activity->properties }}</td>
                                            <td>{{ $activity->created_at }}</td>
                                            <td>{{ $activity->updated_at }}</td>
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
