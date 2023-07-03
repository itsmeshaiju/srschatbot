<!DOCTYPE html>
<html>
<head>
 <title>Software Requirement Chatbot</title>
 <style>
  tml {
  background: #CCD0D4;
  min-height: 100%;
}

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
 width: 400px;
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

 <div class="bot-message">Bot: Hello! How can I assist you with your software 
requirements?</div>
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
 <button id="sendButton" class="send-button">Send</button>
 </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
 <script>
 const sendButton = document.getElementById("sendButton");
 // Event listener for send button click
 sendButton.addEventListener("click", function() {
        $('#loader').show();
        $('#sendButton').prop('disabled', true);
        var url = "{{ route('chat.with.bot') }}";
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                'user_input': $('#userInput').val()
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                $('#sendButton').prop('disabled', false);
                contant  = data.content 
                $('#bot_msg').append('<br><br><br>Bot: ' + contant)
                $('#sendButton').prop('disabled', false);
            },
            error: function(error) {
                $('#loader').hide();
                console.log(data)
            }
        });
 });
 
 </script>
</body>
</html> 