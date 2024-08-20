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
            <span class="wow fadeInUp">Get help from our finance team or use our calculator for an approximation</span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms">Finances & Calculator</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->

<!-- SECTION -->
<section class="contact-us-section section-radius-top mb-0">
    <div class="boxcar-container">

        <!--- TABS --->
        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fs-5 py-3 px-2 px-md-5" id="contact-finance"
                        data-bs-toggle="tab" data-bs-target="#contact-finance-pane" type="button"
                        role="tab" aria-controls="contact-finance-pane" aria-selected="true">Contact Finance</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fs-5 py-3 px-2 px-md-5" id="finance-calculator"
                        data-bs-toggle="tab" data-bs-target="#finance-calculator-pane" type="button"
                        role="tab" aria-controls="finance-calculator-pane" aria-selected="false">Calculator</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">

            <!--- CONTACT FINANCE PANE --->
            <div class="tab-pane fade show active py-5 px-1 px-md-5" id="contact-finance-pane" role="tabpanel" aria-labelledby="contact-finance" tabindex="0">
                <div class="boxcar-title animated fadeInUp">
                    <h2>Contact our Finance Team</h2>
                    <p>Thank you for affording us the opportunity to arrange your finance.</p>
                </div>

                <div class="pb-4 pt-0">
                    <div class="custom-divider animated zoomIn">
                        <div class="mask"></div>
                        <span>
                            <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
                        </span>
                    </div>
                </div>

                <div class="row g-5 g-md-2 justify-content-center">

                    <div class="col-12 col-md-6 animated fadeInLeft">
                        <h4 class="text-center text-decoration-underline pb-2">Instructions</h4>
                        <p>
                            Please attach the&nbsp;<u>required documents</u>&nbsp;to the application form and forward the required
                            documents and the application form to Edenvale Finance Applications:
                        </p>

                        <ul>
                            <li>Perpetua Haakaloba</li>
                            <li>F&I Manager</li>
                            <li><a class="text-decoration-underline" href="mailto:finance@primoexec.co.za">finance@primoexec.co.za</a></li>
                            <li>Fax: 086 529 2771</li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-6 animated fadeInRight">
                        <h4 class="text-center text-decoration-underline pb-2">Required Documents</h4>

                        <div class="d-flex flex-column align-items-center">
                            <ul class="list-group">
                                <li class="list-group-item px-5">Copy of Identity document (ID)</li>
                                <li class="list-group-item px-5">Drivers License (if available)</li>
                                <li class="list-group-item px-5">Pay Slip or Salary Advice</li>
                                <li class="list-group-item px-5">3 Months Bank Statements</li>
                                <li class="list-group-item px-5">Proof of Address</li>
                                <li class="list-group-item px-5">
                                    <a type="button" class="btn btn-danger text-nowrap" href="/assets/client/documents/Finance-Application.pdf" target="_blank">
                                        Download Application Form&nbsp;&nbsp;
                                        <i class="fa-light fa-download"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row mt-5 align-items-center">
                    <hr class="flex-grow-1">
                    <span class="px-2 fst-italic text-secondary">Or Use Our Interface</span>
                    <hr class="flex-grow-1">
                </div>

                <div class="row">
                    <form class="row py-3 g-3 mt-0">

                        <div class="col-12 animated fadeInUp">
                            <div class="form_boxes">
                                <label>Requested Car*</label>
                                <input class="form-control" type="text" data-fr-name0="car_name">
                            </div>
                        </div>

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
                                <label>Your Phone<sup>(optional)</sup></label>
                                <input class="form-control" type="text" data-fr-name0="client_phone">
                            </div>
                        </div>

                        <div class="col-lg-12 mt-5 wow fadeInUp">
                            <div class="form_boxes border border-1 rounded-3 p-3">
                                <label class="w-100 my-1">Upload required documents and the application form</label>
                                <input type="file" class="mb-1" data-fr-action="file-input" multiple>

                                <div class="d-flex flex-row flex-wrap gap-4 mt-5" data-fr-section="uploded-files">

                                    <!--- DYNAMIC CONTENT --->

                                </div>
                            </div>
                        </div>

                        <div class="form-submit">
                            <button type="button" class="theme-btn" data-fr-action="contact-finance">Contact Finance Team<i class="ms-2 fa fa-arrow-right"></i></button>
                        </div>

                    </form>
                </div>
            </div>

            <!--- CALCULATE FINANCE PANE --->
            <div class="tab-pane fade py-5 px-1 px-md-5" id="finance-calculator-pane" role="tabpanel" aria-labelledby="finance-calculator" tabindex="0">

                <div class="boxcar-title mb-2 animated fadeInUp">
                    <h2>Finance Calculator</h2>
                    <p>Calculate all possibilities</p>
                    <p class="fst-italic">Dislcaimer: Results are <u>close approximations</u>. Factors such as banks with different interest rates might effect the actual monthly repayment amounts.</p>
                </div>

                <div class="pb-4 pt-1">
                    <div class="custom-divider animated zoomIn">
                        <div class="mask"></div>
                        <span>
                            <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
                        </span>
                    </div>
                </div>

                <form class="row py-4 g-3">
                    <div class="col-lg-4 col-md-6 animated fadeInUp">
                        <div class="form_boxes">
                            <label>Price Of The Car</label>
                            <input class="form-control" type="text" name="price" placeholder="e.g. R300,000.00" data-fr-input="price-input">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 animated fadeInUp">
                        <div class="form_boxes">
                            <label>Period In Months</label>
                            <select class="form-control" name="period" data-fr-select="period-select">
                                <option value="0" disabled selected class="d-none">Select A Repayment Period</option>
                                <option value="12">12 Months</option>
                                <option value="24">24 Months</option>
                                <option value="36">36 Months</option>
                                <option value="48">48 Months</option>
                                <option value="60">60 Months</option>
                                <option value="72">72 Months</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 animated fadeInUp">
                        <div class="form_boxes">
                            <label>Documentation, License & Registration Fee</label>
                            <input class="form-control" type="text" placeholder="R9.550" value="R9,550.00 (static amount)" disabled>
                        </div>
                    </div>

                    <hr class="pb-4 mt-5 animated zoomIn" />

                    <div class="finance-grid-container animated fadeInUp">
                        <div class="text-center finance-grid-1 fw-semibold">Loan Total (Price + Fees)</div>
                        <div class="text-center finance-grid-2 fw-semibold"> x </div>
                        <div class="text-center finance-grid-3 fw-semibold">Monthly Interest Rate</div>
                        <div class="text-center finance-grid-4 fw-semibold"> x </div>
                        <div class="text-center finance-grid-5 fw-semibold">(1 + Interest Rate)<sup>Period</sup></div>
                        <div class="text-center finance-grid-6 fw-semibold">/</div>
                        <div class="text-center finance-grid-7 fw-semibold">((1 + Interest Rate)<sup>Period</sup> - 1)</div>
                        <div class="text-center finance-grid-8 fw-semibold"> = </div>
                        <div class="text-center finance-grid-9 fw-semibold">Monthly Repayment</div>
                        <div class="text-center finance-grid-10" data-fr-output="loan-total"></div>
                        <div class="text-center finance-grid-11"> x </div>
                        <div class="text-center finance-grid-12" data-fr-output="interest-rate"></div>
                        <div class="text-center finance-grid-13"> x </div>
                        <div class="text-center finance-grid-14" data-fr-output="compounded-interest"></div>
                        <div class="text-center finance-grid-15"> / </div>
                        <div class="text-center finance-grid-16" data-fr-output="compounded-interest-subtraction"></div>
                        <div class="text-center finance-grid-17"> = </div>
                        <div class="text-center finance-grid-18 fs-5 fw-semibold" data-fr-output="total"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
<!-- SECTION -->