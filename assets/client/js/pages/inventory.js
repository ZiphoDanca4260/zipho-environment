// --- UPDATE FILTERS ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const renderActiveFilters = () => {
    const $body = $('[data-fr-section="display_filters"]').empty();

    for (const filter_key in A1.vehicle_search_params) {
        let filter_values = A1.vehicle_search_params[filter_key];
        let filter_description = '';

        if (Array.isArray(filter_values)) {
            if (!filter_values.length) {
                continue;
            } else {
                filter_values.forEach((value, i) => {
                    if (typeof value === 'object' && value !== null) {
                        value = JSON.stringify(value);
                    }

                    if (i === 0) {
                        filter_description += `${ucwords(value.replace('_', ' '))}`;
                    } else {
                        filter_description += ` & ${ucwords(value.replace('_', ' '))}`;
                    }
                });
            }
        } else if ((typeof filter_values !== 'function') && filter_values === Object(filter_values)) {
            if (filter_key === 'vehicle_price') {
                if (Object.keys(filter_values).length === 0) {
                    continue;
                } else if (filter_values.min == 20000 && filter_values.max == -1) {
                    continue;
                } else if (filter_values.max == -1) {
                    filter_values.max = 'All'
                }
            }

            for (const filters_nested_key in filter_values) {
                let filters_nested_value = filter_values[filters_nested_key];
                filter_description += `{ ${filters_nested_key} : ${filters_nested_value} }`
            }
        }

        let $pill = $('<div/>', { class: 'border border-secondary rounded-pill p-3 d-flex flex-column justify-content-start align-items-center' }).appendTo($body);
        let $filterName = $('<span/>', { class: 'text-decoration-underline text-nowrap', text: ucwords(filter_key.replaceAll('_', ' ')) }).appendTo($pill);
        let $removeFilterButton = $('<button/>', {
            type: 'button',
            html: '<i class="fa-thin fa-trash" style="color: red"></i>',
            class: 'btn mx-1 p-0'
        }).appendTo($filterName);

        if (filter_key == 'vehicle_price') {
            $removeFilterButton.on('click', function () {
                delete A1.vehicle_search_params[filter_key];
                $(`[data-fr-slider="${filter_key}"]`)[0].noUiSlider.set([20000, 2000000])
            })
        } else {
            $removeFilterButton.on('click', function () {
                delete A1.vehicle_search_params[filter_key];
                updateFilterInputs();
            })
        }

        let $filterValues = $('<span/>', {
            text: filter_description
        }).appendTo($pill);
    }

    if ($body.find('div').length) {
        if (!$('#display_filters_separator').length) {
            $('<hr>', { id: 'display_filters_separator' }).insertAfter($body);
        }

        if (!$body.find('h3').length) {
            $('<h3>', { class: 'text-center align-self-center me-2', text: 'Active Filters:' }).prependTo($body);
        }
    } else {
        $('#display_filters_separator').remove();
    }
}

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
            case 'vehicle_body_shape':
                vehicleTypeSelectRender($select);
                break;
            case 'vehicle_make':
                vehicleMakeSelectRender($select);
                break;
            case 'vehicle_model':
                vehicleModelSelectRender($select);
                break;
            case 'vehicle_year':
                vehicleYearSelectRender($select);
                break;
            case 'vehicle_mileage':
                vehicleMileageSelectRender($select);
                break;
            case 'vehicle_price':
                vehiclePriceRangeFilter($select);
                break;
            default:
                break;
        }
    });

    A1.vehicles_filtered = filterVehicles(A1.vehicle_search_params);
    renderActiveFilters();
    renderCarCount(A1.vehicles_filtered.length);
    A1.inventory.page = 1;
    renderInventoryPageRange();
    debouncedBuildCarBlockFour();
}
const debouncedUpdateFilterInputs = debounce(updateFilterInputs, 300)


// --- RENDER --------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const buildCarBlockFour = () => {
    const $body = $('[data-fr-section="inventory"]').empty();
    const index_start = (A1.inventory.page * A1.inventory.page_break) - A1.inventory.page_break;

    if (!A1.vehicles_filtered.length) {
        $('<div/>', {
            class: 'd-flex flex-column mt-2',
            html: '<h2 class="text-center">No vehicles found according to current filters..</h2>'
        }).appendTo($body);
        return false;
    }

    //---sort it
    const sort_by = Object.keys(A1.inventory.sort_by)[0];
    if (A1.inventory.sort_by[sort_by] == 'ascending') {
        A1.vehicles_filtered.sort((a, b) => a[sort_by] - b[sort_by])
    } else {
        A1.vehicles_filtered.sort((a, b) => b[sort_by] - a[sort_by])
    }

    for (let i = index_start; i < Math.min(A1.vehicles_filtered.length, A1.inventory.page_break + index_start); i++) {
        const vehicle = A1.vehicles_filtered[i];

        let badge = false;
        let $listing = A1.inventory.listings_template.clone();

        if (!vehicle.image_0) {
            vehicle.image_0 = '/assets/default/image/image_not_found.jpg';
        }

        $listing.find('.image-box .image img').each(function (index) {
            if (!vehicle[`image_${index}`]) {
                $(this).parents('.image').remove();
            } else {
                $(this).attr('src', vehicle[`image_${index}`]).addClass('aspect-four-to-three')
            }
        })

        $listing.find('.image-box .image a').attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);
        $listing.find('.content-box h6 a').text(vehicle.vehicle_make + ' ' + vehicle.vehicle_model).attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);
        $listing.find('.content-box .text').text(vehicle.vehicle_friendly_name);

        if (parseInt(vehicle.vehicle_mileage) !== NaN) {
            $listing.find('.content-box ul li').eq(0).html('<i class="flaticon-speedometer"></i>' + parseInt(vehicle.vehicle_mileage).toLocaleString('en-US') + ' kilometers');
        } else {
            $listing.find('.content-box ul li').eq(0).html('<i class="flaticon-speedometer"></i>' + 'N/A');
        }
        $listing.find('.content-box ul li').eq(1).html('<i class="flaticon-gasoline-pump"></i>' + (vehicle.vehicle_fuel_type ? ucwords(vehicle.vehicle_fuel_type) : 'N/A'));
        $listing.find('.content-box ul li').eq(2).html('<i class="flaticon-gearbox"></i>' + (vehicle.vehicle_transmission ? ucwords(vehicle.vehicle_transmission) : 'N/A'));

        $listing.find('.btn-box small').text('R' + parseInt(vehicle.vehicle_price).toLocaleString('en-US'));
        if (vehicle.vehicle_old_price != 0) {
            $listing.find('.btn-box span').text('R' + parseInt(vehicle.vehicle_old_price).toLocaleString('en-US'));
            $listing.find('.info-badge').text('Discounted!').attr('style', 'background-color: rgba(40, 180, 15, 0.7) !important; color: var(--theme-color-light)');
            badge = true;
        } else {
            $listing.find('.btn-box span').remove();
        }
        $listing.find('.btn-box a').attr('href', `/inventory-single?vehicle_primo_id=${vehicle.vehicle_primo_id}`);

        //---try mileage or date for badge
        if (!badge && vehicle.vehicle_date_in_stock) {
            var threeWeeksAgo = new Date();
            threeWeeksAgo.setDate(threeWeeksAgo.getDate() - 21);

            var dateInStock = new Date(vehicle.vehicle_date_in_stock);
            dateInStock.setHours(0, 0, 0, 0);

            if (dateInStock > threeWeeksAgo) {
                $listing.find('.info-badge').text('New in Stock!').attr('style', 'background-color: rgba(180, 30, 60, 0.7) !important; color: var(--theme-color-light)');
                badge = true;
            }
        }

        if (!badge && vehicle.vehicle_mileage < 50000) {
            $listing.find('.info-badge').text('Low Mileage!').attr('style', 'background-color: rgba(40, 60, 150, 0.7) !important; color: var(--theme-color-light)');
            badge = true;
        }

        if (!badge) {
            $listing.find('.info-badge').remove();
        }

        $listing.removeClass('d-none').addClass('wow fadeInUp');
        $body.append($listing);
    }

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

    //---handle pagination
    renderPagination();
}
const debouncedBuildCarBlockFour = debounce(buildCarBlockFour, 300);

const renderPagination = () => {
    const $body = $('[data-fr-section="pagination"]').empty();
    const $paginator_template = A1.inventory.paginator_template.clone();
    const total_cars = A1.vehicles_filtered.length;

    //---if pagination not needed return early
    if (!total_cars || total_cars <= A1.inventory.page_break) {
        return;
    }
    const total_page_count = Math.ceil(total_cars / A1.inventory.page_break);
    const current_page = A1.inventory.page;

    //---get page buttons
    const $page_number_li = $paginator_template.find('.page-item:nth-child(2)');
    const $page_number_ellipsis_li = $paginator_template.find('[data-fr-section="pagination_ellipsis"]');

    //---remove every button except next-prev buttons
    $('ul li:not(:first-child):not(:last-child)', $paginator_template).remove();

    // --- make paginator ---
    const total_button_count = (window.innerWidth > 450) ? 7 : 5;
    let buttons_to_render = Math.min(total_button_count, total_page_count);

    //---add the first page button
    let $currentPageButton = $page_number_li.clone();
    $currentPageButton.attr('data-fr-page-number', '1').find('a').text(1);
    $paginator_template.find('ul li:last-child').before($currentPageButton);
    buttons_to_render--;

    //---add the last page button
    $currentPageButton = $page_number_li.clone();
    $currentPageButton.attr('data-fr-page-number', total_page_count).find('a').text(total_page_count);
    $paginator_template.find('ul li:last-child').before($currentPageButton);
    buttons_to_render--;

    //---add the current page button
    if (current_page === 1 || current_page === total_page_count) {
        $paginator_template.find(`[data-fr-page-number=${current_page}]`).addClass('page-item-active');
        if (current_page === 1) {
            $paginator_template.find('ul li:first-child').addClass('disabled').find('a').attr('disabled', true);
        } else {
            $paginator_template.find('ul li:last-child').addClass('disabled').find('a').attr('disabled', true);
        }
    } else {
        $currentPageButton = $page_number_li.clone();
        $currentPageButton.attr('data-fr-page-number', current_page).addClass('page-item-active').find('a').text(current_page);
        $paginator_template.find('[data-fr-page-number="1"]').after($currentPageButton);
        buttons_to_render--;
    }

    if (!buttons_to_render) {
        $body.append($paginator_template);

        //---add page button events
        $('[data-fr-page-number]').on('click', function () {
            A1.inventory.page = parseInt($(this).attr('data-fr-page-number'));
            debouncedBuildCarBlockFour();
        });

        return;
    }

    //---render all buttons
    let direction = (current_page <= total_page_count / 2) ? 'ltr' : 'rtl';
    ltr_relative_page = 1;
    rtl_relative_page = 1;
    do {
        if (direction === 'ltr') {
            if (ltr_relative_page + current_page < total_page_count) {
                $currentPageButton = $page_number_li.clone();
                $currentPageButton.attr('data-fr-page-number', current_page + ltr_relative_page).find('a').text(current_page + ltr_relative_page);
                $paginator_template.find(`[data-fr-page-number='${current_page + ltr_relative_page - 1}']`).after($currentPageButton);
                buttons_to_render--;
                ltr_relative_page++;
                direction = 'rtl'
            } else {
                direction = 'rtl'
            }
        } else if (direction === 'rtl') {
            if (current_page - rtl_relative_page > 1) {
                $currentPageButton = $page_number_li.clone();
                $currentPageButton.attr('data-fr-page-number', current_page - rtl_relative_page).find('a').text(current_page - rtl_relative_page);
                $paginator_template.find(`[data-fr-page-number='${current_page - rtl_relative_page + 1}']`).before($currentPageButton);
                buttons_to_render--;
                rtl_relative_page++;
                direction = 'ltr'
            } else {
                direction = 'ltr'
            }
        }
    } while (buttons_to_render >= 1);

    //---render ellipsis where applicable
    if (total_page_count - current_page > Math.ceil(total_button_count / 2)) {
        let $ellipsisButton = $page_number_ellipsis_li.clone();
        let option_start = $paginator_template.find('[data-fr-page-number]:last').prev().attr('data-fr-page-number');
        const $ellipsisSelect = $ellipsisButton.find('select').empty();

        $('<option/>', { value: '', text: '', class: 'd-none' }).appendTo($ellipsisSelect);

        for (let i = option_start; i < total_page_count; i++) {
            $('<option/>', { value: i, text: i }).appendTo($ellipsisSelect);
        }

        $paginator_template.find('[data-fr-page-number]:last').prev().remove();
        $paginator_template.find('[data-fr-page-number]:last').before($ellipsisButton);
    }

    if (current_page > Math.ceil(total_button_count / 2)) {
        let $ellipsisButton = $page_number_ellipsis_li.clone();
        let option_start = $paginator_template.find('[data-fr-page-number]:first').next().attr('data-fr-page-number');
        const $ellipsisSelect = $ellipsisButton.find('select').empty();

        for (let i = option_start; i > 1; i--) {
            $('<option/>', { value: i, text: i }).appendTo($ellipsisSelect);
        }

        $paginator_template.find('[data-fr-page-number]:first').next().remove();
        $paginator_template.find('[data-fr-page-number]:first').after($ellipsisButton);
    }

    $body.append($paginator_template);

    //---add page button events
    $('[data-fr-page-number]').on('click', function () {
        A1.inventory.page = parseInt($(this).attr('data-fr-page-number'));
        debouncedBuildCarBlockFour();
    })

    //---add ellipsis select events
    $('[data-fr-select="pagination_ellipsis"]').on('change', function () {
        A1.inventory.page = parseInt($(this).val());
        debouncedBuildCarBlockFour();
    })

    $('[data-fr-section="pagination"]').find('ul li:last-child a').on('click', () => {
        A1.inventory.page++;
        debouncedBuildCarBlockFour();
    })

    $('[data-fr-section="pagination"]').find('ul li:first-child a').on('click', () => {
        A1.inventory.page--;
        debouncedBuildCarBlockFour();
    })

    renderInventoryPageRange();
}

const renderInventoryPageRange = () => {
    let text;
    if (A1.inventory.page == 1) {
        if (A1.vehicles_filtered.length < A1.inventory.page_break) {
            text = `1 - ${A1.vehicles_filtered.length}`;
        } else {
            text = `1 - ${A1.inventory.page_break}`;
        }
    } else if (A1.inventory.page == Math.ceil(A1.vehicles_filtered.length / A1.inventory.page_break)) {
        text = `${A1.inventory.page * A1.inventory.page_break - A1.inventory.page_break + 1} - ${A1.vehicles_filtered.length}`;
    } else {
        text = `${A1.inventory.page * A1.inventory.page_break - A1.inventory.page_break + 1} - ${A1.inventory.page * A1.inventory.page_break}`;
    }

    $('[data-count="inventory_page_render_range"]').each(function () {
        $(this).text(text);
    })
}


// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


$(function () {
    //---inventory config
    if (!A1.inventory) {
        A1.inventory = {}
    }

    Object.assign(A1.inventory, {
        listings_template: $('.car-block-four').clone(),
        paginator_template: $('nav:first', '[data-fr-section="pagination"]').clone(),
        page: 1,
        page_break: 16
    })

    if (!A1.inventory.sort_by) {
        A1.inventory.sort_by = { vehicle_mileage: "ascending" }
    }

    //---render vehicle type select
    $('[data-fr-select="vehicle_body_shape"]').each(function () {
        vehicleTypeSelectRender($(this))
    }).on('select2:select select2:unselect', function () {
        if ($(this).val().length) {
            A1.vehicle_search_params.vehicle_body_shape = $(this).val();
            updateFilterInputs($(this));
        } else {
            delete A1.vehicle_search_params.vehicle_body_shape;
            updateFilterInputs();
        }
    })

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

    //---render vehicle year select
    $('[data-fr-select="vehicle_year"]').each(function () {
        vehicleYearSelectRender($(this));
    }).on('select2:select select2:unselect', function () {
        if ($(this).val().length) {
            A1.vehicle_search_params.vehicle_year = $(this).val();
            updateFilterInputs($(this));
        } else {
            delete A1.vehicle_search_params.vehicle_year;
            updateFilterInputs();
        }
    });

    //---render vehicle mileage select
    $('[data-fr-select="vehicle_mileage"]').each(function () {
        vehicleMileageSelectRender($(this));
    }).on('select2:select select2:unselect', function () {
        if ($(this).val().length) {
            const json_values = $(this).val().map(function (option) {
                return JSON.parse(option);
            })
            A1.vehicle_search_params.vehicle_mileage = json_values;
            updateFilterInputs($(this));
        } else {
            delete A1.vehicle_search_params.vehicle_mileage;
            updateFilterInputs();
        }
    });

    //---render vehicle price range slider
    $('[data-fr-slider="vehicle_price"]').each(function () {

        //---special case for price range, the initialization also runs on update event (which is fucking idiotic) and it overwrites the filter,
        //so if filter was passed before initialization then save it somewhere and replace it again.
        let initial_price_filter = {};
        if (A1.vehicle_search_params.vehicle_price) {
            Object.assign(initial_price_filter, A1.vehicle_search_params.vehicle_price);
        }

        vehiclePriceRangeRender($(this));

        if (JSON.stringify(initial_price_filter) !== '{}') {
            A1.vehicle_search_params.vehicle_price = initial_price_filter;
        }
    })

    //---render 'sort by' static select
    $('[data-fr-select="sort_by"]').each(function () {
        $(this).select2({
            width: '100%',
            closeOnSelect: true,
        })

        //---remove the caret down
        $(this).siblings('.select2').addClass('remove-caret');

        //---if sort is passed by GET, select it
        if (A1.inventory.sort_by) {
            $(this).val(JSON.stringify(A1.inventory.sort_by)).trigger('change');
        }
    }).on('select2:select', function () {
        A1.inventory.sort_by = JSON.parse($(this).val());
        A1.inventory.page = 1;
        buildCarBlockFour();
    })

    //---render cars
    buildCarBlockFour();
    renderInventoryPageRange();

    //---if onload there are already search_params, then filter
    if (JSON.stringify(A1.vehicle_search_params) !== '{}') {
        updateFilterInputs(null);
    }
})