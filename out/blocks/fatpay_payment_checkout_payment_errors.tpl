[{if $oView->getPaymentError() == 'fatpay_error' || $oView->getPaymentError() == 'fatpay_order_error'}]
    <div class="alert alert-danger">FatPay Error: [{$oView->getPaymentErrorText()}]</div>
[{/if}]
[{$smarty.block.parent}]