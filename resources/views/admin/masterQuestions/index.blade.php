@extends('admin.layouts.app')
@include('admin.layouts.dataTableStyle')
@section('content')
    <section class="content-header">

        <div class="container-fluid">

            @if ($message = Session::get('success'))
                <div class="alert alert-success text-center">
                    <!-- you missed this line of code -->
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>{{ $page_name }} Management</h2>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $page_name }} Management</li>
                    </ol>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- general form elements -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter {{ $page_name }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12 row">

                    <div class="form-group col-md-4">
                        <strong>Name </strong>
                        <input id="filter_name" placeholder="Name" required class="form-control" name="name"
                            type="text">
                    </div>
                    <div class="form-group col-md-4">
                        <strong>Status </strong>
                        <div class="form-group">
                            <select id="filter_status" class="form-control select2 select2-hidden-accessible" name="status"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">-- Select --</option>
                                <option value="1">Activate</option>
                                <option value="0">Deactivate </option>
                            </select>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ $page_name }} List</h3>
               
                    <div class="ml-auto">
                        <a class="btn btn-success btn-sm" href="{{ route('question.create') }}">Create New {{ $page_name }}</a>
                    </div>
               

            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped  category_tbl">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>xfvb</td>
                            <td>dfg</td>
                            <td>dfg</td>
                            <td>dfg</td>
                        </tr>
                        <tr>
                            <td>xfvb</td>
                            <td>dfg</td>
                            <td>dfg</td>
                            <td>dfg</td>
                        </tr>
                        <tr>
                            <td>xfvb</td>
                            <td>dfg</td>
                            <td>dfg</td>
                            <td>dfg</td>
                        </tr>
                        <tr>
                            <td>xfvb</td>
                            <td>dfg</td>
                            <td>dfg</td>
                            <td>dfg</td>
                        </tr>

                    </tbody>

                </table>


            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection('content')


<!-- /.content-wrapper -->
