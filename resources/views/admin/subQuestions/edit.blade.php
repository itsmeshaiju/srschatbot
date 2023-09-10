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
            {!! Form::model($mainQuestion, ['method' => 'PATCH', 'route' => ['subquestion.update', $mainQuestion->id]]) !!}
            <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12 row">
                    <input type="hidden" id="row_count" name="row_count"
                        value="{{ count($mainQuestion->subQuestion) + 1 }}">
                    <div class="col-md-8 ml-2">
                        <div class="form-group">
                            <strong>Questions:</strong>
                            <select class="form-control select2 select2-hidden-accessible" name="main_question_id"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="">-- Select --</option>
                                @foreach ($questions as $qt)
                                    <option {{ $mainQuestion->id == $qt->id ? 'selected' : '' }}
                                        value="{{ $qt->id }}">{{ $qt->question }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="multi-field-wrapper col-md-12 mt-4">
                    <div class="col-md-12 row multi-field">
                        <div class="col-md-3">
                            <h6><b>Sub Question</b></h6>
                        </div>
                        <div class="col-md-3">

                            <h6><b> Answer</b></h6>
                        </div>
                        <div class="col-md-3">
                            <h6><b>Next Question</b></h6>
                        </div>
                        <div class="col-md-2">
                            <h6><b> Is Repeat</b></h6>

                        </div>

                    </div>
                    <div class="multi-fields">
                        <div class="multi-field1">
                            @php
                                $i=0;
                            @endphp
                            @foreach ($mainQuestion->subQuestion as $sqt)
                                <div class="col-md-12 row multi-field">
                                    <div class="col-md-3">
                                        <input id="filter_question" placeholder="Sub Question" required=""
                                            class="form-control mt-2" name="question[]" type="text"
                                            value="{{ $sqt->question }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input id="filter_question" placeholder="Answer" required=""
                                            class="form-control mt-2" name="answer[]" type="text"
                                            value="{{ $sqt->answer }}" required>
                                    </div>
                                    <div class="col-md-3">

                                        <select class="form-control select2 select2-hidden-accessible mt-2"
                                            name="next_question_id[]" style="width: 100%;" data-select2-id="1"
                                            tabindex="-1" aria-hidden="true" required>
                                            <option selected="selected" value="">-- Select --</option>
                                            @foreach ($questions as $qt)
                                                <option {{ $sqt->next_question_id == $qt->id ? 'selected' : '' }}
                                                    value="{{ $qt->id }}">{{ $qt->question }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <div class="form-check">

                                            <input class="form-check-input form-check-input-lg" type="checkbox"
                                                name="is_repeat[]" value="{{$i}}" {{ $sqt->is_repeat == 1 ? 'checked' : ''}} id="flexCheckChecked">

                                        </div>
                                    </div>
                                    <button type="button" class="btn-danger remove-field btn-sm mt-2"><i
                                            class="nav-icon fa fa-trash"></i></button>
                                </div>
                                @php
                                $i++;
                            @endphp
                            @endforeach


                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-4 add-field ml-2">Add</button>
                    <button type="submit" class="btn btn-success mt-4">Submit</button>
                </div>

            </div>

        </div>
        <!-- /.card-body -->
        {!! Form::close() !!}
    </div>
    <!-- /.card -->
    </div>

@endsection
@section('scripts')
    <script>
     $('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
        var count = $('#row_count').val();
        count++;
        var $clonedElement = $('.multi-field:first-child', $wrapper).clone(true);
        $clonedElement.appendTo($wrapper).find('input').val('').end().find('input[type="checkbox"]').val(count-1).prop('checked', false).end().find('input').first().focus();
        $('#row_count').val(count);
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1) {
            var count = $('#row_count').val();
            count--;
            $(this).parent('.multi-field').remove();
            $('#row_count').val(count);
        }
    });
});
 
$('input[name="is_repeat[]"]').change(function() {
  // Uncheck all checkboxes with the same name
  $('input[name="is_repeat[]"]').prop('checked', false);
  
  // Check the clicked checkbox
  $(this).prop('checked', true);
});
    </script>
@endsection
