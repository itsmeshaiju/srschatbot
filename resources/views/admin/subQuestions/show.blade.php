@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
 
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <a class="btn-sm btn-success" href="{{ route('subquestion.index') }}"> <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        <!-- <h1>Show Role</h1> -->
        <div class="pull-right">
        </div>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">View {{$page_name}}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>


<!-- Content Header (Page header) -->
<section class="content-header">
  
  <div class="container-fluid">
  <div class="card card-primary">
    @if ($message = Session::get('success'))
    <div class="alert alert-success text-center">
        <!-- you missed this line of code -->
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{ $message }}
    </div>
@endif
<div class="card-header">
<h3 class="card-title">About {{$page_name}}</h3>
</div>

<div class="card-body text-center">
<strong><i class="fa fa-question-circle mr-1"></i> Question</strong>
<p class="text-muted">
{{ $subQuestion->question }}
</p>

</div>
<div class="card-body">
<table id="example1" class="table table-bordered table-striped  category_tbl">
  <thead>
      <tr>
          <th>No</th>
          <th>Sub Question</th>
          <th>Answer</th>
          <th>Action</th>
      </tr>
  </thead>
  <tbody>
      @php
          $i = 1;
      @endphp
      @foreach ($subQuestion->subQuestion as $qt)
        
          <tr>
              <td>{{ $i++ }}</td>
              <td>{{ $qt->question }}</td>
               <td>{{ $qt->answer }}</td>
               <td> <form action="{{ route('subquestion.destroy', $qt->id) }}" method="POST">
                @method('DELETE')
                @csrf
                <button   onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm">
                    <i class="fa fa-fw fa-trash  "></i>
                </button>
            </form></td>
          </tr>
      @endforeach

  </tbody>
</table>
  </div>
  </div><!-- /.container-fluid -->
</section>


@endsection


