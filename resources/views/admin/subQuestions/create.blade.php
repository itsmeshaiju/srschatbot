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
                    <a class="btn-sm btn-success" href="{{ route('question.index') }}"> <i class="fa fa-arrow-left"
                            aria-hidden="true"></i></a>
                    <!-- <h1>Show Role</h1> -->
                    <div class="pull-right">
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Create New {{ $page_name }}</li>
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
                <h3 class="card-title">Create New {{ $page_name }}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            {!! Form::open(['route' => 'subquestion.store', 'method' => 'POST']) !!}
            <input type="hidden" id="main_question_id" name="main_question_id">
            <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12 row">
                    <input type="hidden" id="row_count" name="row_count" value="1">
                    <div class="col-md-12 ml-1 row">
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <strong>Level:</strong>
                                <select class="form-control select2 select2-hidden-accessible" onchange="getQuestion()"
                                    id="level" name="level" style="width: 100%;" data-select2-id="1" tabindex="-1"
                                    aria-hidden="true" required>
                                    
                                    @foreach ($levels as $lv)
                                        <option value="{{ $lv->id }}">{{ $lv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 ml-2">
                            <div class="form-group">
                                <strong>Questions:</strong>
                                <select class="form-control select2 select2-hidden-accessible" onchange="getSubQuestion(0)"
                                    id="main_question_id_0" name="main_question_id_01" style="width: 100%;"
                                    data-select2-id="1" tabindex="-1" aria-hidden="true" required>
                                    <option selected="selected" value="">-- Select --</option>
                                    @foreach ($questions as $qt)
                                        <option value="{{ $qt->id }}">{{ $qt->question }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="dynamic_slect_field" class="col-md-12 ml-1 row">

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

                        <div class="col-md-2">
                            <h6><b> Is Repeat</b></h6>

                        </div>

                    </div>
                    <div class="multi-fields">
                        <div class="multi-field1">

                            <div class="col-md-12 row multi-field">
                                <div class="col-md-3">
                                    <input  placeholder="Sub Question" required=""
                                        class="form-control mt-2" name="question[]" type="text" value="" required>
                                </div>
                                <div class="col-md-3">
                                    <textarea  placeholder="Answer" required class="form-control mt-2" name="answer[]" required></textarea>
                                </div>

                                <div class="col-md-2 mt-3">
                                    <div class="form-check">

                                        <input class="form-check-input form-check-input-lg" type="checkbox"
                                            name="is_repeat[]" value="0" id="flexCheckChecked">

                                    </div>
                                </div>
                                <button type="button" class="btn-danger remove-field btn-sm mt-2"><i
                                        class="nav-icon fa fa-trash"></i></button>
                            </div>


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
                $clonedElement.appendTo($wrapper).find('input').val('').end().find('input[type="checkbox"]')
                    .val(count - 1).prop('checked', false).end().find('input').first().focus();
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

        function getQuestion() {
           
            dynamicSelectFunction();
            var url = "{{ route('get.level.question') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'level': $('#level').val(),
                },
                cache: true,
                success: function(data) {
                    // Clear existing options from the select box
                    $('#main_question_id').empty();

                    // Append new options to the select box
                    $('#main_question_id').append('<option value="">--Select--</option>');
                    
                    $.each(data, function(index, qt) {
                        $('#main_question_id').append('<option value="' + qt.id + '">' + qt.question +
                            '</option>');
                    });
                }
            });
        }

        function getSubQuestion(id) {
            //===============get level count and html value append on slected===============
            count = $('#level').val();
            count = count - 1;
            last_html_id = '#main_question_id_' + count;
            last_option_val = $(last_html_id).val();
            $('#main_question_id').val(last_option_val);
            //==========================
            html_id = '#main_question_id_' + id;
            next_id_no = parseInt(id) + 1;
            appending_html_id = '#main_question_id_' + next_id_no;
            var qt_id = $(html_id).val();
            var url = "{{ route('get.master.sub.questions') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'qt_id': qt_id,
                    'level':id
                },
                cache: true,
                success: function(data) {
                    if (data.length == 0) {
                        $(document).Toasts('create', {
                            class: 'bg-warning',
                            title: 'Warning',
                            body: 'Subquestion is Empty',
                            autohide: true,
                            delay: 3000
                        })
                    }
                    // Clear existing options from the select box
                    $(appending_html_id).empty();
                    // Append new options to the select box
                    $(appending_html_id).append('<option value="">--Select--</option>');
                    $.each(data, function(index, qt) {
                        $(appending_html_id).append('<option value="' + qt.id + '">' + qt.question +
                            '</option>');
                    });
                }
            });
        }




        function dynamicSelectFunction() {
            count = $('#level').val();
            count = count - 1;
            $('#dynamic_slect_field').empty();
            for (var i = 0; i < count; i++) {
                var id_count = i + 1;
                var html_id = "main_question_id_" + id_count;

                $('#dynamic_slect_field').append(`<div class="col-md-4">
                            <div class="form-group">
                                <strong>Level ` + id_count + `  Questions:</strong>
                                <select class="form-control select2 select2-hidden-accessible" id="` + html_id + `"
                                    onchange="getSubQuestion(` + id_count + `)" name="main_question_id_` + id_count + `" style="width: 100%;" data-select2-id="1" tabindex="-1"
                                    aria-hidden="true" required>
                                    <option selected="selected" value="">-- Select --</option>

                                </select>
                            </div>
                        </div>`);
            }
        }
    </script>
@endsection
