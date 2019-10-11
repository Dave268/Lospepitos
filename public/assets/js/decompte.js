jQuery(function($){

    var launch = new Date(2019, 9, 18, 16, 05, 00);
    var days = $('#days');
    var hours = $('#hours');
    var minutes = $('#minutes');
    var seconds = $('#seconds');

    setDate();
    function setDate(){
        var now = new Date();
        var s = ((launch.getTime() - now.getTime())/1000) - now.getTimezoneOffset() * 60;
        var d = Math.floor(s/86400);
        days.html('<strong>' + d + '</strong> jour' + (d>1?'s':''));
        s-= d * 86400;
        var h = Math.floor(s/3600);
        hours.html('<strong>' + h + '</strong> heure' + (h>1?'s':''));
        s-= h * 3600;
        var m = Math.floor(s/60);
        minutes.html('<strong>' + m + '</strong> minute' + (m>1?'s':''));
        s-= m * 60;
        s = Math.floor(s);
        seconds.html('<strong>' + s + '</strong> seconde' + (s>1?'s':''));

        setTimeout(setDate, 1000);
        //console.log(now.toLocaleString());
    }

});