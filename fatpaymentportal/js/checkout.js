function submitPayment(storeUrl, data)
{
    $.ajax({
        url: "http://localhost/fatpayapi/index.php",
        type: "POST",
        data: data,
        success: function(data) {
            evaluateApiReturn(storeUrl, data);
        }
    });
}

function evaluateApiReturn(redirectUrl, data) {
    if(!isValidUrl(redirectUrl)){
        let errormessage = "We couldn't redirect you back to the store :(. However, the payment process is complete and you can now leave the page now.";
        window.location.href = "http://" + window.location.hostname + "/fatpaymentportal/index.php?controller=error&errormessage=" + errormessage;
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
                data.fcFinishedPayment = true;
                redirectUrl += httpEncode(data);
                window.location.href = redirectUrl;
            }, 200);
        });
    });
}

function httpEncode(array)
{
    let out = [];
    for (let key in array) {
        if (array.hasOwnProperty(key)) {
            out.push(key + '=' + encodeURIComponent(array[key]));
        }
    }
    return "&" + out.join('&');
}

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (err) {
        return false;
    }
}