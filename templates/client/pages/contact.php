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
            <span class="wow fadeInUp">Call us, message us or visit our premier Edenvale showroom</span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms">Contact Us</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->

<!-- contact-us-section -->
<section class="contact-us-section layout-radius mb-4">
    <div class="boxcar-container">
        <!-- boxcar-title -->
        <div class="boxcar-title-three wow fadeInUp">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><span>Contact</span></li>
            </ul>
            <h2>Contact Us</h2>
        </div>
        <!-- End section title -->

        <!-- calculater-section -->
        <div class="calculater-sec wow fadeInUp mb-5 pt-0">
            <div class="right-box">
                <div class="row align-items-center">
                    <!-- content-column -->
                    <div class="col-lg-6 content-column">
                        <div class="inner-column">
                            <div class="boxcar-title">
                                <h2>We Value Your Feedback</h2>
                                <p>You can contact us about any feedback or recommendation without hesitation.</p>
                            </div>
                            <form class="row">
                                <div class="col-lg-6">
                                    <div class="form_boxes">
                                        <label>First Name</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name0="client_name" required placeholder="Jane">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form_boxes">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name0="client_surname" placeholder="Doe">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form_boxes">
                                        <label>Email</label>
                                        <input type="email" class="form-control h-auto py-1" data-fr-name0="client_email" required placeholder="youremail@domain.co.za">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form_boxes">
                                        <label>Phone<sup>(Optional)</sup></label>
                                        <input type="text" class="form-control h-auto py-1" data-fr-name0="client_phone" placeholder="e.g. 012 345 67 89">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form_boxes v2">
                                        <label>Message</label>
                                        <textarea rows="5" class="form-control h-auto py-3" data-fr-name0="client_message" placeholder="Your message" required style="width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="d-none">
                                    <input type="checkbox" hidden data-fr-name0="sales_enquiry">
                                </div>
                                <div class="form-submit">
                                    <button type="submit" class="theme-btn" data-fr-action="send-message">Send Message<i class="ms-2 fa fa-arrow-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- contact-column -->
                    <div class="contact-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="boxcar-title">
                                <h6 class="title">Contact Details</h6>
                            </div>
                            <div class="content-box">
                                <h6 class="title">
                                    <span class="icon">
                                        <i class="fa fa-location-dot"></i>
                                    </span>
                                    Address
                                </h6>
                                <div class="text">131 Van Riebeck Avenue<br />Edenvale, Johannesburg, 1610</div>
                            </div>
                            <div class="content-box">
                                <h6 class="title">
                                    <span class="icon">
                                        <i class="fa fa-envelopes"></i>
                                    </span>
                                    Email
                                </h6>
                                <div class="text">primo@primoexec.co.za</div>
                            </div>
                            <div class="content-box">
                                <h6 class="title">
                                    <span class="icon">
                                        <i class="fa fa-phone-volume"></i>
                                    </span>
                                    Phone
                                </h6>
                                <div class="text">+27 87 820 40 99</div>
                            </div>
                            <div class="social-icons">
                                <h6 class="title">Follow us</h6>
                                <ul class="social-links">
                                    <li><a class='fa fa-envelope' href='mailto:mario@primoexec.co.za'></a></li>
                                    <li><a class='fa-brands fa-facebook-f' href='https://facebook.com/PrimoExecutiveCars'></a></li>
                                    <li><a class='fa-brands fa-x-twitter' href='https://x.com/PrimoExecCars'></a></li>
                                    <li><a class="fa-brands fa-instagram" href="https://instagram.com/primoexecutivecars"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End calculater-section -->

        <!-- map-sec -->
        <div class="map-sec">
            <div class="goole-iframe">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3581.9572889576402!2d28.145924675184904!3d-26.132935561074852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e9512f80964bf1f%3A0xa80c399b725b3f29!2sPrimo%20Executive%20Cars!5e0!3m2!1sen!2sza!4v1719840697130!5m2!1sen!2sza"
                        width="auto" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <!-- End map-section -->

    </div>
</section>
<!-- contact-us-section -->