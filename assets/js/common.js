$(function () {

    $('.reviews_slider').slick({
        arrows: false,
        dots: true,
        fade: true,
        autoplay: true,
        autoplaySpeed: 2000
    });

    $('.send_message').on('click', function(){
        event.preventDefault();
        var data = {
            'name' : $('input[name="name"]').val(),
            'email' : $('input[name="email"]').val(),
            'message' : $('.message-text').val(),
            'avatar' : $('input[name="avatar"]').val(),
        }
        console.log(data.avatar.split('\\').pop())
        $.ajax({
            type: 'POST',
            url: 'ajax.php?act=addMessage',
            data: 'email=' + data.email + '&name=' + data.name + '&message=' + data.message + '&avatar=' + data.avatar.split('\\').pop(),
            success: function (response, status) {
                if (status == 'success') {
                    console.log(data.avatar.split('\\').pop())
                    $('.feedback-message').html(response.status);
                }
            }


        });


    });

    $('.glyphicon-floppy-saved').on('click', function(){
        $.ajax({
            type: 'POST',
            url: 'ajax.php?act=activateAllMessages',
            success: function (response, status) {
                if (status == 'success') {
                    $('.main_wrap').html('<h2 class="messages_activated">' + response.status + '</h2>');
                }
            }
        });
    })

    $('.glyphicon-trash').on('click', function(){
        $.ajax({
            type: 'POST',
            url: 'ajax.php?act=deleteNotActiveAllMessages',
            success: function (response, status) {
                if (status == 'success') {
                    $('.main_wrap').html('<h2 class="messages_activated">' + response.status + '</h2>');
                }
            }
        });
    })


    // $('.get_messages').on('click', function () {
    //     $.ajax({
    //         type: 'GET',
    //         url: 'ajax.php?act=getMessages',
    //         success: function(data) {
    //
    //             $('#messages').html(data[0].name);
    //         }
    //     });
    // });


});