/**
 * Created by aurelien on 13/06/14.
 */
$(document).ready(function(){
    $('#generatePassword').click(function() {
       var pw= $.generateRandomPassword(6);
        $('#UserPassword').val(pw);
        $('#UserPasswd').val(pw);
    });
});







(function($) {

    $.generateRandomPassword = function(limit) {

        limit = limit || 8;

        var password = '';

        var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        var list = chars.split('');
        var len = list.length, i = 0;

        do {

            i++;

            var index = Math.floor(Math.random() * len);

            password += list[index];

        } while(i < limit);
        return password;
    };


})(jQuery);