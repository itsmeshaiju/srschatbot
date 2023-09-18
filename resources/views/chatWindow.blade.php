@extends('layout')
  
@section('content')

@if(session('success'))
    <div class="success-box1">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            var successBox = document.querySelector('.success-box1');
            if (successBox) {
                successBox.style.display = 'none';
            }
        }, 2000);
    </script>
@endif

<head>
 <title>Software Requirement Chatbot</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
 <style>
  html {
  background: #CCD0D4;
  min-height: 100%;
}
/* Style the active class, and buttons on mouse-over */


body {
  min-height: 100%;
}

h1.loader {
  text-align: center;
  text-transform: uppercase;
  font-family: 'Nunito', sans-serif;
  font-size: 4.6875em;
  color: transparent;
  letter-spacing: 0.01em;
}

.loader span {
  text-shadow:
    0 0 2px rgba(204, 208, 212,0.9),
    0 15px 25px rgba(0, 0, 0, 0.3),
    0 -2px 3px rgba(0, 0, 0, 0.1),
    0 -5px 10px rgba(255, 255, 255, 0.5),
    0 5px 10px rgba(0, 0, 0, 0.3),
    0 3px 4px rgba(255, 255, 255, 0.2),
    0 0 20px rgba(255, 255, 255, 0.45);
  
    animation: loading 0.85s ease-in-out infinite alternate;
}

@keyframes loading {
	to {text-shadow:
    0 0 2px rgba(204, 208, 212,0.2),
    0 0 3px rgba(0, 0, 0, 0.02),
    0 0 0 rgba(0, 0, 0, 0),
    0 0 0 rgba(255, 255, 255, 0),
    0 0 0 rgba(0, 0, 0, 0),
    0 0 0 rgba(255, 255, 255, 0),
    0 0 0 rgba(255, 255, 255, 0);}
}

.loader span:nth-child(2) {
  animation-delay:0.15s;
}

.loader span:nth-child(3) {
  animation-delay:0.30s;
}

.loader span:nth-child(4) {
  animation-delay:0.45s;
}

.loader span:nth-child(5) {
  animation-delay:0.60s;
}

.loader span:nth-child(6) {
  animation-delay:0.75s;
}

.loader span:nth-child(7) {
  animation-delay:0.90s;
}

 .chatbox {
 width: 1000px;
 height: 500px;
 border: 1px solid #ccc;
 overflow-y: scroll;
 padding: 10px;
 }
 .user-message {
 color: #333;
 margin-bottom: 10px;
 }
 .bot-message {
 color: #888;
 margin-bottom: 10px;
 }
 .input-container {
 display: flex;
 margin-top: 20px;
 }
 .input-field {
 flex-grow: 1;
 padding: 5px;
 }
 .send-button {
 padding: 5px 10px;
 background-color: #4CAF50;
 color: white;
 border: none;
 cursor: pointer;
 }
 </style>
</head>
<body>
 <h1>Software Requirement Chatbot</h1>
 <div class="chatbox" id="chatbox">

 <div class="bot-message  text-dark">Bot: Hello! How can I assist you with your software 
requirements?</div>
<br><br><div id="user-answer" class="bot-message  text-success" style="text-align: right">
<input type="hidden" id="q_id" value="">
<input type="hidden" id="qt_count" value="0">


</div>
<div class="bot-message" id="bot_msg">
  


</div>
<h1 id="loader" class="loader" style="display:none">
  <span>L</span>
  <span>O</span>
  <span>A</span>
  <span>D</span>
  <span>I</span>
  <span>N</span>
  <span>G</span>
</h1>
 </div>
 
 <div class="input-container">

 <input type="text" id="userInput" class="input-field" placeholder="Enter your message...">
 
 </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

 <script>
 const sendButton = document.getElementById("sendButton");
 // Event listener for send button click
 $("#userInput").on('keyup', function (e) {
  
  if ((e.key === 'Enter' || e.keyCode === 13) && $('#userInput').val() != "" ) {
    getChatReplay();
      }
 });
 

 function getButtonText(next_question_id,html_id){
  alert(next_question_id);
  $('#userInput').val(data);
  $(html_id).addClass('btn-secondary').removeClass('btn-success');
  getChatReplay();
 }

 function getChatReplay() {
  var qt_count =  $('#qt_count').val()
        $('#user-answer').append( $('#userInput').val())
        $('#user-answer').attr('id', '');
        $('#bot-question').attr('id', '');
        $('#button-row').attr('id', '');
        $('#loader').show();
        $('#sendButton').prop('disabled', true);
        var url = "{{ route('get.question') }}";
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                'user_input': $('#userInput').val(),
                'q_id' : $('#q_id').val(),
                'qt_count' : qt_count,
                

                
            },
            cache: true,
            success: function(data) {
              new_count = parseInt(qt_count) + 1
                $('#loader').hide();
                $('#sendButton').prop('disabled', false);
                contant  = data['question_name'];
                html_contant = '<br><br><br>'+data['options_html'];
                q_id = data['id']
                $('#bot_msg').append('<div id="bot-question"><br><br>Bot: <br><br></div><div id="button-row"></div><div id="user-answer" class="bot-message text-success" style="text-align: right"></div>')
                typeWriter(contant,html_contant)
                // $('#bot-question').text(contant);
                $('#sendButton').prop('disabled', false);
                $('#q_id').val(q_id)
                $('#qt_count').val(new_count)
                $('#userInput').val('')
                
    
               
            },
            error: function(error) {
              toastr.error('Something went wrong.Try again later..!', 'Failed!');
            }
        });
  }

  
 

  function typeWriter(content, html_content) {
  var text = content;
  var speed = 5; // Typing speed (in milliseconds)
  var delay = 2000; // Delay between typing and erasing (in milliseconds)
  var container = document.getElementById("bot-question");
  var i = 0;
  var interval = setInterval(function() {
    if (i < text.length) {
      container.textContent += text.charAt(i);
      i++;
    } else {
      clearInterval(interval); // Stop the typing animation
      setTimeout(function() {
        $('#button-row').append(html_content); // Append the HTML content after the delay
      }, delay);
    }
  }, speed);

  // Preserve line breaks
  container.style.whiteSpace = 'pre-line';
}
  
 </script>
@endsection
