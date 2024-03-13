[{$smarty.block.parent}]

[{if $sPaymentID == 'oxidfatredirect' || $sPaymentID == 'oxidfatpay'}]
    <script>
        let [{$sPaymentID}]Label = document.getElementById('payment_[{$sPaymentID}]').nextElementSibling;
        let [{$sPaymentID}]LogoImg = createImage("[{$oViewConf->getModuleUrl('fatpay')}]/[{$sPaymentID}].png", "[{$sPaymentID}] Logo", 25);
        [{$sPaymentID}]Label.insertBefore([{$sPaymentID}]LogoImg, [{$sPaymentID}]Label.firstChild);

        function createImage(src, alt, size) {
            let img = document.createElement('img');
            img.src = src;
            img.alt = alt;
            img.height = size;
            img.setAttribute("style", "margin-right: 10px;");

            return img;
        }
    </script>
[{/if}]