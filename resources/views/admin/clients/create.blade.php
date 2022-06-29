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
                    <h3 class="card-title">Create client</h3>
                </div>
            <form class="form-horizontal">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Company name</label>
                        <input type="text" class="form-control" id="title" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="VAT">VAT</label>
                        <input type="text" class="form-control" id="VAT" placeholder="Enter VAT number">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Enter address">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Create</button>
                    <a href="{{ url()->previous() }}" type="submit" class="btn btn-default float-right">Cancel</a>
                </div>
            </form>
            </div>
        </div>
    </section>
</div>
@endsection
