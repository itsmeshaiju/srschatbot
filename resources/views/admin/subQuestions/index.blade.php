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
                <form action="{{ route('subquestion.index') }}" method="POST">
                    @csrf
                <div class="col-xs-12 col-sm-12 col-md-12 row">

                    <div class="form-group col-md-6">
                        <strong>Question </strong>
                        <input id="filter_question" placeholder="Question" required class="form-control" name="filter_question"
                            type="text" value="">
                    </div>
                    <div class="form-group col-md-6">
                        <strong>Status </strong>
                        <div class="form-group">
                            <select id="filter_status" class="form-control select2 select2-hidden-accessible" name="filter_status"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">-- Select --</option>
                                <option value="1">Activate</option>
                                <option  value="0">Deactivate </option>
                            </select>

                        </div>
                    </div>
                    
                    
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 row">
                    <div class="col-md-10">
                    </div>
                    <div class="col-md-1 pull-right">
                    <a class="btn btn-danger btn-sm pull-right" href="{{route('question.index')}}"> 
                        Reset
                    </a>
                    </div>
                    <div class="col-md-1 pull-right">
                <div class="mr-auto">
                    <button type="submit" class="btn btn-success btn-sm pull-right"> 
                        Search
                    </button>
                </div>
            </div>
                </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ $page_name }} List</h3>

                <div class="ml-auto">
                    <a class="btn btn-success btn-sm" href="{{ route('subquestion.create') }}">Create New
                        {{ $page_name }}</a>
                </div>


            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped  category_tbl">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Main Question</th>
                            <th>Sub Questions</th>
                            <th>Answers</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($mainQuestions as $qt)
                          
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $qt->question }}</td>
                               
                                <td>
                                    @foreach ($qt->subQuestion as $sub)
                                        <li>{{ $sub->question }}</li>       
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($qt->subQuestion as $sub)
                                        <li>{{ $sub->answer }}</li>       
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('subquestion.show', $qt->id) }}">
                                                    <i class="fa fa-fw fa-eye mr-2"></i>View
                                                </a>
                                                <hr>

                                                <a class="dropdown-item" href="{{ route('subquestion.edit', $qt->id) }}">
                                                    <i class="fa fa-fw fa-edit mr-2"></i>Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

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
