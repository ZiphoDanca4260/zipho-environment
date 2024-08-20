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
            <span class="wow fadeInUp">Fast, efficient & top prices paid for quality cars</span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms">We Want To Value Your Car</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->

<!-- contact-us-section -->
<section class="contact-us-section section-radius-top">
    <div class="boxcar-container">

        <!-- calculater-section -->
        <div class="calculater-sec">
            <div class="right-box">
                <div class="row align-items-center">
                    <!-- content-column -->
                    <div class="col-lg-12 content-column">
                        <div class="inner-column mb-4">
                            <div class="boxcar-title">
                                <h2>Value Evaluation Form</h2>
                                <p>Fill in the enquiry form below with all your vehicle details and a consultant will contact you back with an estimate.</p>
                            </div>

                            <div class="pb-4 pt-1">
                                <div class="custom-divider wow fadeInUp">
                                    <div class="mask"></div>
                                    <span>
                                        <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
                                    </span>
                                </div>
                            </div>

                            <form class="row">
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Name</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="client" data-fr-name0="client_name" required placeholder="e.g. Your Name">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Email</label>
                                        <input type="email" class="form-control h-auto py-1" data-fr-name1="client" data-fr-name0="client_email" required placeholder="e.g. youremail@domain.co.za">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Phone</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="client" data-fr-name0="client_phone" required placeholder="e.g. 012 345 67 89">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Make</label>
                                        <input type="text" list="autocomplete_makes" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="make" required placeholder="e.g. Audi / Mercedes-Benz / Ford etc.">

                                        <datalist id="autocomplete_makes">

                                            <!--- DYNAMIC CONTENT --->

                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Model</label>
                                        <input type="text" list="autocomplete_models" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="model" required placeholder="e.g. A3 / Focus / AMG GLS63 etc.">

                                        <datalist id="autocomplete_models">

                                            <!--- DYNAMIC CONTENT --->

                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Year</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="year" required placeholder="e.g. 2020">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Transmission</label>
                                        <select class="form-control h-auto py-1 py-1" data-fr-name1="car" data-fr-name0="transmission" required style="height: auto">
                                            <option class="d-none" selected disabled>Select a Transmission Type</option>
                                            <option value="manual">Manual</option>
                                            <option value="automatic">Automatic</option>
                                            <option value="semi_automatic">Semi-Automatic</option>
                                            <option value="continuously_variable_transmission_(CVT)">Continuously Variable Transmission (CVT)</option>
                                            <option value="dual_clutch">Dual Clutch</option>
                                            <option value="electric_vehicle_transmission_(EVT)">Electric Vehicle Transmission (EVT)</option>
                                            <option value="hybrid">Hybrid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Mileage</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="mileage" required placeholder="e.g. 20,000kms">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Color</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="color" required placeholder="e.g. Black / Red / Blue / White etc.">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp">
                                    <div class="form_boxes">
                                        <label>Settlement<sup>(Optional)</sup></label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name1="car" data-fr-name0="settlement">
                                    </div>
                                </div>
                                <div class="col-lg-12 wow fadeInUp">
                                    <div class="form_boxes mb-0">
                                        <label>Please Mention Any Scratches Or Damages</label>
                                        <textarea name="message" class="form-control h-auto py-1" placeholder="Your message" data-fr-name1="client" data-fr-name0="client_message" required style="width: 100%;"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-4 wow fadeInUp">
                                    <div class="form_boxes border border-1 rounded-3 p-3">
                                        <label class="w-100 my-1">Upload any vehicle related documents (pictures - settlement - scratches - license disc etc.)</label>
                                        <input type="file" class="mb-1" data-fr-action="file-input" multiple>

                                        <div class="d-flex flex-row flex-wrap gap-4 mt-5" data-fr-section="uploded-files">

                                            <!--- DYNAMIC CONTENT --->

                                        </div>
                                    </div>
                                </div>

                                <div class="form-submit">
                                    <button type="button" class="theme-btn" data-fr-action="evaluate-my-car">Evaluate My Car<i class="ms-2 fa fa-arrow-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End calculater-section -->
    </div>
</section>
<!-- contact-us-section -->