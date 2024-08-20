// --- RENDER --------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const updateFilterInputs = ($exclude) => {
    A1.vehicles_filtered = filterVehicles(A1.vehicle_search_params);

    //---update multi-selects
    let $selects = $('[data-fr-select], [data-fr-slider]');
    $selects.each((index, $select) => {
        $select = $($select);

        if ($exclude && $select[0] == $exclude[0]) {
            return;
        }

        let type = $select.data('fr-select') ? 'select' : 'slider';
        let name = $select.data(`fr-${type}`);

        switch (name) {
            case 'vehicle_make':
                vehicleMakeSelectRender($select);
                break;
            case 'vehicle_model':
                vehicleModelSelectRender($select);
                break;
            case 'vehicle_price':
                vehiclePriceRangeFilter($select);
                break;
            default:
                break;
        }
    });

    A1.vehicles_filtered = filterVehicles(A1.vehicle_search_params);
    renderCarCount(A1.vehicles_filtered.length);
}


// --- RENDER --------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const renderPopularMakesLogos = () => {

    const $section = $('.boxcar-brand-section');
    const $body = $section.find('.row');

    //---filter to see which has the most cars
    const counts = A1.vehicles.reduce(function (acc, vehicle) {
        var make = vehicle.vehicle_make;
        if (typeof acc[make] === 'undefined') {
            acc[make] = 0;
        }
        acc[make]++;
        return acc;
    }, {});

    const result = Object.keys(counts).map(function (make) {
        return { vehicle_make: make, count: counts[make] };
    }).sort(function (a, b) {
        return b.count - a.count; //sort in descending order of count
    })
    const available_brand_logos = ['bmw', 'audi', 'ford', 'hyundai', 'land-rover', 'mercedes-benz', 'peugeot', 'toyota', 'volkswagen']; //available in .png

    //---render the results if they have a logo.
    let rendered_brands_count = 0;
    for (let index = 0; index < Math.min(result.length, 10); index++) {
        const vehicle = result[index];

        //---if picture not available, send a warning to developer
        if (!available_brand_logos.includes(vehicle.vehicle_make.toLowerCase().replace(' ', '-'))) {
            //TODO:

            continue;
        }

        let $wrapperDiv = $('<div/>', { class: 'cars-block style-1 w-auto' }).appendTo($body);
        let $innerDiv = $('<div/>', { class: 'inner-box wow fadeInUp' }).appendTo($wrapperDiv);

        let $imageDiv = $('<div/>', { class: 'image-box d-block' }).appendTo($innerDiv);
        let $imgLinkWrapper = $('<a/>', { href: `/inventory?vehicle_make=${vehicle.vehicle_make.toLowerCase()}` }).appendTo($imageDiv);
        $('<img>', { class: 'p-2 img-fluid', src: `/assets/default/image/brands-logos/${vehicle.vehicle_make.toLowerCase().replace(' ', '-')}.png` }).appendTo($imgLinkWrapper);

        let $contentBox = $('<div/>', { class: 'content-box' }).appendTo($innerDiv);
        let $title = $('<div/>', { class: 'title' }).appendTo($contentBox);
        $('<a/>', { href: `/inventory?vehicle_make=${vehicle.vehicle_make.toLowerCase()}`, text: vehicle.vehicle_make }).appendTo($title);
    }

    return result;
}

const renderPopularMakesListings = () => {
    let $section = $('.cars-section-two');
    let $tabs = $section.find('.nav-tabs');
    let $tabContents = $section.find('.tab-content');

    let $listingTemplate = $section.find('.car-block-two').clone();
    $section.find('.car-block-two').remove();

    for (let index = 0; index < 3; index++) {
        let vehicles = A1.vehicles.filter(v => v.vehicle_make === A1.vehicle_top_brands[index].vehicle_make);

        //--- make the tab
        $tabs.find(`[data-bs-target="#popular-makes-listings-${index}"]`).text(A1.vehicle_top_brands[index].vehicle_make);

        //--- make the tab content
        let $carSlider = $tabContents.find(`#popular-makes-listings-${index}`).find('.row.car-slider');

        vehicles.slice(0, 10).forEach(vehicle => {
            let badge = false;
            let $newListing = $listingTemplate.clone();

            if (!vehicle.image_0) {
                vehicle.image_0 = '/assets/default/image/image_not_found.jpg'
            }

            $newListing.find('.image-box figure a').attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);
            $newListing.find('.image-box figure a img').attr("src", vehicle.image_0).css('aspect-ratio', '4/3 !important');
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
            if (!badge) {
                if (vehicle.vehicle_date_in_stock) {
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
            }

            if (!badge) {
                $newListing.find('.info-badge').remove();
            }

            $carSlider.append($newListing);
        });
    }

    //---make it slide
    var previewShopSlider = $(".car-slider").data("preview");
    $(".car-slider").slick({
        infinite: false,
        slidesToShow: previewShopSlider,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        responsive: [
            {
                breakpoint: 1440,
                settings: {
                    slidesToShow: 1.9,
                    slidesToScroll: 1,
                    infinite: true,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1.02,
                    slidesToScroll: 1,
                    infinite: true,
                },
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1.2,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1.02,
                    slidesToScroll: 1,
                },
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    });

    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $(".car-slider").slick("setPosition");
    });
}

const renderExploreAllVehicleListings = () => {
    let $section = $('.cars-section-three');
    let $tabContents = $section.find('.tab-content');

    let $listingTemplate = $section.find('.car-block-three').clone();
    $section.find('.car-block-three').remove();

    //---make the new cars tab
    let $carSlider = $tabContents.find('#new-listings-tab').find('.row.car-slider-three');
    let threeWeeksAgoInUnix = Math.floor(new Date().setDate(new Date().getDate() - 21) / 1000);
    let new_cars = filterVehicles({ 'vehicle_date_in_stock': { unix_date_min: threeWeeksAgoInUnix } });

    new_cars.slice(0, 10).forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete new_cars;

    //---make the best deals tab
    $carSlider = $tabContents.find('#best-deals-tab').find('.row.car-slider-three');
    let listings_with_best_deals = filterVehicles({ 'vehicle_old_price': { min: 1 } });
    listings_with_best_deals.sort((vehicleA, vehicleB) => {
        return (vehicleB.vehicle_old_price - vehicleB.vehicle_price) - (vehicleA.vehicle_old_price - vehicleA.vehicle_price);
    });
    listings_with_best_deals.slice(0, 10).forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete listings_with_best_deals;

    //---make the premium cars (most expensive) tab
    $carSlider = $tabContents.find('#premium-cars-tab').find('.row.car-slider-three');
    let most_expensive_cars = [...A1.vehicles];
    most_expensive_cars.sort((carA, carB) => {
        return carB.vehicle_price - carA.vehicle_price;
    });
    most_expensive_cars = most_expensive_cars.slice(0, 10).forEach(vehicle => {
        buildCarBlockThreeCard(vehicle, $carSlider, $listingTemplate);
    });
    delete most_expensive_cars;

    //---make all of it slide
    var previewShopSliderThree = $(".car-slider-three:not(.testimonials-slider)").data("preview");
    $(".car-slider-three:not(.testimonials-slider)").slick({
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


// --- MISC ----------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const buildQuery = (obj) => {
    const urlSearchParams = new URLSearchParams();

    function processObject(obj, prefix = '') {
        for (let [key, value] of Object.entries(obj)) {
            let urlKey = prefix ? `${prefix}[${key}]` : key;

            if (value instanceof Object && !Array.isArray(value)) {
                processObject(value, urlKey);
            } else {
                urlSearchParams.append(urlKey, value);
            }
        }
    }

    processObject(obj);
    return urlSearchParams.toString();
}

const searchVehicles = () => {
    const search_params = buildQuery(A1.vehicle_search_params);
    window.location.href = `${window.location.origin}/inventory?${search_params}`
}


// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


$(function () {
    //---set vehicle counts according to filters
    renderCarCount(A1.vehicles.length);

    //---render vehicle make select
    $('[data-fr-select="vehicle_make"]').each(function () {
        vehicleMakeSelectRender($(this));
    }).on('select2:select select2:unselect', function () {
        if ($(this).val().length) {
            A1.vehicle_search_params.vehicle_make = $(this).val();
            updateFilterInputs($(this));
        } else {
            delete A1.vehicle_search_params.vehicle_make;
            updateFilterInputs();
        }
    });

    //---render vehicle model select
    $('[data-fr-select="vehicle_model"]').each(function () {
        vehicleModelSelectRender($(this));
    }).on('select2:select select2:unselect', function () {
        if ($(this).val().length) {
            A1.vehicle_search_params.vehicle_model = $(this).val();
            updateFilterInputs($(this));
        } else {
            delete A1.vehicle_search_params.vehicle_model;
            updateFilterInputs();
        }
    });

    //---render vehicle price range slider
    $('[data-fr-slider="vehicle_price"]').each(function () {
        vehiclePriceRangeRender($(this));
        $(this).data('user-input');

        this.noUiSlider.on('update', function () {
            debouncedFilterVehicles(A1.vehicle_search_params).then(filtered_vehicles => {
                A1.vehicles_filtered = filtered_vehicles;
                renderCarCount(A1.vehicles_filtered.length);
            }).catch(err => {
                console.warn(err);
            })
        })

        //---the values are set immediately, this is unnecessary, remove the values.
        delete A1.vehicle_search_params?.vehicle_price;
    })

    //---render popular makes logos section
    A1.vehicle_top_brands = renderPopularMakesLogos();

    //---render listing sections
    renderPopularMakesListings();
    renderExploreAllVehicleListings();

    //---activate the search button
    $('[data-fr-action="search_vehicles"]').on('click', searchVehicles);

    //---make testimonials slide
    var previewShopSliderThree = $(".testimonials-slider").data("preview");
    $(".testimonials-slider").slick({
        infinite: false,
        slidesToShow: previewShopSliderThree,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 1600,
                settings: {
                    slidesToShow: 2.2,
                    slidesToScroll: 1,
                    infinite: false,
                },
            },
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: false,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1.5,
                    slidesToScroll: 1,
                    infinite: false,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1.2,
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
        $(".testimonials-slider").slick("setPosition");
    });
});