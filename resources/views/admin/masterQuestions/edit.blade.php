@extends('admin.layouts.app')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
 
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <a class="btn-sm btn-success" href="{{ route('question.index') }}"> <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        <!-- <h1>Show Role</h1> -->
        <div class="pull-right">
        </div>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Edit {{$page_name}}</li>
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
      <h3 class="card-title">Edit {{$page_name}}</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    {!! Form::model($question, ['method' => 'PATCH','route' => ['question.update', $question->id]]) !!}
    <div class="card-body">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Question:</strong>
            {!! Form::text('question', null, array('placeholder' => 'Question','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
          <strong>Status:</strong>
          <select class="form-control select2 select2-hidden-accessible" name="status" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
          <option selected="selected" value="">-- Select --</option>
          <option {{$question->status == 1 ? 'selected' : '' }} value="1">Activate</option>
          <option {{$question->status == 0 ? 'selected' : '' }} value="0">Deactivate </option>
          </select>
      
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



