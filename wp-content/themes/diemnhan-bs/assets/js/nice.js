jQuery(document).ready(function () {
    // Remove thumbnail width and height
    jQuery('img').removeAttr('width').removeAttr('height');
});

function load_ajax() {
    jQuery.ajax({
        type: 'POST',
        dataType: 'html',
        url: DN_VAR.AJAX_URL,
        data: {
            'cat': 'news',
            'ppp': 3,
            'action': 'post_ajax'
        },
        beforeSend : function () {
            console.log('Before Send.')
        },
        success: function (data) {
            if (data) {
                console.log('Return: ' + data);
            } else {
                console.log('Return Data Null.');
            }
        },
        error : function (jqXHR, textStatus, errorThrown) {
            // $loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
            console.log(jqXHR);
        },
    });
    return false;
}