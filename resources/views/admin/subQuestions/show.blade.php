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
                                <input type="checkbox" id="frontend" onclick="appendHtml()">
                                <label for="frontend">Front-end</label>
                                <div id="subchild-frontend1">

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

        function appendHtml() {
            count = count + 1;
            html_id = '#subchild-frontend' + count;
            next_html_id = count+1;
            $(html_id).empty();
            $(html_id).append(`

  <ul>
      <li>
                      <input type="checkbox" onclick="appendHtml()" id="css`+count+`">
                      <label for="css`+count+`">CSS</label>
                      <ul>
                          <li>
                              <label for="">SASS</label>
                          </li>
                          <li>
                              <label for="">LESS</label>
                          </li>
                          <li>
                            <input type="checkbox" onclick="appendHtml()" id="test11`+count+`">
                              <label for="test11">Stylus <br> test11</label>
                          </li>
                      </ul>
                    </li>
                    <li>
                      <input type="checkbox" onclick="appendHtml()" id="css1`+count+`">
                      <label for="css`+count+`">CSS1</label>
                      <ul>
                          <li>
                            <input type="checkbox" onclick="appendHtml()" id="css1`+count+`">
                              <label for="">SASS</label>
                          </li>
                          <li>
                            <input type="checkbox" onclick="appendHtml()" id="less`+count+`">
                              <label for="less">LESS</label>
                          </li>
                          <li>
                            <input type="checkbox" onclick="appendHtml()" id="test`+count+`">
                              <label for="test">Stylus <br> test</label>
                          </li>
                      </ul>
                    </li>
                    </ul>
                    <div id="subchild-frontend`+next_html_id+`">

                      </div>
                    `);
        }
    </script>
@endsection
