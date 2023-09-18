@extends('admin.layouts.app')

@section('content')
    <style>
        * {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .container {
            padding: 50px;
            font-size: 16px;
            margin: 0 auto;
            overflow: auto;
        }

        .container ul {
            display: flex;
            opacity: 0;
            visibility: hidden;
            transform: scale(0);
            transition: 300ms visibility, 300ms transform, 300ms opacity;
        }

        .container ul li {
            flex: 1;
            text-align: center;
            padding: 0 10px;
            position: relative;
        }

        .container ul:first-child {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }

        .container ul li input {
            display: none;
        }

        .container ul li::before {
            content: '';
            position: absolute;
            top: -1em;
            left: 0;
            right: 0;
            height: 2px;
            background: #000;
        }

        .container ul li:first-child::before {
            left: 50%;
        }

        .container ul li:last-child::before {
            right: 50%;
        }

        .container ul li label {
            display: inline-block;
            padding: 5px 20px;
            border: 2px solid #000;
            border-radius: 5px;
            margin-bottom: 2em;
            position: relative;
            white-space: nowrap;
            box-sizing: border-box;
            user-select: none;
            cursor: pointer;
        }

        .container ul li label::after,
        .container ul li label::before {
            content: '';
            width: 2px;
            position: absolute;
            top: calc(-1em - 2px);
            height: 1em;
            left: 50%;
            transition: 300ms all;
            background: #000;
        }

        .container ul li label::after {
            top: auto;
            bottom: calc(-1em - 2px);
            height: 0;
        }

        .container ul li label:last-child::after {
            display: none;
        }

        .container>ul>li>label::before {
            display: none;
        }

        .container ul li input:checked+label {
            color: darkorange;
        }

        .container ul li input:checked+label::after {
            height: 1em;
        }

        .container ul li input:checked+label+ul {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
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
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View {{ $page_name }}</li>
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
                    <h3 class="card-title">About {{ $page_name }}</h3>
                </div>
                <div class="card-body">
                    <div class="container">
                        <ul>
                            <li>
                                <input type="checkbox" id="main_branch_{{ $master_question->id }}" onclick="appendHtml()">
                                <label for="main_branch_{{ $master_question->id }}">{{ $master_question->question }}</label>
                                <div id="subchild_main_branch_{{ $master_question->id }}">
                                    <ul>
                                        @foreach ($sub_question as $sub_qt)
                                            <li>
                                                <input type="checkbox" onclick="appendHtml({{ $sub_qt->id }})"
                                                    id="sub_child_id_{{ $sub_qt->id }}">
                                                <label
                                                    for="sub_child_id_{{ $sub_qt->id }}">{{ $sub_qt->question }}</label>
                                            </li>
                                         
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script>
        var count = 0;


        $('input[type="checkbox"]').on('click', function() {
          alert('ssss');
    // if ($(this).is(':checked')) {
    //   var value = $(this).val();
    //   console.log(value);
    // }
  });



        function appendHtml(main_question_id) {
            var html_id = "#sub_child_id_" + main_question_id;
            var url = "{{ route('master.questions.and.sub.questions') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'main_question_id': main_question_id,
                },
                cache: true,
                success: function(data) {
                    $(html_id).empty();

                    $(html_id).append(`
                        <ul>`);
                    Object.keys(data).forEach(function(key) {
                        $(html_id).append(`
                      
                        <input type="checkbox" onclick="appendHtml(` + data[key]['id'] + `)"
                            id="sub_child_id_` + data[key]['id'] + `">
                        <label
                            for="sub_child_id_` + data[key]['id'] + `">` + data[key]['question'] + `</label>
                        </li>
                        </ul>
                    <div id="subchild-next-div_` + data[key]['id'] + `">
                      </div>
                    `);

                       
                    });



                }
            });

        }
    </script>
@endsection
