<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <script src="{{ asset('assets/chatWindow/dist/script.js') }}"></script>

    <script>
        const sendButton = document.getElementById("sendButton");
        var repeatedData = [];
        var send_mail_flag = 0;
        var repeat_flag = 0;
        // Event listener for send button click
        $("#userInput").on('keyup', function(e) {

            if ((e.key === 'Enter' || e.keyCode === 13) && $('#userInput').val() != "") {
                getChatReplay();
            }
        });


        function getButtonText(next_question_id, master_question, question, html_id) {
            repeat_flag = 0;
            $('#userInput').val(question);
            $(html_id).addClass('btn-outline-success').removeClass('btn-outline-primary');

            getChatReplay(next_question_id, master_question, question);
        }

        function getChatReplay(next_question_id = "", master_question = "", user_answer = "") {
            if (send_mail_flag == 1) {
                next_question_id = 'send_mail'
            }
            var qt_count = $('#qt_count').val()
            appendChatHtml();
            var url = "{{ route('get.question') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    'user_input': $('#userInput').val(),
                    'q_id': next_question_id,
                    'qt_count': qt_count,
                    'chat_question': master_question,
                    'user_answer': user_answer,
                    'is_repeat': $('#is_repeat').val(),
                },
                cache: true,
                success: function(data) {

                    addChatData(data['id'], data['question'], data['answer'], data['options_html']);
                    if (data['is_repeat'] == 1) {
                        repeat_flag = 1;
                        appendChatHtml();
                        if (data['is_last_question'] == 1) {
                            send_mail_flag = 1;
                            addChatData(data['id'], data['next_question']['question'], "", "");
                        }
                        addChatData(data['id'], data['next_question']['question'], data['next_question'][
                            'answer'
                        ], data['next_options_html']);


                    }

                    if (data['options_html'] != "") {
                        $("#userInput").prop("disabled", true);
                        $("#sendButton").prop("disabled", true);
                    } else {
                        $("#userInput").prop("disabled", false);
                        $("#sendButton").prop("disabled", false);
                    }

                    if(send_mail_flag == 1){
                        setTimeout(function() {
                            window.location.reload();
                    }, 4000);
                    }

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


        function appendChatHtml() {

            if (repeat_flag == 0) {
                $('#user-chat-div').show();
            }

            $('#user-answer').append($('#userInput').val())
            $('#user-answer').attr('id', '');
            $('#bot-question').attr('id', '');
            $('#user-chat-div').attr('id', '');
            $('#button-row').attr('id', '');
            $('#loader').show();
            $('#sendButton').prop('disabled', true);

            $('#bot-user-chat').append(`
  <div class="message-wrapper">
    <img class="message-pp" src="https://images.unsplash.com/photo-1587080266227-677cc2a4e76e?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=934&amp;q=80" alt="profile-pic">
    <div class="message-box-wrapper">
      <div id="bot-question" class="message-box">
        <div class="typing">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>
      </div>
    </div>
  </div>
  <div id="user-chat-div" class="message-wrapper reverse"  style="display: none;">
    <img class="message-pp" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2550&q=80" alt="profile-pic">
    <div class="message-box-wrapper">
      <div id="user-answer" class="message-box">
      </div>
    </div>
  </div>
`);
        }

        function repeatQuestion(data) {

            appendChatHtml();
        }

        function addChatData(id, question = "", answer = "", options_html = "") {
            new_count = parseInt(qt_count) + 1
            $('#loader').hide();
            $('#sendButton').prop('disabled', false);

            contant = '<h6 class="text-center" id="chat_question">' + question + '</h6>' + '<b>' + answer + '</b>' +
                options_html;
            var contentArray = contant.split("</p>");
            q_id = id;
            $("#bot-question").empty();
            if (contentArray.length > 1) {
                contentArray.forEach(function(option, index) {
                    setTimeout(function() {

                        $('#bot-question').append(option);
                    }, 1000 * index);
                });
                $("#bot-question").removeClass("blinker");
            } else {
                $('#bot-question').append(contant);

            }


            // $('#bot-question').append(contant);
            // typeWriter(contant, html_contant)
            // $('#bot-question').text(contant);
            $('#sendButton').prop('disabled', false);
            $('#q_id').val(q_id)
            $('#qt_count').val(new_count)
            $('#userInput').val('')


        }

        function RepeatAppendChatHtml() {

            $('#user-chat-div').show();
            $('#user-answer').append($('#userInput').val())
            $('#user-answer').attr('id', '');
            $('#chat-question').attr('id', '');
            $('#bot-question').attr('id', '');
            // $('#user-chat-div').attr('id', '');
            $('#button-row').attr('id', '');
            $('#loader').show();
            $('#sendButton').prop('disabled', true);

            $('#bot-user-chat').append(`
  <div class="message-wrapper">
    <img class="message-pp" src="https://images.unsplash.com/photo-1587080266227-677cc2a4e76e?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=934&amp;q=80" alt="profile-pic">
    <div class="message-box-wrapper">
      <div id="bot-question" class="message-box">
        <div class="typing">
  <div class="dot"></div>
  <div class="dot"></div>
  <div class="dot"></div>
</div>
      </div>
    </div>
  </div>
  <div id="user-chat-div" class="message-wrapper reverse"  style="display: none;">
    <img class="message-pp" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2550&q=80" alt="profile-pic">
    <div class="message-box-wrapper">
      <div id="user-answer" class="message-box">
      </div>
    </div>
  </div>
  
`);
        }
    </script>