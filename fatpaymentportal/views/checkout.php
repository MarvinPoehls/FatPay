<?php /** @var CheckoutController $controller */ ?>
<div class="row h-100">
    <div class="hidden-xs col-1 col-md-2 col-lg-3 col-xxl-4"></div>
    <div class="col h-100 p-0">
        <div class="card mt-0 mt-md-5 h-100 h-md-50">
            <div class="card-body">
                <div class="row">
                    <div class="col pe-0">
                        <img src="images/FATRedirect.png" alt="FATRedirect Logo" class="float-start" height="55">
                        <p class="fw-bold fs-2 mb-0 d-none d-sm-inline" style="color: #4f4f4f">FATRedirect</p>
                    </div>
                    <div class="col-auto ps-1 text-end">
                        <i class="bi bi-bag fs-2"></i>
                        <p class="fs-3 d-inline"><?= $controller->getData('checkoutPrice') ?></p>
                    </div>
                </div>
                <hr style="color: #4f4f4f">
                <div class="text-center row align-items-center h-75 h-md-50">
                    <div class="col-12">
                        <img src="images/FatShieldWithCoins.png" alt="Pay safe with FATRedirect" id="fatshield" class="img-fluid img-md-not-fluid" height="200">
                        <img src="images/TickWithCircle.png" alt="finished payment" id="finishedPayment" class="d-none img-fluid img-md-not-fluid" height="200">
                        <p class="text fw-bold m-0 mt-2" style="color: #4f4f4f">Pay safe with FATRedirect.</p>
                    </div>
                </div>
                <div class="position-bottom">
                    <hr style="color: #4f4f4f">
                    <div class="row">
                        <div class="col">
                            <button onclick='submitPayment("<?= $controller->getRedirectToStore()?>")' class="btn fw-bold w-100" id="payButton" style="background-color: #FF6600; color: white">
                                <div id="payButtonText" style="display: block">Pay Now</div>
                                <div class="justify-content-end" style="width: calc(50% + 12px)"><div class="loader float-end" id="loader" style="display: none;"></div></div>
                            </button>
                        </div>
                        <div class="col-auto p-0" style="border-left:1px solid #d3d3d3;"></div>
                        <div class="col-auto">
                            <a href="<?= $controller->getCancelRedirectToStore(); ?>" class="btn fw-bold" style="background-color: #FF6600; color: white">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden-xs col-1 col-md-2 col-lg-3 col-xxl-4"></div>
    <script src="js/checkout.js"></script>
</div>