<div class="modal fade" data-bs-backdrop="static" id="login-confirm" tabindex="-1" aria-labelledby="loginConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content has-form">
            <div class="modal-header flex-row d-flex">
                <h5 class="modal-title text-center login-confirm-title"><?= $lang['text']['welcomeToMyautocare'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <div class="container">
                    <div class="col-12">
                        <?php include('c:\WEB-SITELERI\AFRICA\myautocare.co.za\public_html\templates\default-client\assets\widget-show-notifications.php-show-notifications.php'); ?>
                    </div>
                </div> -->
                <div class="container">
                    <div class="col-12">
                        <div class="mb-3">
                            <p><?= $lang['text']['checkMailForCode'] ?> <br> <span class="fw-bold emailArea">mail@domain.co.za</span></p>
                            <input type="text" class="form-control" data-name0="messageCode" placeholder="<?= $lang['text']['messageCode'] ?>" required>
                            <small class="mx-auto"><?= $lang['text']['pleaseEnterCode'] ?></small>
                        </div>
                        <div class="mb-3">
                            <p class="py-0 my-0"><?= $lang['text']['didntWork'] ?> <a href="#" onclick="sendCodeAgain();"><?= $lang['text']['sendAnotherCode'] ?></a></p>
                            <p class="py-0 my-0"><a href="#">Need Help?</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><?= $lang['text']['Close'] ?></button>
                <button type="button" class="btn btn-success btn-sm loginConfirm position-relative" onclick="logIn($(this));"><?= $lang['text']['LoginConfim'] ?></button>
            </div>
        </div>
    </div>
</div>