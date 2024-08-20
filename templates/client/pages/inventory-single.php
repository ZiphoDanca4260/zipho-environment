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
            <span class="wow fadeInUp"><?= $A1['selected_vehicle'][0]['vehicle_friendly_name'] . '&nbsp;' . $A1['selected_vehicle'][0]['vehicle_year'] . ' ~ ' . $A1['selected_vehicle'][0]['vehicle_mileage'] . 'kms' ?></span>
            <h2 class="wow fadeInUp" data-wow-delay="100ms"><?= $A1['selected_vehicle'][0]['vehicle_model'] . '&nbsp;' . $A1['selected_vehicle'][0]['vehicle_make'] ?></h2>
        </div>
    </div>
</section>
<!-- BANNER SECTION END -->

<!-- inventory-section -->
<section class="inventory-section pb-0 layout-radius mb-5 pb-5">
    <div class="boxcar-container">
        <div class="boxcar-title-three">
            <ul class="breadcrumb">
                <li><a href="/inventory">Inventory</a></li>
                <li><span><?= $A1['selected_vehicle'][0]['vehicle_friendly_name'] ?></span></li>
            </ul>
        </div>
    </div>
    <div class="gallery-sec-two wrap-gallery-box style-1">
        <div class="row inventy-slider wrap-slider-gallery">
            <?php
            //---rendering the image carousel of the selected vehicle
            if (empty($A1['selected_vehicle'][0]['image_saved_location'])) {
                $A1['selected_vehicle'][0]['image_saved_location'] = '/assets/default/image/image_not_found.jpg';
            }

            foreach ($A1['selected_vehicle'] as $vehicle) {
                ?>
                <div class="image-column col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-column">
                        <div class="image-box">
                            <figure class="image">
                                <a href="<?= $vehicle['image_saved_location'] ?>" data-fancybox="gallery" data-src="<?= $vehicle['image_saved_location'] ?>">
                                    <img class="rounded-3" src="<?= $vehicle['image_saved_location'] ?>" alt="Picture of <?= $vehicle['vehicle_friendly_name'] ?>">
                                </a>
                            </figure>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="boxcar-container">
        <div class="boxcar-title-three">
            <h2><?= $vehicle['vehicle_make'] . ' ' . $vehicle['vehicle_model'] ?></h2>
            <div class="text"><?= $vehicle['vehicle_friendly_name'] ?></div>
            <ul class="spectes-list">
                <li><span><i class="fa-thin fa-calendar-days"></i>&nbsp;<?= $vehicle['vehicle_year'] ?></span></li>
                <li><span><i class="fa-thin fa-gauge-low"></i>&nbsp;<?= number_format($vehicle['vehicle_mileage']) . 'kms' ?></span></li>
                <li><span><i class="fa-thin fa-code-branch"></i>&nbsp;<?= $vehicle['vehicle_transmission'] ? ucwords($vehicle['vehicle_transmission']) : 'N/A' ?></span></li>
                <li><span><i class="fa-thin fa-gas-pump"></i>&nbsp;<?= $vehicle['vehicle_fuel_type'] ? ucwords($vehicle['vehicle_fuel_type']) : 'N/A' ?></span></li>
            </ul>
            <div class="content-box">
                <div class="btn-box v2">
                    <div class="share-btn">
                        <span>Share</span>
                        <a href="#" class="share"><i class="fa-thin fa-share"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="inspection-column v2 col-lg-8 col-md-12 col-sm-12">
                <div class="inner-column">
                    <!-- overview-sec -->
                    <div class="overview-sec-two v2">
                        <h4 class="title">Car Overview</h4>
                        <ul class="list">
                            <li>
                                <img src="/assets/default/image/template-images/resource/insep2-1.svg">
                                <span>Mileage</span>
                                <small><?= number_format($vehicle['vehicle_mileage']) . 'kms' ?></small>
                            </li>
                            <li>
                                <img src="/assets/default/image/template-images/resource/insep2-2.svg">
                                <span>Fuel Type</span>
                                <small><?= $vehicle['vehicle_fuel_type'] ? ucwords($vehicle['vehicle_fuel_type']) : 'N/A' ?></small>
                            </li>
                            <li>
                                <img src="/assets/default/image/template-images/resource/insep2-3.svg">
                                <span>Transmission</span>
                                <small><?= $vehicle['vehicle_transmission'] ? ucwords($vehicle['vehicle_transmission']) : 'N/A' ?></small>
                            </li>
                            <li>
                                <img src="/assets/default/image/template-images/resource/insep2-4.svg">
                                <span>Year</span>
                                <small><?= $vehicle['vehicle_year'] ?></small>
                            </li>
                            <li>
                                <img src="/assets/default/image/template-images/resource/insep2-5.svg">
                                <span>Body Style</span>
                                <small><?= ucwords($vehicle['vehicle_body_shape']) ?></small>
                            </li>
                        </ul>
                    </div>

                    <div class="custom-divider wow fadeInUp">
                        <div class="mask"></div>
                        <span>
                            <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
                        </span>
                    </div>

                    <!-- description-sec -->
                    <div class="description-sec">
                        <h4 class="title">Description</h4>
                        <div class="text"><?= $vehicle['vehicle_description'] ?></div>
                    </div>

                    <!-- features-sec -->
                    <div class="features-sec">
                        <h4 class="title mb-1">Features</h4>
                        <div class="row">
                            <!-- list-column -->
                            <div class="list-column col-12 row">
                                <div class="inner-column">
                                    <ul class="feature-list d-flex flex-row flex-wrap gap-3">
                                        <?php
                                        if (!empty($A1['selected_vehicle'][0]['vehicle_extras'])) {
                                            $extras = explode(',', str_replace('.', '', $A1['selected_vehicle'][0]['vehicle_extras']));

                                            foreach ($extras as $i => $extra) {
                                                ?>

                                                <li><i class="fa-solid fa-check"></i><?= ucwords($extra) ?></li>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="location-box">
                        <h4 class="title">Location</h4>
                        <div class="text">131 Van Riebeeck Ave, Edenvale, Johannesburg, 1609
                            <br />
                            Open Mon-Friday 8-6pm // Saturdays 8-1pm
                        </div>
                        <a href="https://maps.app.goo.gl/D7b8F9y7U2Qb424S7" class="brand-btn">Get Directions<svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewbox="0 0 15 14" fill="none">
                                <g clip-path="url(#clip0_881_14440)">
                                    <path d="M14.1111 0H5.55558C5.34062 0 5.16668 0.173943 5.16668 0.388901C5.16668 0.603859 5.34062 0.777802 5.55558 0.777802H13.1723L0.613941 13.3362C0.46202 13.4881 0.46202 13.7342 0.613941 13.8861C0.689884 13.962 0.789415 14 0.88891 14C0.988405 14 1.0879 13.962 1.16388 13.8861L13.7222 1.3277V8.94447C13.7222 9.15943 13.8962 9.33337 14.1111 9.33337C14.3261 9.33337 14.5 9.15943 14.5 8.94447V0.388901C14.5 0.173943 14.3261 0 14.1111 0Z" fill="#405FF2"></path>
                                </g>
                                <defs>
                                    <clippath id="clip0_881_14440">
                                        <rect width="14" height="14" fill="white" transform="translate(0.5)"></rect>
                                    </clippath>
                                </defs>
                            </svg>
                        </a>
                        <div class="goole-iframe">
                            <iframe width="600" height="450" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJH79kCfgSlR4RKT9bcps5DKg&key=AIzaSyCmVHQatMvgbyxEqjvPhgv9fTIMKh8DCxs"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div class="side-bar-column v2 col-lg-4 col-md-12 col-sm-12">
                <div class="inner-column">
                    <div class="contact-box-two">
                        <h4 class="mb-1 text-decoration-underline">Our Price</h4>
                        <?php
                        if (!empty($A1['selected_vehicle'][0]['vehicle_old_price'])) {
                            ?>
                            <small class="text-decoration-line-through"><?= 'R' . number_format($A1['selected_vehicle'][0]['vehicle_old_price']) ?></small>
                            <?php
                        }
                        ?>

                        <h3 class="title"><?= 'R' . number_format($A1['selected_vehicle'][0]['vehicle_price']) ?></h3>
                        <div class="btn-box">
                            <a href="/finance/arrange-finance?vehicle_primo_id=<?= $A1['selected_vehicle'][0]['vehicle_primo_id'] ?>#contact-finance" target="_blank" class="side-btn"><i class="fa-light fa-tag fa-lg" style="transform: scaleX(-1)"></i>&nbsp;&nbsp;Contact Our Finance Team</a>
                        </div>
                        <div class="btn-box mt-0">
                            <a href="/finance/arrange-finance?vehicle_primo_id=<?= $A1['selected_vehicle'][0]['vehicle_primo_id'] ?>#finance-calculator" target="_blank" class="side-btn"><i class="fa-light fa-landmark-magnifying-glass fa-lg" style="transform: scaleX(-1)"></i>&nbsp;&nbsp;Calculate Loan Repayments</a>
                        </div>
                    </div>
                    <div class="contact-box">
                        <div class="content-box">
                            <h6 class="title">Primo Executive Cars</h6>
                            <div class="text">131 Van Riebeeck Ave, Edenvale, Johannesburg, 1609</div>
                            <ul class="contact-list">
                                <li>
                                    <a href="https://maps.app.goo.gl/D7b8F9y7U2Qb424S7">
                                        <div class="image-box"><img src="/assets/default/image/template-images/resource/phone1-1.svg"></div>Get Directions
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+27878204099">
                                        <div class="image-box"><img src="/assets/default/image/template-images/resource/phone1-2.svg"></div>087 820-4099
                                    </a>
                                </li>
                            </ul>
                            <div class="btn-box">
                                <a href="/contact?vehicle_primo_id=<?= $A1['selected_vehicle'][0]['vehicle_primo_id'] ?>" class="side-btn">Contact Us About This Car<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewbox="0 0 14 14" fill="none">
                                        <g clip-path="url(#clip0_881_7563)">
                                            <path d="M13.6111 0H5.05558C4.84062 0 4.66668 0.173943 4.66668 0.388901C4.66668 0.603859 4.84062 0.777802 5.05558 0.777802H12.6723L0.113941 13.3362C-0.0379805 13.4881 -0.0379805 13.7342 0.113941 13.8861C0.189884 13.962 0.289415 14 0.38891 14C0.488405 14 0.5879 13.962 0.663879 13.8861L13.2222 1.3277V8.94447C13.2222 9.15943 13.3962 9.33337 13.6111 9.33337C13.8261 9.33337 14 9.15943 14 8.94447V0.388901C14 0.173943 13.8261 0 13.6111 0Z" fill="white"></path>
                                        </g>
                                        <defs>
                                            <clippath id="clip0_881_7563">
                                                <rect width="14" height="14" fill="white"></rect>
                                            </clippath>
                                        </defs>
                                    </svg>
                                </a>
                                <a href="#" class="side-btn two">Chat Via Whatsapp<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewbox="0 0 14 14" fill="none">
                                        <g clip-path="url(#clip0_881_8744)">
                                            <path d="M13.6111 0H5.05558C4.84062 0 4.66668 0.173943 4.66668 0.388901C4.66668 0.603859 4.84062 0.777802 5.05558 0.777802H12.6723L0.113941 13.3362C-0.0379805 13.4881 -0.0379805 13.7342 0.113941 13.8861C0.189884 13.962 0.289415 14 0.38891 14C0.488405 14 0.5879 13.962 0.663879 13.8861L13.2222 1.3277V8.94447C13.2222 9.15943 13.3962 9.33337 13.6111 9.33337C13.8261 9.33337 14 9.15943 14 8.94447V0.388901C14 0.173943 13.8261 0 13.6111 0Z" fill="#60C961"></path>
                                        </g>
                                        <defs>
                                            <clippath id="clip0_881_8744">
                                                <rect width="14" height="14" fill="white"></rect>
                                            </clippath>
                                        </defs>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End inventory-section -->

<div class="custom-divider wow fadeInUp mb-5 mt-2">
    <div class="mask"></div>
    <span>
        <i><img class="img-fluid" src="/assets/default/image/primoexecutive.ico" alt="Primo Icon"></i>
    </span>
</div>

<!-- cars-section-three -->
<section class="cars-section-three">
    <div class="boxcar-container">
        <div class="boxcar-title wow fadeInUp">
            <h2>Explore Related Listings</h2>
            <a href="/inventory" class="btn-title">View All<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewbox="0 0 14 14" fill="none">
                    <g clip-path="url(#clip0_601_243)">
                        <path d="M13.6109 0H5.05533C4.84037 0 4.66643 0.173943 4.66643 0.388901C4.66643 0.603859 4.84037 0.777802 5.05533 0.777802H12.6721L0.113697 13.3362C-0.0382246 13.4881 -0.0382246 13.7342 0.113697 13.8861C0.18964 13.962 0.289171 14 0.388666 14C0.488161 14 0.587656 13.962 0.663635 13.8861L13.222 1.3277V8.94447C13.222 9.15943 13.3959 9.33337 13.6109 9.33337C13.8259 9.33337 13.9998 9.15943 13.9998 8.94447V0.388901C13.9998 0.173943 13.8258 0 13.6109 0Z" fill="#050B20"></path>
                    </g>
                    <defs>
                        <clippath id="clip0_601_243">
                            <rect width="14" height="14" fill="white"></rect>
                        </clippath>
                    </defs>
                </svg>
            </a>
        </div>

        <nav class="wow fadeInUp" data-wow-delay="100ms">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#more-from-brand" type="button" role="tab" aria-controls="more-from-brand" aria-selected="true">More From <?= $A1['selected_vehicle'][0]['vehicle_make'] ?></button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#similar-priced-listings" type="button" role="tab" aria-controls="similar-priced-listings" aria-selected="false">Similar Priced Listings</button>
                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#more-from-body-shape" type="button" role="tab" aria-controls="more-from-body-shape" aria-selected="false">More <?= ucwords(str_replace('_', ' ', $A1['selected_vehicle'][0]['vehicle_body_shape'])) ?>'s</button>
            </div>
        </nav>
    </div>

    <div class="tab-content wow fadeInUp" data-wow-delay="200ms" id="nav-tabContent">
        <div class="tab-pane fade show active" id="more-from-brand" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row car-slider-three slider-layout-1" data-preview="4.8">

                <!---DYNAMIC CONTENT START COPY--->

                <div class="box-car car-block-three col-lg-3 col-md-6 col-sm-12 d-flex flex-column">
                    <div class="inner-box d-flex flex-column flex-grow-1">
                        <div class="image-box">
                            <div class="slider-thumb">
                                <div class="image"><a href="#"><img src="/assets/default/image/template-images/resource/shop3-1.jpg" alt=""></a></div>
                                <div class="image"><a href="#"><img src="/assets/default/image/template-images/resource/shop3-2.jpg" alt=""></a></div>
                                <div class="image"><a href="#"><img src="/assets/default/image/template-images/resource/shop3-3.jpg" alt=""></a></div>
                            </div>
                            <span class="info-badge">Low Mileage</span>
                        </div>
                        <div class="content-box d-flex flex-column flex-grow-1 justify-content-around">
                            <h6 class="title"><a href="#">Mercedes-Benz, C Class</a></h6>
                            <div class="text text-wrap">2023 C300e AMG Line Night Ed Premiu...</div>
                            <ul class="justify-content-between">
                                <li class="align-items-center text-center"><i class="flaticon-gasoline-pump"></i>72,925 miles</li>
                                <li class="align-items-center text-center"><i class="flaticon-speedometer"></i>Petrol</li>
                                <li class="align-items-center text-center"><i class="flaticon-gearbox"></i>Automatic</li>
                            </ul>
                            <div class="btn-box">
                                <span>R789.000</span>
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <small>R399.000</small>
                                    <a href="#" class="details ms-3 text-nowrap">View Details&nbsp;&nbsp;<i class="fa-thin fa-arrow-up-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!---DYNAMIC CONTENT END COPY--->

            </div>
        </div>
        <div class="tab-pane fade" id="similar-priced-listings" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="row car-slider-three slider-layout-1" data-preview="4.8">

                <!--- DYNAMIC CONTENT --->

            </div>
        </div>
        <div class="tab-pane fade" id="more-from-body-shape" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="row car-slider-three slider-layout-1" data-preview="4.8">

                <!--- DYNAMIC CONTENT --->

            </div>
        </div>
    </div>
</section>
<!-- End shop section two -->