// https://www.w3schools.com/howto/howto_js_countdown.asp
// Display the countdown timer in an element
// usage : <p id="demo"></p>

/***
 * @param duration int in minutes
 */
function timeoutCounter(duration) {

// Set the expiring time
    var countDownDate = new Date().getTime();
    countDownDate += (duration + 1) * 1000;

// set a counter every 1 second
    var x = setInterval(function () {

        // Get time now
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for minutes and seconds
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        var strTime = 'Session timeout ' + minutes + ':' + seconds;

        if (distance > 0) {
            // Output the result in an element with id="demo"
            document.getElementById("timeoutCounter").innerHTML = strTime;
        } else {
            //unset counter
            clearInterval(x);

            // If the count down is over, write some text
            //document.getElementById("timeoutCounter").innerHTML = "EXPIRED";
           location.reload();

        }
    }, 1000);
}

