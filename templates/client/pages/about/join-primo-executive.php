<?php
//---init google recaptcha
if ($settings['google']['reCaptchaV3']['active']) {
    $siteKey = $settings['google']['reCaptchaV3']['siteKey'];
    $siteKeyV2 = $settings['google']['reCaptchaV2']['siteKey'];
    echo "<script src='https://www.google.com/recaptcha/api.js?render={$siteKey}' defer></script>";
    if (!isset($A1['public_settings'])) {
        $A1['public_settings'] = array();
    }
    $A1['public_settings']['google']['recaptchaV3'] = $siteKey;
    $A1['public_settings']['google']['recaptchaV2'] = $siteKeyV2;
}
?>

<!-- BANNER SECTION -->
<section class="boxcar-banner-section-v1 alt">
    <div class="container">
        <div class="banner-content">
            <span class="wow fadeInUp">We want you, see open positions and easily apply</span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms">Work With Primo Executive</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->


<!-- about-inner-one -->
<section class="about-inner-one layout-radius">
    <!-- faq-section -->
    <div class="boxcar-container pt-0 pb-5">
        <div class="inner-container">

            <div class="boxcar-title text-center wow fadeInUp">
                <h2 class="title">Open Positions</h2>
            </div>

            <div class="custom-divider wow zoomIn mb-5">
                <div class="mask"></div>
                <span>
                    <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
                </span>
            </div>

            <div class="px-1 px-md-5 py-4">
                <p class="px-0 px-md-5">
                    We are looking for innovative, hard working and talented individuals to help
                    drive the excellence at Primo Executive Cars.
                    <br />
                    A passion for Cars is required.
                    Below, you can find a list of openings we currently have. If you’re interested,
                    please follow the instruction described in each opening.
                </p>
            </div>

            <div class="px-1 px-md-5 py-4">

                <h2 class="text-center">Dynamic Car Sales Executive Position</h2>

                <p class="px-0 px-md-5">
                    We are looking for a dynamic, well-spoken, vibrant individual that is quick thinking and outgoing and has a passion for cars.
                    Must have motor industry sales experience.
                    <br />
                    <br />
                    You get to be part of an exciting dealership in Edenvale with a basic salary and commission.
                    Working hours are from&nbsp;<strong><em><u>Monday to Friday from 8am to 6pm and Every Saturday from 9am to 2pm.</u></em></strong>
                    <br />
                    <br />
                    If you think you have what it takes and are that bubbly,
                    special person we are looking for then please apply with your CV and a short introduction video of
                    yourself to:&nbsp;<strong><u>0627137987</u></strong>&nbsp;(No voice calls will be answered. Applications that are sent without an introduction video of yourself will not be considered.)
                    <br />
                    <br />
                    Please state what position you are applying for when you send your Whatsapp message.
                </p>
            </div>

            <form class="row px-1 px-md-5 py-4 g-3">
                <div class="col-lg-4 col-md-6 animated fadeInUp">
                    <div class="form_boxes">
                        <label>Your Name*</label>
                        <input class="form-control" type="text" data-fr-name0="client_name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 animated fadeInUp">
                    <div class="form_boxes">
                        <label>Your Email*</label>
                        <input class="form-control" type="text" data-fr-name0="client_email">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 animated fadeInUp">
                    <div class="form_boxes">
                        <label>Your Phone*</label>
                        <input class="form-control" type="text" data-fr-name0="client_phone">
                    </div>
                </div>

                <div class="col-12 wow fadeInUp">
                    <div>
                        <div class="form_boxes border border-1 rounded-3 p-3">
                            <label>Select your CV and your introduction video</label>
                            <input type="file" class="mb-1" data-fr-action="file-input" multiple>

                            <div class="d-flex flex-row flex-wrap gap-4 mt-5" data-fr-section="uploded-files">

                                <!--- DYNAMIC CONTENT --->

                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-submit pt-3 wow fadeInRight">
                            <div class="form_boxes">
                                <button type="button" class="theme-btn my-3 px-5 ms-auto" data-fr-action="send-job-application">Send Your Application<i class="ms-2 fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End faqs-section -->
</section>