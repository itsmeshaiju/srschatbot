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
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="sub_questions">

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

@section('scripts')
    <script>
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
                    'level': id
                },
                cache: true,
                success: function(data) {
                    if (data.length == 0) {
                        $(document).Toasts('create', {
                            class: 'bg-warning',
                            title: 'Warning',
                            body: 'Subquestion is empty',
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

                    $('#sub_questions').empty();
                    // table appending
                    var i = 0;
                    $.each(data, function(index, qt) {
                        i++;
                        var url = '{{ route('subquestion.edit', ':id') }}';
                        var editUrl = url.replace(':id', qt.id);
                        url = '{{ route('subquestion.show', ':id') }}';
                        var showUrl = url.replace(':id', qt.id);
                        url = '{{ route('subquestion.destroy', ':id') }}';
                        var deleteUrl =  url.replace(':id', qt.id);
                        var deleteHtml = "";
                        if(qt.is_sub_qt == 0 ){
                             deleteHtml = `<hr><form action="`+deleteUrl+`" method="POST">
                                                    <input type="hidden" name="_method" value="DELETE">                                                    <input type="hidden" name="_token" value="7AN6LzXwxn5gFilDjc49zCf9CofB1LkNi2KVFYrQ">                                                    <button type="submit" class="btn btn-dangers">
                                                        <i class="fa fa-fw fa-trash"></i>Delete
                                                    </button>
                                                </form>`;
                        }


                        $('#sub_questions').append(`<tr>
        <td>` + i + `</td>
        <td>` + qt.question + `</td>
        <td>` + qt.answer + `</td>
        <td>
            <div class="btn-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        Action
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="` + showUrl + `">
                            <i class="fa fa-fw fa-eye mr-2"></i>View
                        </a>
                        <hr>
                        <a class="dropdown-item" href="` + editUrl + `">
                            <i class="fa fa-fw fa-edit mr-2"></i>Edit
                        </a>
                        `+deleteHtml+`
                    </div>
                </div>
            </div>
        </td>
    </tr>`);

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

<!-- /.content-wrapper -->
