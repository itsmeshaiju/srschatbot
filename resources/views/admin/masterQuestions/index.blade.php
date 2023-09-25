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
        {{-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter {{ $page_name }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
                <form action="{{ route('question.index') }}" method="POST">
                    @csrf
                <div class="col-xs-12 col-sm-12 col-md-12 row">

                    <div class="form-group col-md-6">
                        <strong>Question </strong>
                        <input id="filter_question" placeholder="Question" required class="form-control" name="filter_question"
                            type="text" value="{{ $filterQuestion  }}">
                    </div>
                    <div class="form-group col-md-6">
                        <strong>Status </strong>
                        <div class="form-group">
                            <select id="filter_status" class="form-control select2 select2-hidden-accessible" name="filter_status"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">-- Select --</option>
                                <option {{ $filterStatus == 1 ? 'selected' : '' }} value="1">Activate</option>
                                <option {{ $filterStatus == 0 ? 'selected' : '' }} value="0">Deactivate </option>
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
        </div> --}}
        <!-- /.card -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ $page_name }} List</h3>

                <div class="ml-auto">
                    <a class="btn btn-success btn-sm" href="{{ route('question.create') }}">Create New
                        {{ $page_name }}</a>
                </div>


            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped  category_tbl">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Question</th>
                            <th>Status</th>
                            <th>First Question</th>
                            <th>Last Question</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($questions as $qt)
                            @if ($qt->status == 0)
                                @php $status = '<label class="badge badge-danger">Deactivated</label>' @endphp
                            @else
                                @php $status = '<label class="badge badge-success">Activated</label>' @endphp
                            @endif
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $qt->question }}</td>
                               
                                <td>
                                    {!! $status !!}
                                </td>
                                <td>
                                    @php
                                   
                                    $value = ($qt->is_first_question == 1) ? 0 : 1;
                                    $btn = ($qt->is_first_question == 1) ? 
                                    '<button type="submit" class="btn btn-danger">
                                            <i class="fa fa-ban"></i> </button>':
                                         '<button type="submit" class="btn btn-success"><i class="fa fa-check"></i>';
                                    @endphp
                                    <form action="{{ route('update.first.question') }}" method="POST">
                                        @csrf
                                        <input name="id" value="{{ $qt->id }}" type="hidden">
                                        <input name="is_first_question" value="{{ $value }}" type="hidden">
                                        {!! $btn !!}
                                    </form>
                                </td>
                                <td>
                                    @php
                                    $value = ($qt->is_last_question == 1) ? 0 : 1;
                                    $btn = ($qt->is_last_question == 1) ? 
                                    '<button type="submit" class="btn btn-danger">
                                            <i class="fa fa-ban"></i> </button>':
                                         '<button type="submit" class="btn btn-success"><i class="fa fa-check"></i>';
                                    @endphp
                                    <form action="{{ route('update.last.question') }}" method="POST">
                                        @csrf
                                        <input name="id" value="{{ $qt->id }}" type="hidden">
                                        <input name="is_last_question" value="{{ $value }}" type="hidden">
                                        {!! $btn !!}
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('question.show', $qt->id) }}">
                                                    <i class="fa fa-fw fa-eye mr-2"></i>View
                                                </a>
                                                <hr>

                                                <a class="dropdown-item" href="{{ route('question.edit', $qt->id) }}">
                                                    <i class="fa fa-fw fa-edit mr-2"></i>Edit
                                                </a>
                                                <hr>

                                                <form action="{{ route('question.destroy', $qt->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-dangers">
                                                        <i class="fa fa-fw fa-trash ml-3"></i>Delete
                                                    </button>
                                                </form>

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
