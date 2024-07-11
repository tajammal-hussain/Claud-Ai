jQuery(document).ready(function($) {
    $('#claud-ai-submit').on('click', function() {
        var input = $('#claud-ai-input').val();
        var data = {
            'action': 'claud_ai_action',
            'input': input
        };
        $.post(claud_ai_ajax.ajax_url, data, function(response) {
            $('#claud-ai-response').html(response);
        });
    });
});
