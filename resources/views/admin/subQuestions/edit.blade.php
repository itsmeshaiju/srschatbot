@extends('admin.layouts.app')

@section('content')

    <style>
        input[type=checkbox] {
            margin: 4px 0 0;
            line-height: normal;
            width: 20px;
            height: 20px;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a class="btn-sm btn-success" href="{{ route('subquestion.index') }}"> <i class="fa fa-arrow-left"
                            aria-hidden="true"></i></a>
                    <!-- <h1>Show Role</h1> -->
                    <div class="pull-right">
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Edit {{ $page_name }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>



    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-header">
                <h3 class="card-title">Edit {{ $page_name }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            {!! Form::model($question, ['method' => 'PATCH','route' => ['subquestion.update', $question->id]]) !!}
            <div class="card-body">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group col-md-6">
                    <strong>Question:</strong>
                    {!! Form::text('question', null, array('placeholder' => 'Question','class' => 'form-control')) !!}
                </div>
                <div class="form-group col-md-6">
                    <strong>Answer:</strong>
                    {!! Form::text('answer', null, array('placeholder' => 'Answer','class' => 'form-control')) !!}
                </div>
            </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
    </div>
    <!-- /.card -->
    </div>

@endsection

