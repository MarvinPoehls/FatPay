<div class="row h-100">
    <div class="hidden-xs col-1 col-md-2 col-lg-3 col-xxl-4"></div>
    <div class="col h-100 p-0">
        <div class="card mt-0 mt-md-5 h-100 h-md-50">
            <div class="card-body p-3">
                <div class="row my-2">
                    <div class="col">
                        <img src="images/FATRedirect.png" alt="FATRedirect Logo" class="float-start" height="40">
                        <p class="fw-bold fs-3 mb-0" style="color: #4f4f4f">FATRedirect</p>
                    </div>
                </div>
                <hr style="color: #4f4f4f">
                <form action="<?= $controller->getRedirect("login&action=validateLogin") ?>" method="post">
                    <div class="d-grid gap-4">
                        <div class="alert alert-info p-3 rounded-2 fw-bold m-0" role="alert"><?= $controller->getAlert(); ?></div>
                        <div>
                            <label for="birthday" class="text-secondary ms-3">Birthday</label>
                            <input type="date" class="form-control" name="birthday" id="birthday" value="<?= $controller->getBirthday() ?>">
                        </div>
                    </div>
                    <div class="position-bottom">
                        <hr style="color: #4f4f4f">
                        <button class="btn fw-bold" style="background-color: #FF6600; color: white">Login</button>
                        <a href="<?= $controller->getCancelRedirectToStore() ?>" class="btn fw-bold float-end" style="background-color: #FF6600; color: white">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="hidden-xs col-1 col-md-2 col-lg-3 col-xxl-4"></div>
</div>
<script src="js/login.js"></script>