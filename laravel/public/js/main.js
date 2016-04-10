$(document).ready(function () {
    likeAction();
    deleteAction();
    intervalUpdates();
    VideoControls();
    LinkControls();
    PhotoControls();
    function likeAction() {
        $('body').on('click', '.message__like', function () {
            var url = $(this).attr('like-url');
            var like = $(this);
            $.ajax({
                url: url,
                success: function (data) {
                    like.html('Like (' + data + ')');

                }
            });
        });
    }
    function deleteAction() {
        $('body').on('click', '.message__delete', function () {
            var url = $(this).attr('delete-url');
            var deleteMsg = $(this);
            $.ajax({
                url: url,
                success: function (data) {
                    deleteMsg.closest('.flex_col').html('Message has been deleted!');
                }
            });
        });
    }
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });
        return vars;
    }
    function updateMessages() {
        $.ajax({
            url: '/home/messages',
            success: function (data) {
                if (data != 0) {
                    $('#msgs').html(data);
                }
            }
        });
    }
    function intervalUpdates() {
        var page = getUrlVars()["page"];
        if (/*!page || page == 1*/true) {
            setInterval(updateMessages, 5000);
        }
    }
    function VideoControls() {
        $('iframe').click(function(){
           event.preventDefault();
            console.log("asdf");
        });
        current_video = 1;
        $('.fields__video').click(function () {
            var new_video = '<div class="flex_row"><input name="video[' + current_video + ']" type="text" pattern="https?://www\\.youtube\\.com/watch\\?v=.+" title="Link from youtube.com" placeholder="Video">' +
                '<div class="fields__del fields__del-video"></div></div>';
            $('.fields__video-block').append(new_video);
            current_video++;
        });
        $('body').on('click', '.fields__del-video', function () {
            $(this).closest('.flex_row').remove();
            current_video--;
        });
    }
    function LinkControls() {
        current_link = 1;
        $('.fields__link').click(function () {
            var new_link = '<div class="flex_row"><input type="text" placeholder="Link" pattern="https?://(?!www.youtube.com).+" title="http(s)://..." name="link[' + current_link + ']">' +
                '<div class="fields__del fields__del-link"></div></div>';
            $('.fields__link-block').append(new_link);
            current_link++;
        });
        $('body').on('click', '.fields__del-link', function () {
            $(this).closest('.flex_row').remove();
            current_link--;
        });
    }
    function PhotoControls() {
        current_photo = 1;
        $('.fields__photo').click(function () {
            var new_photo = '<div class="flex_row"><input name="photo[' + current_photo + ']"  type="file">' +
                '<div class="fields__del fields__del-photo"></div></div>';
            $('.fields__photo-block').append(new_photo);
            current_photo++;
        });
        $('body').on('click', '.fields__del-photo', function () {
            $(this).closest('.flex_row').remove();
            current_photo--;
        });
    }
});