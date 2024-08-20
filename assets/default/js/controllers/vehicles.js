// --- RENDER --------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


const renderCarCount = (count) => {
    $('[data-count="vehicle_count"]').each(function () {
        $(this).text(count);
    })
};
const debouncedRenderCarCount = debounce(renderCarCount, 500);

const vehicleTypeSelectRender = ($select) => {

    let selectOptions = [];
    if (A1.vehicles_filtered) {
        A1.vehicles_filtered.forEach((element) => {
            let type = element.vehicle_body_shape + "";
            let row = { id: type, text: ucwords(type.replace('_', ' ')) };
            if (!selectOptions.some(e => e.id === row.id)) {
                selectOptions.push(row);
            }
        })
    }

    if (!selectOptions.length) {
        selectOptions = [
            {
                id: 'none',
                text: 'No types found according to current filters.',
                disabled: true
            }
        ]
    } else {
        //---set back old values if possible.
        selectOptions = selectOptions.map(function (option) {
            if (A1.vehicle_search_params['vehicle_body_shape'] && A1.vehicle_search_params['vehicle_body_shape'].includes(option.id)) {
                return { ...option, selected: true };
            } else {
                return option
            }
        })
    }

    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    $select.empty();

    $select.select2({
        width: '100%',
        placeholder: "Types",
        closeOnSelect: (window.innerWidth > 900) ? false : true,
        data: selectOptions
    })
};

const vehicleMakeSelectRender = ($select) => {

    let selectOptions = [];
    if (A1.vehicles_filtered) {
        A1.vehicles_filtered.forEach((element) => {
            let make = element.vehicle_make + "";
            let row = { id: make.toLowerCase(), text: make };
            if (!selectOptions.some(e => e.id === row.id)) {
                selectOptions.push(row);
            }
        })
    }

    if (!selectOptions.length) {
        selectOptions = [
            {
                id: 'none',
                text: 'No vehicles makes found according to current filters.',
                disabled: true
            }
        ]
    } else {
        //---set back old values if possible.
        selectOptions = selectOptions.map(function (option) {
            if (A1.vehicle_search_params['vehicle_make'] && A1.vehicle_search_params['vehicle_make'].includes(option.id)) {
                return { ...option, selected: true };
            } else {
                return option;
            }
        })
    }

    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    $select.empty();

    $select.select2({
        width: '100%',
        placeholder: "Makes",
        closeOnSelect: (window.innerWidth > 900) ? false : true,
        data: selectOptions
    })
};

const vehicleModelSelectRender = ($select) => {

    let selectOptions = [];
    if (A1.vehicles_filtered && A1.vehicles_filtered.length) {
        A1.vehicles_filtered.forEach((element) => {
            let model = element.vehicle_model + "";
            let row = { id: model.toLowerCase(), text: model };
            if (!selectOptions.some(e => e.id === row.id)) {
                selectOptions.push(row);
            }
        })
    }

    if (!selectOptions.length) {
        selectOptions = [
            {
                id: 'none',
                text: 'No vehicle models found according to current filters.',
                disabled: true
            }
        ];
    } else {
        //---set back old values if possible.
        selectOptions = selectOptions.map(function (option) {
            if (A1.vehicle_search_params['vehicle_model'] && A1.vehicle_search_params['vehicle_model'].includes(option.id)) {
                return { ...option, selected: true };
            } else {
                return option;
            }
        })
    }

    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    $select.empty();

    $select.select2({
        width: '100%',
        placeholder: "Models",
        closeOnSelect: (window.innerWidth > 900) ? false : true,
        data: selectOptions
    })
};

const vehicleYearSelectRender = ($select) => {

    let selectOptions = [];
    if (A1.vehicles_filtered && A1.vehicles_filtered.length) {
        A1.vehicles_filtered.forEach((element) => {
            let year = element.vehicle_year + "";
            let row = { id: year.toString(), text: year };
            if (!selectOptions.some(e => e.id === row.id)) {
                selectOptions.push(row);
            }
        })
    }

    if (!selectOptions.length) {
        selectOptions = [
            {
                id: 'none',
                text: 'No vehicles/year found according to current filters.',
                disabled: true
            }
        ];
    } else {
        //---set back old values if possible.
        selectOptions = selectOptions.map(function (option) {
            if (A1.vehicle_search_params['vehicle_year'] && A1.vehicle_search_params['vehicle_year'].includes(option.id.toString())) {
                return { ...option, selected: true };
            } else {
                return option
            }
        })

        selectOptions.sort((a, b) => b.id - a.id);
    }

    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    $select.empty();

    $select.select2({
        width: '100%',
        placeholder: "Years",
        closeOnSelect: (window.innerWidth > 900) ? false : true,
        data: selectOptions
    })
};

const available_mileage_options = [{ render_sequence: 1, min: -1, max: 24999 }, { render_sequence: 2, min: 25000, max: 49999 }, { render_sequence: 3, min: 50000, max: 99999 }, { render_sequence: 4, min: 100000, max: 199999 }, { render_sequence: 5, min: 200000, max: -1 }];
const vehicleMileageSelectRender = ($select) => {

    let selectOptions = [];
    if (A1.vehicles_filtered && A1.vehicles_filtered.length) {
        A1.vehicles_filtered.forEach((element) => {
            let mileage = parseInt(element.vehicle_mileage, 10);
            for (const option of available_mileage_options) {
                if ((mileage >= option.min || option.min === -1) && (mileage <= option.max || option.max === -1)) {
                    let select_option = { render_sequence: option.render_sequence, id: JSON.stringify({ min: option.min, max: option.max }), text: `${(option.min === -1) ? '0' : option.min.toLocaleString('en-US')} - ${(option.max === -1) ? 'All' : option.max.toLocaleString('en-US')}` };
                    if (!selectOptions.some(e => e.id === select_option.id)) {
                        selectOptions.push(select_option);
                    }
                }
            }
        })
    }

    if (!selectOptions.length) {
        selectOptions = [
            {
                id: 'none',
                text: 'No mileage range found according to current filters.',
                disabled: true
            }
        ];
    } else {
        //---set back old values if possible.
        selectOptions.sort((a, b) => a.render_sequence - b.render_sequence);
        selectOptions = selectOptions.map(function (option) {
            if (A1.vehicle_search_params['vehicle_mileage'] && A1.vehicle_search_params['vehicle_mileage'].includes(JSON.parse(option.id))) {
                return { ...option, selected: true };
            } else {
                return option;
            }
        })
    }

    if ($select.data('select2')) {
        $select.select2('destroy');
    }
    $select.empty();

    $select.select2({
        width: '100%',
        placeholder: "Mileages",
        closeOnSelect: (window.innerWidth > 900) ? false : true,
        data: selectOptions
    })
};

const vehiclePriceRangeRender = ($slider) => {
    if (!A1.vehicle_search_params.vehicle_price) {
        A1.vehicle_search_params.vehicle_price = {}
    }
    $slider.data('user_input', true);

    var moneyFormat = wNumb({
        decimals: 0,
        thousand: ",",
        prefix: "R",
    });

    const range = {
        min: [20000],
        max: [2000000],
    };

    noUiSlider.create($slider.get(0), {
        start: [20000, 2000000],
        step: 1,
        range: range,
        format: moneyFormat,
        connect: true,
    });

    //---set visual min and max values and also update value hidden form inputs on update
    $slider[0].noUiSlider.on('update', function (...args) {
        vehiclePriceRangeOnUpdateEvent.call($slider, ...args)
    });
};

function vehiclePriceRangeOnUpdateEvent(values, handle, unencoded) {
    const $slider = $(this);
    const $slide1 = $('[data-fr-handle="slider-range-val-el1"]', $slider.parent());
    const $slide2 = $('[data-fr-handle="slider-range-val-el2"]', $slider.parent());
    const is_user_input = $slider.data('user_input');

    const range = {
        min: [20000],
        max: [2000000],
    };

    $slide1.text(values[0]);
    $slide1.data('value', unencoded[0]);
    if (unencoded[1] == range.max[0]) {
        $slide2.text('All');
        $slide2.data('value', -1);
    } else {
        $slide2.text(values[1]);
        $slide2.data('value', unencoded[1]);
    }

    if (!is_user_input) {
        return;
    }

    if (!A1.vehicle_search_params.vehicle_price) {
        A1.vehicle_search_params.vehicle_price = {};
    }
    A1.vehicle_search_params.vehicle_price.min = Math.round(unencoded[0]);
    A1.vehicle_search_params.vehicle_price.max = (unencoded[1] == range.max[0]) ? -1 : Math.round(unencoded[1]);

    debouncedFilterVehicles(A1.vehicle_search_params).then(filtered_vehicles => {
        A1.vehicles_filtered = filtered_vehicles;
        updateFilterInputs($slider);
    }).catch(err => {
        console.warn(err);
    })
};

const vehiclePriceRangeFilter = ($slider) => {
    let prices = A1.vehicles_filtered.map(vehicle => vehicle.vehicle_price);
    let max_price = Math.max(...prices);
    let min_price = Math.min(...prices);

    //---remove the 'update' event listener
    $slider[0].noUiSlider.off('update');
    $slider.data('user_input', false);

    //---set new values
    $slider[0].noUiSlider.set([min_price, max_price]);

    setTimeout(() => {
        //---reattach 'update' event listener
        $slider[0].noUiSlider.on('update', function (...args) {
            vehiclePriceRangeOnUpdateEvent.call($slider[0], ...args)
        });
        $slider.data('user_input', true);
    }, 500);
};

// --- FILTER & SEARCH -----------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------
let minisearch; //---fuzzy search engine

const filterVehicles = (filter_params) => {

    if (!filter_params || Object.keys(filter_params).length === 0) {
        return A1.vehicles;
    }

    return A1.vehicles.filter(vehicle => {

        return Object.entries(filter_params).every(([key, value]) => {

            if (Array.isArray(value) && value.length > 0) {
                if ((typeof value[0] === 'object' && value[0] !== null) && (value[0].min !== undefined || value[0].max !== undefined)) {

                    return value.some(range => {
                        if (range.min !== undefined && vehicle[key] < range.min) {
                            return false;
                        }

                        if (range.max !== undefined && range.max != -1 && vehicle[key] > range.max) {
                            return false;
                        }

                        return true;
                    });

                } else {
                    return value.includes(vehicle[key].toString().toLowerCase());
                }

            } else if (value.min !== undefined || value.max !== undefined) {
                if (value.min !== undefined && vehicle[key] < value.min) {
                    return false;
                }

                if (value.max !== undefined && value.max != -1 && vehicle[key] > value.max) {
                    return false;
                }
                return true;

            } else if (value.unix_date_min !== undefined || value.unix_date_max !== undefined) {

                if (!vehicle[key]) {
                    return false;
                }

                const unixVehicleDate = getDateInUnix(vehicle[key]);
                if (value.unix_date_min !== undefined && value.unix_date_max !== undefined) {
                    return unixVehicleDate >= value.unix_date_min && unixVehicleDate <= value.unix_date_max;

                } else if (value.unix_date_min !== undefined) {
                    return unixVehicleDate >= value.unix_date_min;

                } else if (value.unix_date_max !== undefined) {
                    return unixVehicleDate <= value.unix_date_max;
                }

            } else {
                return true;
            }
        });

    });
}
const debouncedFilterVehicles = debouncePromise(filterVehicles, 500);

const boxSearch = function () {
    $(document).on("click", function (e) {
        var clickID = e.target.id;
        if (clickID !== "s") {
            $(".box-content-search").removeClass("active");
            $(".layout-search").removeClass("active");
            $(".show-search").val("");
        }
    });

    $(document).on("click", function (e) {
        var clickID = e.target.class;
        if (clickID !== "a111") {
            $(".show-search").removeClass("active");
        }
    });

    $(".show-search").on("click", function (event) {
        event.stopPropagation();
    });

    var input = $(".layout-search").find("input");

    input.on("input", function () {
        if ($(this).val().trim() !== "") {
            $(".box-content-search").addClass("active");
            $(".layout-search").addClass("active");
            boxSearchResults($(this).val())
        } else {
            $(".box-content-search").removeClass("active");
            $(".layout-search").removeClass("active");
        }
    });
};

const boxSearchResults = debounce((search_value) => {
    const $body = $(".box-car-search").empty();
    const results = minisearch.search(search_value);

    //---if nothing found then render "nothing found"
    if (!results.length) {
        $body.prepend($('<li class="empty-results"><div class="info"><p class="name text-center my-2">Nothing found...</p></div></li>'));
    }

    //---build results
    for (let i = 0; i < Math.min(results.length, 10); i++) {

        let vehicle_primo_id = results[i].id;
        let vehicle = A1.vehicles.find(v => v.vehicle_primo_id == vehicle_primo_id);
        let $listItem = $('<li/>', { id: vehicle.vehicle_id }).appendTo($body);
        let $linkToVehicleDetails = $('<a/>', { class: 'car-search-item', href: `/inventory?vehicle_primo_id=${vehicle.vehicle_primo_id}` }).appendTo($listItem);
        let $imgDiv = $('<div/>', { class: 'box-img' }).appendTo($linkToVehicleDetails);
        $('<img>', { alt: `Image of ${vehicle.vehicle_friendly_name}`, src: vehicle.image_1 ?? '/assets/default/image/image_not_found.jpg' }).appendTo($imgDiv);

        let $informationDiv = $('<div/>', { class: 'info' }).appendTo($linkToVehicleDetails);
        $('<p/>', { class: 'name', text: vehicle.vehicle_friendly_name }).appendTo($informationDiv);
        $('<span/>', { class: 'price', text: `R${parseInt(vehicle.vehicle_price).toLocaleString('en-US')}` }).appendTo($informationDiv);
    }

}, 500);


// --- DOCUMENT READY ------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------


$(function () {
    //---will hold all the inventory search parameters
    if (!A1.vehicle_search_params) {
        A1.vehicle_search_params = {};
    }

    //---will hold filtered vehicles
    if (!A1.vehicles_filtered) {
        A1.vehicles_filtered = A1.vehicles;
    }
    renderCarCount(A1.vehicles_filtered?.length)

    //---initiate fuzzy search lib (minisearch).
    minisearch = new MiniSearch({
        fields: ['vehicle_friendly_name', 'vehicle_make', 'vehicle_model'],
        idField: 'vehicle_primo_id',
        searchOptions: {
            fuzzy: 0.4
        }
    });
    minisearch.addAll(A1.vehicles);

    //---search box events
    boxSearch();
});