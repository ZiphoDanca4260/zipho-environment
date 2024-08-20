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
            <span class="wow fadeInUp">Happiest days of our lives</span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms">Client Reviews</h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->

<!-- boxcar-customers-section -->
<section class="boxcar-customers-section">
    <div class="boxcar-container">
        <div class="boxcar-title wow fadeInUp">
            <h2>What our customers say</h2>
            <div class="text">Rated 4 / 5 based on 236 reviews Showing our 4 & 5 star reviews</div>
        </div>
        <div class="row wow fadeInUp g-3" data-wow-delay="200ms">
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/ivan-is-an-exceptional-sales-person-and-hes-knowledge-about-every-vehicle-is-fantastic-and-very-helpful-5089261">Ivan is an exceptional sales person</a></h6>
                    <div class="text">
                        Ivan is an exceptional sales person and he's knowledge about every vehicle
                        is fantastic and very helpful. I will always come to ivan for any of my car needs.
                    </div>
                    <span>- Thabo, June 12, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/great-and-professsional-service-from-jabu-from-the-time-i-called-him-on-a-saturday-morning-to-check-availability-of-the-car-all-the-way-up-to-taking-delivery-5059679">Great and professsional service from Jabu</a></h6>
                    <div class="text">Great and professsional service from Jabu from the time I called him on a
                        Saturday morning to check availability of the car, all the way up to taking delivery.
                        He kept me in the loop all the time and his manager took the time to come chat and
                        congralute me when I collected the car. Great service overall, will definitely consider
                        buying a car again from Jabu.
                    </div>
                    <span>- Cas A, May 26, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/very-good-service-wi-a9fcc00acd8bcecfcaf2809f171dbdf29e58230a-5041435">Very good service</a></h6>
                    <div class="text">Very good service with reliable cars and excellent communication</div>
                    <span>- Loizo, May 15, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/if-you-need-a-family-car-primo-executive-is-your-go-to-dealership-5039393">If you need a family car Primo executive is your go to Dealership.</a></h6>
                    <div class="text">If you need a family car Primo executive is your go to Dealership.
                        There’s a guy by the name of Rivence he really assisted me with getting the best
                        vehicle up until this day it hasn’t given me any problems. S/O to Rivence for assisting
                        me and my family in getting the best cars.</div>
                    <span>- Violet, May 15, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/brad-from-primo-exec-5050612">Brad from Primo Executive Cars</a></h6>
                    <div class="text">Brad from Primo Executive Cars was so accommodating and efficient
                        and it was a pleasure doing business with him.</div>
                    <span>- Chantelle, May 21, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/i-was-looking-for-a-car-that-is-still-in-a-good-state-5039156">I was looking for a car that is still In a good state</a></h6>
                    <div class="text">I was looking for a car that is still In a good state.
                        A salesman by the Name of Rivence assisted me, he was really helpful,
                        loved his service and he made the whole process look easy.
                        I’m still happy with my car, no complaints😊</div>
                    <span>- Elizabeth, May 14, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/they-were-very-profe-2e6fe583bee00435e8df24d6e16621e2dcc2adfe-5038642">They were very professional</a></h6>
                    <div class="text">They were very professional and assisted me immediately</div>
                    <span>- Melandie E, May 14, 2024</span>
                </div>
            </div>
            <!-- customer-block  -->
            <div class="customer-block col-lg-4 col-md-6 col-sm-12">
                <div class="inner-box">
                    <div class="rating-area">
                        <ul class="rating">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span><i class="fa-solid fa-circle-check"></i>Verified</span>
                    </div>
                    <h6 class="title"><a target="_blank" href="https://www.hellopeter.com/primo-executive-cars/reviews/very-good-service-fr-1c6f71c141f601a53a1fce264f9a3d845e9a2205-5045136">Very good service from Mr Ivan</a></h6>
                    <div class="text">Very good service from Mr Ivan.
                        Very honest and hard working man. Making sure my car is always perfect and brand spanking new</div>
                    <span>- Loizo, May 17, 2024</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End boxcar-customers-section -->

<!-- contact-us-section -->
<section class="contact-us-section">
    <div class="boxcar-container">

        <!-- calculater-section -->
        <div class="calculater-sec py-0">
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
    </div>
</section>
<!-- contact-us-section -->