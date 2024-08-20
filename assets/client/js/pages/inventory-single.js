// --- RENDER --------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const renderExploreAllVehicleListings = () => {
    let $section = $('.cars-section-three');
    let $tabContents = $section.find('.tab-content');

    let $listingTemplate = $section.find('.car-block-three').clone();
    $section.find('.car-block-three').remove();

    //---make the more from brand tab
    let $carSlider = $tabContents.find('#more-from-brand').find('.row.car-slider-three');
    let more_from_brand_cars = filterVehicles({ vehicle_make: [A1.selected_vehicle[0]['vehicle_make'].toLowerCase()] });
    //---filter out the selected car
    console.log(more_from_brand_cars);
    more_from_brand_cars = more_from_brand_cars.filter(v => v.vehicle_primo_id != A1.selected_vehicle[0]['vehicle_primo_id']);
    console.log(more_from_brand_cars);

    //---if nothing to show, remove tab.
    if (!more_from_brand_cars.length) {
        $('[data-bs-target="#more-from-brand"]').remove();
        $tabContents.find('#more-from-brand').remove();
    }

    more_from_brand_cars.slice(0, 10).forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete more_from_brand_cars;

    //---make the similar prices tab
    $carSlider = $tabContents.find('#similar-priced-listings').find('.row.car-slider-three');
    let main_price = A1.selected_vehicle[0]['vehicle_price'];

    let similar_priced_cars = A1.vehicles
        .map(vehicle => ({
            vehicle: vehicle,
            priceDifference: Math.abs(vehicle.vehicle_price - main_price)
        })).sort((a, b) => a.priceDifference - b.priceDifference).slice(0, 10).map(item => item.vehicle);

    similar_priced_cars = similar_priced_cars.filter(v => v.vehicle_primo_id !== A1.selected_vehicle[0]['vehicle_primo_id']);

    //---if nothing to show, remove tab.
    if (!similar_priced_cars.length) {
        $('[data-bs-target="#similar-priced-listings"]').remove();
        $tabContents.find('#similar-priced-listings').remove();
    }

    similar_priced_cars.forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete similar_priced_cars;

    //---make the same body shape tab
    $carSlider = $tabContents.find('#more-from-body-shape').find('.row.car-slider-three');
    let same_body_shape_cars = filterVehicles({ vehicle_body_shape: [A1.selected_vehicle[0]['vehicle_body_shape']] });
    same_body_shape_cars = same_body_shape_cars.filter(v => v.vehicle_primo_id !== A1.selected_vehicle[0]['vehicle_primo_id']);

    //---if nothing to show, remove tab.
    if (!same_body_shape_cars.length) {
        $('[data-bs-target="#more-from-body-shape"]').remove();
        $tabContents.find('#more-from-body-shape').remove();
    }

    same_body_shape_cars = same_body_shape_cars.slice(0, 10).forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete same_body_shape_cars;

    //---show the first tab that still exists
    $section.find('.nav.nav-tabs button:first-child').tab('show');

    //---make all of it slide
    var previewShopSliderThree = $(".car-slider-three").data("preview");
    $(".car-slider-three").slick({
        infinite: false,
        slidesToShow: previewShopSliderThree,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 1600,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,
                },
            },
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    });

    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $(".car-slider-three").slick("setPosition");
    });

    $('.slider-thumb').on('mousedown touchstart', function (event) {
        event.stopPropagation();
    });

    //---make the thumbs slide too
    $(".slider-thumb").slick({
        infinite: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        arguments: false,
    });

    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $(".slider-thumb").slick("setPosition");
    });

    $(window).on('resize orientationchange', function () {
        $('.slider-thumb').slick('resize');
    });
}

const buildCarBlockThreeCard = (vehicle, $body, $listingTemplate) => {
    let badge = false;
    let $newListing = $listingTemplate.clone();

    if (!vehicle.image_0) {
        vehicle.image_0 = '/assets/default/image/image_not_found.jpg';
    }

    $newListing.find('.image-box .image img').each(function (index) {
        if (!vehicle[`image_${index}`]) {
            $(this).parents('.image').remove();
        } else {
            $(this).attr('src', vehicle[`image_${index}`]).css('aspect-ratio', '4/3 !important');
        }
    })

    $newListing.find('.image-box .image a').attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);
    $newListing.find('.content-box h6 a').text(vehicle.vehicle_make + ' ' + vehicle.vehicle_model).attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);
    $newListing.find('.content-box .text').text(vehicle.vehicle_friendly_name);

    if (parseInt(vehicle.vehicle_mileage) !== NaN) {
        $newListing.find('.content-box ul li').eq(0).html('<i class="flaticon-speedometer"></i>' + parseInt(vehicle.vehicle_mileage).toLocaleString('en-US') + ' kilometers');
    } else {
        $newListing.find('.content-box ul li').eq(0).html('<i class="flaticon-speedometer"></i>' + 'N/A');
    }
    $newListing.find('.content-box ul li').eq(1).html('<i class="flaticon-gasoline-pump"></i>' + (vehicle.vehicle_fuel_type ? ucwords(vehicle.vehicle_fuel_type) : 'N/A'));
    $newListing.find('.content-box ul li').eq(2).html('<i class="flaticon-gearbox"></i>' + (vehicle.vehicle_transmission ? ucwords(vehicle.vehicle_transmission) : 'N/A'));

    $newListing.find('.btn-box small').text('R' + parseInt(vehicle.vehicle_price).toLocaleString('en-US'));
    if (vehicle.vehicle_old_price != 0) {
        $newListing.find('.btn-box span').text('R' + parseInt(vehicle.vehicle_old_price).toLocaleString('en-US'));
        $newListing.find('.info-badge').text('Discounted!').attr('style', 'background-color: rgba(40, 180, 15, 0.7) !important; color: var(--theme-color-light)');
        badge = true;
    } else {
        $newListing.find('.btn-box span').remove();
    }
    $newListing.find('.btn-box a').attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);

    //---try mileage or date for badge

    if (!badge && vehicle.vehicle_date_in_stock) {
        var threeWeeksAgo = new Date();
        threeWeeksAgo.setDate(threeWeeksAgo.getDate() - 21);

        var dateInStock = new Date(vehicle.vehicle_date_in_stock);
        dateInStock.setHours(0, 0, 0, 0);

        if (dateInStock > threeWeeksAgo) {
            $newListing.find('.info-badge').text('New in Stock!').attr('style', 'background-color: rgba(180, 30, 60, 0.7) !important; color: var(--theme-color-light)');
            badge = true;
        }
    }

    if (!badge && vehicle.vehicle_mileage < 50000) {
        $newListing.find('.info-badge').text('Low Mileage!').attr('style', 'background-color: rgba(40, 60, 150, 0.7) !important; color: var(--theme-color-light)');
        badge = true;
    }

    if (!badge) {
        $newListing.find('.info-badge').remove();
    }

    $body.append($newListing);
}


// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------

$(function () {
    //---render similar cars tabs
    renderExploreAllVehicleListings();

    //---bind fancybox gallery
    Fancybox.bind('[data-fancybox="gallery"]', {
        animated: false,
    });
});