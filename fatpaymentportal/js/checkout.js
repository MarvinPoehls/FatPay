function submitPayment(redirectUrl)
{
    if(!isValidUrl(redirectUrl)){
        let errormessage = "We couldn't redirect you back to the store :(. However, the payment process is complete and you can now leave the page now.";
        window.location.href = window.location.href.split('?')[0] + "/../index.php?controller=error&errormessage=" + errormessage;
    }

    $('#loader').toggle();
    $('#payButtonText').toggle();
    $('#payButton').removeAttr("onclick");

    let fadeOutImg = $('#fatshield');
    let fadeInImg = $('#finishedPayment');

    fadeOutImg.fadeOut(1000);
    fadeOutImg.promise().done(function () {
        fadeInImg.removeClass("d-none");
        fadeInImg.fadeOut(0).fadeIn(1000);
        fadeInImg.promise().done(function () {
            setTimeout(function(){
                window.location.href = redirectUrl + "&fcFinishedPayment=1";
            }, 200);
        });
    });
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (err) {
        return false;
    }
}
