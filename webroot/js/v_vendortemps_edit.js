var country_id_option = `<option>Please Select</option>`;
var country_code_option = `<option>Please Select</option>`;
var today = new Date();
var year3 = today.getMonth() > 3 ? today.getFullYear() : today.getFullYear() - 1;
var year2 = year3 - 1;
var year1 = year2 - 1;
var year0 = year1 - 1;

$(function () {
    $(".first_year").text(year0 + "-" + year1);
    $(".second_year").text(year1 + "-" + year2);
    $(".third_year").text(year2 + "-" + year3);
    $("#id_vendor_turnovers_first_year_turnover").attr("placeholder", year0 + " - " + year1);
    $("#id_vendor_turnovers_second_year_turnover").attr("placeholder", year1 + " - " + year2);
    $("#id_vendor_turnovers_third_year_turnover").attr("placeholder", year2 + " - " + year3);
    $(".year3").val(year2);
    $(".year2").val(year1);
    $(".year1").val(year0);
    $("#id_vendor_turnovers_first_year").val(year0+"-"+year1);
    $("#id_vendor_turnovers_second_year").val(year1+"-"+year2);
    $("#id_vendor_turnovers_third_year").val(year2+"-"+year3);
    bsCustomFileInput.init();
});

function getRemote(remote_url, method = "GET", request_data = null, response_type = "json", convertapi = true, process_data = false, content_type = 'application/x-www-form-urlencoded; charset=UTF-8') {
    var resp = $.ajax({ type: method, data: request_data, dataType: response_type, url: remote_url, async: false, processData: process_data, contentType: content_type }).responseText;
    if (convertapi) { return JSON.parse(resp); }
    return resp;
}

function delte(obj) { $("#" + obj).remove(); }

function rmv_secondlast(url) { if (url != undefined || url != null) { var a = url.split('/'); return a[a.length - 1]; } }

var countries = getRemote(getCountries);
$.each(countries.message, function (key, value) {
    country_code_option += `<option value="` + value.country_code + `">` + value.country_name + `</option>`;
    country_id_option += `<option value="` + value.id + `">` + value.country_name + `</option>`;
});

$(document).on("click", ".fully_manufactured_radio", function () {
    $(".suppliers_name").show();
    if ($(this).val() === "yes") { $(".suppliers_name").hide(); $("#id_vendor_otherdetails_suppliers_name").val(""); }
});

$(document).on("change", "#copypermanant", function () {
    if ($(this).is(":checked")) {
        $("#id_vendor_registered_offices_country").val(getRemote(getCountryCodeById + '/' + $("#id_vendor_temps_country").val())).trigger('change');
        $("#id_vendor_registered_offices_address").val($("#id_vendor_temps_address").val()).prop('readonly', true);
        $("#id_vendor_registered_offices_address_2").val($("#id_vendor_temps_address_2").val()).prop('readonly', true);
        $("#id_vendor_registered_offices_pincode").val($("#id_vendor_temps_pincode").val()).prop('readonly', true);
        $("#id_vendor_registered_offices_city").val($("#id_vendor_temps_city").val()).prop('readonly', true);
        $("#id_vendor_registered_offices_state").val(getRemote(getStateRegioncodeById + '/' + $("#id_vendor_temps_state").val()));
    } else {
        $("#id_vendor_registered_offices_address").removeAttr('readonly');
        $("#id_vendor_registered_offices_address_2").removeAttr('readonly');
        $("#id_vendor_registered_offices_pincode").removeAttr('readonly');
        $("#id_vendor_registered_offices_city").removeAttr('readonly');
        $("#id_vendor_registered_offices_country").removeAttr('readonly');
        $("#id_vendor_registered_offices_state").removeAttr('readonly');
    }
});

$(document).on("change", "#id_vendor_registered_offices_country", function () {
    if ($("#copypermanant").is(":checked")) {
        $("#id_vendor_registered_offices_country").val(getRemote(getCountryCodeById + '/' + $("#id_vendor_temps_country").val()));
    }
});

$(document).on("change", "#id_vendor_registered_offices_state", function () {
    if ($("#copypermanant").is(":checked")) {
        $("#id_vendor_registered_offices_state").val(getRemote(getStateRegioncodeById + '/' + $("#id_vendor_temps_state").val()));
    }
});

$(document).on("change", ".country_id_option", function () {
    var r = getRemote(stateByCountryId + '/' + $(this).val());
    var state_code_options = "<option selected=''>Please Select</option>";
    $.each(r["message"], function (i, v) { state_code_options += `<option value="` + v.id + `">` + v.name + `</option>`; });
    $("#" + $(this).data("state")).empty().append(state_code_options);
});

$(document).on("change", ".country_code_option", function () {
    var r = getRemote(stateByCountry + '/' + $(this).val());
    var state_code_options = "<option selected=''>Please Select</option>";
    $.each(r["message"], function (i, v) { state_code_options += `<option value="` + v.region_code + `">` + v.name + `</option>`; });
    $("#" + $(this).data("state")).empty().append(state_code_options);
});

$(document).on("click", ".profile_submit", function (e) {
    e.preventDefault();
    try {
        var isValid = $(e.target).parents('form').valid();
        console.log(isValid);
        if (isValid) {
            var profile_form = new FormData($("#id_form_" + $(this).data('id'))[0]);
            console.log(profile_form);
            var resp = getRemote(window.location.href, "POST", profile_form, 'json', true, false, false);
            if (resp.status == 1) {
                Toast.fire({ icon: "success", title: resp.msg });
                $.each(resp.data, function (i, v) {
                    if (resp.data['vendor_commencements'] != undefined) { location.reload(); }
                    else { load_data(i, v) }
                });
            }
            else { Toast.fire({ icon: "error", title: resp.msg }); }
        }
    }
    catch (err) { console.log(err); }
});

$(window).on('load', function () {
    $.ajax({
        type: "GET",
        url: vendorView + "/" + $("#id_vendor_temps_id").val(),
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        async: false,
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (r, status, error) {
            console.log(r["message"]);
            $.each(r["message"], function (i, v) { load_data(i, v) });
        },
        error: function (xhr, status, error) { console.log(xhr, status, error); },
        complete: function () { $("#gif_loader").hide(); }
    });
});

function load_data(i, v) {
    switch (i) {
        case 'vendor_branch_offices':
            if (v.length > 0) { $('#id_vendor_branch_offices_body').empty(); }
            $.each(v, function (a, b) {
                $('#id_vendor_branch_offices_body').append(`<div class="card">
                <div class="card-body">
                    <input required="required" type="hidden" value="`+ b.vendor_temp_id + `" name="branch_offices[` + a + `][vendor_temp_id]" id="id_vendor_branch_offices_` + a + `_vendor_temp_id" class="vendor_temp_id branch_offices" data-id="` + a + `">
                    <input required="required" type="hidden" value="`+ b.id + `" name="branch_offices[` + a + `][id]" id="id_vendor_branch_offices_` + a + `_id">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_address">Address</label>
                            <input required="required" type="text" value="`+ b.address + `" class="form-control" name="branch_offices[` + a + `][address]" id="id_vendor_branch_offices_` + a + `_address">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_address_2">Address 1</label>
                            <input required="required" type="text" value="`+ b.address_2 + `" class="form-control" name="branch_offices[` + a + `][address_2]" id="id_vendor_branch_offices_` + a + `_address_2">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_pincode">Pincode</label>
                            <input required="required" type="number" value="`+ b.pincode + `" class="form-control maxlength_validation" maxlength="6" name="branch_offices[` + a + `][pincode]" id="id_vendor_branch_offices_` + a + `_pincode">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_city">City</label>
                            <input required="required" type="text" value="`+ b.city + `" class="form-control" name="branch_offices[` + a + `][city]" id="id_vendor_branch_offices_` + a + `_city">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_country">Country</label>
                            <select class="form-control country_code_option" data-state="id_vendor_branch_offices_` + a + `_state" name="branch_offices[` + a + `][country]" id="id_vendor_branch_offices_` + a + `_country">` + country_code_option + `</select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_state">State</label>
                            <select class="form-control" name="branch_offices[`+ a + `][state]" id="id_vendor_branch_offices_` + a + `_state"></select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_telephone">Telephone</label>
                            <input required="required" type="text" value="`+ b.telephone + `" class="form-control numberonly maxlength_validation" maxlength="14" minlength="10" name="branch_offices[` + a + `][telephone]" id="id_vendor_branch_offices_` + a + `_telephone">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_registration_year"> Year of Registration</label>
                            <input required="required" type="text" value="`+ b.registration_year + `" class="form-control maxlength_validation numberonly" maxlength="4" name="branch_offices[` + a + `][registration_year]" id="id_vendor_branch_offices_` + a + `_registration_year">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 required">
                            <label for="id_vendor_branch_offices_registration_no">Registration No</label>
                            <input required="required" type="text" value="`+ b.registration_no + `" class="form-control" name="branch_offices[` + a + `][registration_no]" id="id_vendor_branch_offices_` + a + `_registration_no">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3">
                            <label class="form-label">Registration Certificate</label>
                            <div class="custom-file">
                                <input name="branch_offices[`+ a + `][registration_certificate]" type="file" accept=".pdf" class="custom-file-input" id="id_vendor_branch_offices_` + a + `_registration_certificate">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <a href="`+ baseurl + b.registration_certificate + `">` + rmv_secondlast(b.registration_certificate) + `</a>
                        </div>
                    </div>
                </div>
            </div>`);
                $(`#id_vendor_branch_offices_` + a + `_country`).val(b.country).trigger('change');
                setTimeout(function () { $(`#id_vendor_branch_offices_` + a + `_state`).val(b.state); }, 3000);
            });
            break;
        case 'vendor_facilities':
            $.each(v, function (a, b) {
                $.each(b, function (x, y) {
                    if (x == "isi_registration" || x == "lab_facility" || x == "quality_control" || x == "sales_services" || x == "test_facility") { $(`#id_vendor_facilities_` + x + `_` + y).trigger('click'); $(`.id_vendor_facilities_` + x + '_file').trigger('click'); }
                    else if (x == "isi_registration_file" || x == "lab_facility_file" || x == "quality_control_file" || x == "sales_services_file" || x == "test_facility_file") { $(`.id_vendor_facilities_` + x).attr('target', '_blank').attr('href', baseurl + "" + y).text(rmv_secondlast(y)); }
                    else { $(`#id_vendor_facilities_` + x).val(y); }
                });
            });
            break;
        case 'vendor_factories':
            if (v.length > 0) { $("#id_vendor_factories_body").empty(); }
            $.each(v, function (a, b) {
                $('#id_vendor_factories_body').append(`
                <div class="card mb-0" id="vf_killme` + a + `">
                    <div class="card-body">
                        <div class="row" id="factory_office_`+ a + `_row0">
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <input required="required" type="hidden" value="`+ b.id + `" name="factories[` + a + `][id]" id="id_vendor_factories_` + a + `_id">
                                <input required="required" type="hidden" value="`+ b.vendor_temp_id + `" name="factories[` + a + `][vendor_temp_id]" id="id_vendor_factories_` + a + `_vendor_temp_id" data-id="` + a + `" class="vendor_factories vendor_temp_id">
                                <label for="id_vendor_factories_`+ a + `_address">Address</label>
                                <input required="required" type="text" class="form-control" value="`+ b.address + `" name="factories[` + a + `][address]" id="id_vendor_factories_` + a + `_address">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_factories_`+ a + `_address_2">Address 1</label>
                                <input required="required" type="text" class="form-control" value="`+ b.address_2 + `" name="factories[` + a + `][address_2]" id="id_vendor_factories_` + a + `_address_2">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_factories_`+ a + `_pincode">Pincode</label>
                                <input required="required" type="number" class="form-control maxlength_validation" maxlength="6" value="`+ b.pincode + `" name="factories[` + a + `][pincode]" id="id_vendor_factories_` + a + `_pincode">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_factories_`+ a + `_city">City</label>
                                <input required="required" type="text" class="form-control" value="`+ b.city + `" name="factories[` + a + `][city]" id="id_vendor_factories_` + a + `_city">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required="required" hide">
                                <label for="id_vendor_factories_`+ a + `_country">Country</label>
                                <select class="form-control country_code_option" data-state="id_vendor_factories_` + a + `_state" name="factories[` + a + `][country]" id="id_vendor_factories_` + a + `_country">` + country_code_option + `</select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required="required" hide">
                                <label for="id_vendor_factories_`+ a + `_state">State</label>
                                <select class="form-control" name="factories[` + a + `][state]" id="id_vendor_factories_` + a + `_state"></select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 hide">
                                <span class="badge redbadge delete" id="id_vendor_factories_`+ a + `_delete" data-toggle="tooltip" data-id="0" data-placement="right" data-original-title="Delete Address" required="true">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                        <div class="row" id="factory_office_`+ a + `_row1">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                        <label class="text-info">Installed Capacity</label>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input required="required" type="text" class="form-control" required="true" name="factories[`+ a + `][installed_capacity]" placeholder="Installed Capacity" value="` + b.installed_capacity + `" id="id_vendor_factories_` + a + `_installed_capacity">
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="custom-file">
                                            <input name="factories[`+ a + `][installed_capacity_file]" type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label" id="id_vendor_factories_`+ a + `_installed_capacity_file">
                                                Choose File
                                            </label>
                                        </div>
                                        <a class="id_vendor_facilities_installed_capacity_file" href="`+ b.installed_capacity_file + `">` + rmv_secondlast(b.installed_capacity_file) + `</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                        <label class="text-info">Power Consumption</label>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input required="required" type="text" class="form-control"
                                            name="factories[`+ a + `][power_available]" placeholder="Power Consumption" value="` + b.power_available + `"
                                            required="true" id="id_vendor_factories_`+ a + `_power_available">
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="custom-file">
                                            <input name="factories[`+ a + `][power_available_file]" type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label"
                                                id="id_vendor_factories_`+ a + `_power_available_file">
                                                Choose File
                                            </label>
                                        </div>
                                        <a class="id_vendor_facilities_power_available_file" href="`+ b.power_available_file + `">` + rmv_secondlast(b.power_available_file) + `</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                        <label class="text-info">Machinery Available</label>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input required="required" type="text" class="form-control" name="factories[`+ a + `][machinery_available]" value="` + b.machinery_available + `" placeholder="Machinery Available" required="true" id="id_vendor_factories_` + a + `_machinery_available">
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="custom-file">
                                            <input name="factories[`+ a + `][machinery_available_file]" type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label" id="id_vendor_factories_`+ a + `_machinery_available_file">
                                                Choose File
                                            </label>
                                        </div>
                                        <a class="id_vendor_facilities_machinery_available_file" href="`+ b.machinery_available_file + `">` + rmv_secondlast(b.machinery_available_file) + `</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-3">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                        <label class="text-info">Raw Material Avi. and Source</label>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input required="required" type="text" class="form-control" name="factories[`+ a + `][raw_material]" value="` + b.raw_material + `" placeholder="Raw Material Avi. and Source" required="true" id="id_vendor_factories_` + a + `_raw_material">
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="custom-file">
                                            <input name="factories[`+ a + `][raw_material_file]" type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label" id="id_vendor_factories_`+ a + `_raw_material_file">
                                                Choose File
                                            </label>
                                        </div>
                                        <a class="id_vendor_facilities_raw_material_file" href="`+ b.raw_material_file + `">` + rmv_secondlast(b.raw_material_file) + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="factory_office_`+ a + `_row3">
                            <div class="card-header">
                                <p style="text-transform: uppercase; font-weight: 500; font-size: inherit;">
                                    Actual production during preceding 3 years
                                    <span class="badge lgreenbadge float-right" data-sup="`+ a + `" id="id_factory_commencement_add">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="card-body" id="id_vendor_commencements_`+ a + `_body"></div>
                        </div>
                    </div>
                </div>
                `);
                if (b.vendor_commencements.length > 0) {
                    var first = true;
                    $.each(b.vendor_commencements, function (x, y) {
                        var data = `
                        <div class="row"  id="vc_killme` + a + `` + x + `">
                        <div class="col-sm-12 col-md-3 col-lg-3 required">
                        <input required="required" type="hidden" value="`+ y.id + `" name="factories[` + a + `][commencements][` + x + `][id]" id="id_vendor_factories_` + a + `_commencement_` + x + `_id">
                        <input required="required" type="hidden" value="`+ y.vendor_temp_id + `" name="factories[` + a + `][commencements][` + x + `][vendor_temp_id]" id="id_vendor_factories_` + a + `_commencement_` + x + `_vendor_temp_id" data-sup="` + a + `" data-id="` + x + `" class="factory_commencement vendor_temp_id">`
                        if (first) { data += `<label for="id_vendor_factories_` + a + `_commencement_` + x + `_commencement_year"> Year Of Commencement Of Production</label>`; }
                        data += `<input required="required" type="number" class="form-control maxlength_validation mb-2" name="factories[` + a + `][commencements][` + x + `][commencement_year]" id="id_vendor_factories_` + a + `_commencement_` + x + `_commencement_year" value="` + y.commencement_year + `" required="true" maxlength="4"></div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">`
                        if (first) { data += `<label for="id_vendor_factories_` + a + `_commencement_` + x + `_commencement_material">Material</label>`; }
                        data += `<input required="required" type="text" class="form-control  mb-2" name="factories[` + a + `][commencements][` + x + `][commencement_material]" id="id_vendor_factories_` + a + `_commencement_` + x + `_commencement_material" placeholder="Material" value="` + y.commencement_material + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">`
                        if (first) { data += `<label id="id_vendor_factories_` + a + `_commencement_` + x + `_first_year" class="year1">` + y.first_year + "-" + (parseInt(y.first_year) + 1) + `</label>`; }
                        data += `<input required="required" type="hidden" class="year1  mb-2" name="factories[` + a + `][commencements][` + x + `][first_year]" id="id_vendor_factories_` + a + `_commencement_` + x + `_first_year" value="` + y.first_year + `" required="true">
                                <input required="required" type="number" class="form-control placeholder1" name="factories[`+ a + `][commencements][` + x + `][first_year_qty]" id="id_vendor_factories_` + a + `_commencement_` + x + `_first_year_qty" value="` + y.first_year_qty + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">`
                        if (first) { data += `<label id="id_vendor_factories_` + a + `_commencement_` + x + `_second_year_qty" class="year1">` + y.second_year + "-" + (parseInt(y.second_year) + 1) + `</label>`; }
                        data += `<input required="required" type="hidden" class="year2  mb-2" name="factories[` + a + `][commencements][` + x + `][second_year]"
                                    id="id_vendor_factories_`+ a + `_commencement_` + x + `_second_year" value="` + y.second_year + `" required="true">
                                <input required="required" type="number" class="form-control placeholder2" name="factories[`+ a + `][commencements][` + x + `][second_year_qty]"
                                    id="id_vendor_factories_`+ a + `_commencement_` + x + `_second_year_qty" value="` + y.second_year_qty + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">`
                        if (first) { data += `<label id="factory_office_` + a + `_commencement_` + x + `_third_year_qty" class="year1">` + y.third_year + "-" + (parseInt(y.third_year) + 1) + `</label>`; }
                        data += `<input required="required" type="hidden" class="year3  mb-2" name="factories[` + a + `][commencements][` + x + `][third_year]" id="factory_office_` + a + `_commencement_` + x + `_third_year" value="` + y.third_year + `" required="true">
                                <input required="required" type="number" class="form-control placeholder3" name="factories[`+ a + `][commencements][` + x + `][third_year_qty]"
                                    id="factory_office_`+ a + `_commencement_` + x + `_third_year_qty" value="` + y.third_year_qty + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1 mt-2 hide">
                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="`+ x + `" data-placement="right" data-class="factory_office_` + a + `_commencement_` + x + `" data-original-title="Delete Address">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>`;
                        $(`#id_vendor_commencements_` + a + `_body`).append(data);
                        first = false;
                    });
                } else {
                    var data = `
                        <div class="row" id="vc_killme` + a + `0">
                            <div class="col-sm-12 col-md-3 col-lg-3 required">
                                <input required="required" type="hidden" value="" name="factories[` + a + `][commencements][0][id]" id="id_vendor_factories_` + a + `_commencement_0_id">
                                <input required="required" type="hidden" value="`+ $("#id_vendor_temps_id").val() + `" name="factories[` + a + `][commencements][0][vendor_temp_id]" id="id_vendor_factories_` + a + `_commencement_0_vendor_temp_id" data-sup="` + a + `" data-id="0" class="factory_commencement vendor_temp_id">
                                <label for="id_vendor_factories_` + a + `_commencement_0_commencement_year"> Year Of Commencement Of Production</label>
                                <input required="required" type="number" class="form-control maxlength_validation mb-2" name="factories[` + a + `][commencements][0][commencement_year]" id="id_vendor_factories_` + a + `_commencement_0_commencement_year" value="" required="true" maxlength="4">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label for="id_vendor_factories_` + a + `_commencement_0_commencement_material">Material</label>
                                <input required="required" type="text" class="form-control  mb-2" name="factories[` + a + `][commencements][0][commencement_material]" id="id_vendor_factories_` + a + `_commencement_0_commencement_material" placeholder="Material" value="" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label id="id_vendor_factories_` + a + `_commencement_0_first_year">` + year0 + "-" + year1 + `</label>
                                <input required="required" type="hidden" class="year1  mb-2" name="factories[` + a + `][commencements][0][first_year]" id="id_vendor_factories_` + a + `_commencement_0_first_year" value="" required="true" value="` + year0 + `">
                                <input required="required" type="number" class="form-control placeholder1" name="factories[`+ a + `][commencements][0][first_year_qty]" id="id_vendor_factories_` + a + `_commencement_0_first_year_qty" placeholder="` + year0 + `-` + year1 + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label id="id_vendor_factories_` + a + `_commencement_0_second_year_qty">` + year1 + "-" + year2 + `</label>
                                <input required="required" type="hidden" class="year2  mb-2" name="factories[` + a + `][commencements][0][second_year]"
                                        id="id_vendor_factories_`+ a + `_commencement_0_second_year" value="" required="true" value="` + year1 + `">
                                <input required="required" type="number" class="form-control placeholder2" name="factories[`+ a + `][commencements][0][second_year_qty]"
                                    id="id_vendor_factories_`+ a + `_commencement_0_second_year_qty" placeholder="` + year1 + `-` + year2 + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label id="factory_office_` + a + `_commencement_0_third_year_qty">` + year2 + "-" + year3 + `</label>
                                <input required="required" type="hidden" class="year3  mb-2" name="factories[` + a + `][commencements][0][third_year]" id="factory_office_` + a + `_commencement_0_third_year" value="" required="true" value="` + year2 + `">
                                <input required="required" type="number" class="form-control placeholder3" name="factories[`+ a + `][commencements][0][third_year_qty]"
                                    id="factory_office_`+ a + `_commencement_0_third_year_qty" placeholder="` + year2 + `-` + year3 + `" required="true">
                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1 mt-2 hide">
                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0" data-placement="right" data-class="factory_office_` + a + `_commencement_0" data-original-title="Delete Address" onclick="">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>`;
                    $(`#id_vendor_commencements_` + a + `_body`).append(data);
                }
                $("#id_vendor_factories_" + a + "_country").val(b.country).trigger('change');
                setTimeout(function () { $("#id_vendor_factories_" + a + "_state").val(b.state); }, 1000);
            });
            break;
        case 'vendor_incometaxes':
            $.each(v, function (a, b) {
                if (a == 'certificate_file' || a == 'balance_sheet_file') { $(`.id_vendor_incometaxes_` + a).attr('target', '_blank').attr('href', b).text(rmv_secondlast(b)); }
                else { $(`#id_vendor_incometaxes_` + a).val(b); }
            });
            break;
        case 'vendor_otherdetails':
            if (v.id != undefined || v.id != null) {
                $(`#id_vendor_otherdetails_id`).val(v.id);
                $(`#id_vendor_otherdetails_vendor_temp_id`).val(v.vendor_temp_id);
                if (v.fully_manufactured == "yes" || v.fully_manufactured == undefined || v.fully_manufactured == null) { $(`#id_vendor_otherdetails_fully_manufactured_yes`).trigger('click'); }
                else { $(`#id_vendor_otherdetails_fully_manufactured_no`).trigger('click'); }
                $(`#id_vendor_otherdetails_iso`).val(v.iso);
                $(`#id_vendor_otherdetails_six_sigma`).text(v.six_sigma);
                $(`#id_vendor_otherdetails_suppliers_name`).val(v.suppliers_name);
                $(`.id_vendor_otherdetails_declaration_file`).attr('target', '_blank').attr('href', baseurl + "" + v.declaration_file).text(rmv_secondlast(v.declaration_file));
                $(`.id_vendor_otherdetails_halal_file`).attr('target', '_blank').attr('href', baseurl + "" + v.halal_file).text(rmv_secondlast(v.halal_file));
                $(`.id_vendor_otherdetails_iso_file`).attr('target', '_blank').attr('href', baseurl + "" + v.iso_file).text(rmv_secondlast(v.iso_file));
                $(`.id_vendor_otherdetails_six_sigma_file`).attr('target', '_blank').attr('href', baseurl + "" + v.six_sigma_file).text(rmv_secondlast(v.six_sigma_file));
            }
            break;
        case 'vendor_partner_address':
            if (v.length > 0) { $("#id_vendor_partner_address_body").empty(); }
            $.each(v, function (a, b) {
                $("#id_vendor_partner_address_body").append(`
                <div class="card">
                    <div class="card-body">
                        <input required="required" type="hidden" value="`+ b.id + `" name="partner_address[` + a + `][id]" id="id_vendor_partner_address_` + a + `_id">
                        <input required="required" type="hidden" value="`+ b.vendor_temp_id + `" class="vendor_partner vendor_temp_id" data-id="` + a + `" name="partner_address[` + a + `][vendor_temp_id]" id="id_vendor_partner_address_` + a + `_vendor_temp_id">
                        <div class="row">
                            <div class="col-2 mt-1">
                                <input required="required" type="radio" name="partner_address[`+ a + `][type]" id="id_vendor_partner_address_` + a + `_type1" value="Proprietor">
                                <label>Proprietor</label>
                            </div>
                            <div class="col-2 mt-1">
                                <input required="required" type="radio" name="partner_address[`+ a + `][type]" id="id_vendor_partner_address_` + a + `_type2" value="Partner">
                                <label>Partner</label>
                            </div>
                            <div class="col-2 mt-1">
                                <input required="required" type="radio" name="partner_address[`+ a + `][type]" id="id_vendor_partner_address_` + a + `_type3" checked="" value="Director">
                                <label>Director</label>
                            </div>
                            <div class="col-3 col-md-3 hide">
                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0" data-class="partner" data-placement="right" data-original-title="Delete">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                            <div class="col-sm-12 col-md-12 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_name">Name</label>
                                <input required="required" type="text" value="`+ b.name + `" class="form-control" name="partner_address[` + a + `][name]" id="id_vendor_partner_address_` + a + `_name">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_address">Address</label>
                                <input required="required" type="text" value="`+ b.address + `" class="form-control" name="partner_address[` + a + `][address]" id="id_vendor_partner_address_` + a + `_address">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_address_2">Address 1</label>
                                <input required="required" type="text" value="`+ b.address_2 + `" class="form-control" name="partner_address[` + a + `][address_2]" id="id_vendor_partner_address_` + a + `_address_2">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_pincode">Pincode</label>
                                <input required="required" type="number" value="`+ b.pincode + `" class="form-control maxlength_validation" maxlength="6" name="partner_address[` + a + `][pincode]" id="id_vendor_partner_address_` + a + `_pincode">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_city">City</label>
                                <input required="required" type="text" value="`+ b.city + `" class="form-control" name="partner_address[` + a + `][city]" id="id_vendor_partner_address_` + a + `_city">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_country">Country</label>
                                <select class="form-control country_code_option" data-state="id_vendor_partner_address_`+ a + `_state" name="partner_address[` + a + `][country]" id="id_vendor_partner_address_` + a + `_country">` + country_code_option + `</select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 required">
                                <label for="id_vendor_partner_address_`+ a + `_state">State</label>
                                <select class="form-control" name="partner_address[`+ a + `][state]" id="id_vendor_partner_address_` + a + `_state"></select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3">
                                <label for="id_vendor_partner_address_`+ a + `_telephone">Telephone</label>
                                <input  minlength="10" maxlength="14" type="text" value="`+ b.telephone + `" class="numberonly form-control" name="partner_address[` + a + `][telephone]" id="id_vendor_partner_address_` + a + `_telephone">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3">
                                <label for="id_vendor_partner_address_`+ a + `_fax_no">Fax No.</label>
                                <input  minlength="10" maxlength="14" type="text" value="`+ b.fax_no + `" class="form-control" name="partner_address[` + a + `][fax_no]" id="id_vendor_partner_address_` + a + `_fax_no">
                            </div>
                        </div>
                    </div>
                </div>`)
                $(`#id_vendor_partner_address_` + a + `_country`).val(b.country).trigger("change");
                setTimeout(function () { $(`#id_vendor_partner_address_` + a + `_state`).val(b.state); }, 1000);
                switch (b.type) {
                    case 'Proprietor':
                        $(`#id_vendor_partner_address_` + a + `_type1`).trigger('click');
                        break;
                    case 'Partner':
                        $(`#id_vendor_partner_address_` + a + `_type2`).trigger('click');
                        break;
                    case `Director`:
                        $(`#id_vendor_partner_address_` + a + `_type3`).trigger('click');
                        break;
                }
            });
            break;
        case 'vendor_questionnaires':
            // $("#id_vendor_questionnaires_body").empty();
            $.each(v, function (a, b) {
                $(`#id_vendor_questionnaires_` + a + `_id`).val(b.id);
                $(`#id_vendor_questionnaires_` + a + `_vendor_temp_id`).val(b.vendor_temp_id);
                $(`#id_vendor_questionnaires_` + a + `_answer`).text(b.answer);
            });
            break;
        case 'vendor_registered_offices':
            $("#id_vendor_registered_offices_country").append(country_code_option);
            $.each(v, function (a, b) {
                if (a == 'country') { $(`#id_vendor_registered_offices_country`).val(b).trigger('change'); }
                else if (a == 'state') { setTimeout(function () { $(`#id_vendor_registered_offices_state`).val(b); }, 1000); }
                else { $(`#id_vendor_registered_offices_` + a).val(b); $(`.id_vendor_registered_offices_` + a).text(b); }
            });
            break;
        case 'vendor_reputed_customers':
            if (v.length > 0) { $('#id_vendor_reputed_customers_body').empty(); }
            $.each(v, function (a, b) {
                $("#id_vendor_reputed_customers_body").append(`
                    <div class="row"   id="rc_killme` + a + `">
                        <div class="col-3 mb-3 col-md-3">
                        <input required="required" type="hidden" value="`+ b.id + `" name="reputed_customers[` + a + `][id]" id="id_vendor_reputed_customers_` + a + `_id">
                        <input required="required" type="hidden" value="`+ b.vendor_temp_id + `" name="reputed_customers[` + a + `][vendor_temp_id]" id="id_vendor_reputed_customers_` + a + `_vendor_temp_id" data-id="` + a + `" class="reputed_customer vendor_temp_id">
                        <div class="form-group">
                            <div class="input text required">
                                <label for="id_name">Customer Name</label>
                                <input required="required" type="text" name="reputed_customers[`+ a + `][customer_name]" class="form-control alphaonly capitalize" required="required" id="id_vendor_reputed_customers_` + a + `_customer_name" value="` + b.customer_name + `" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input text required">
                                <label for="reputed-customer-0-address">Address</label>
                                <input required="required" type="text" name="reputed_customers[`+ a + `][address]" value="` + b.address + `" required="required" class="form-control" id="id_vendor_reputed_customers_` + a + `_address" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input number required">
                                <label for="reputed_pincode">Pincode</label>
                                <input required="required" type="number" name="reputed_customers[`+ a + `][pincode]" value="` + b.pincode + `" required="required" class="form-control maxlength_validation" id="id_vendor_reputed_customers_` + a + `_pincode" maxlength="6" aria-required="true">
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input text required">
                                <label for="">City</label>
                                <input required="required" type="text" name="reputed_customers[`+ a + `][city]" value="` + b.city + `" class="form-control alphaonly capitalize" required="required" id="id_vendor_reputed_customers_` + a + `_city" aria-required="true">
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input select required">
                                <label for="reputed-customer-0-country">Country</label>
                                <select name="reputed_customers[`+ a + `][country]" class="selectpicker form-control my-select country_code_option" data-state="id_vendor_reputed_customers_` + a + `_state" data-live-search="true" title="Select Country" required="required" id="id_vendor_reputed_customers_` + a + `_country"><option value="">Please select</option>` + country_code_option + `</select>
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input select required">
                                <label for="reputed_customer_`+ a + `_state">State</label>
                                <select name="reputed_customers[`+ a + `][state]" id="id_vendor_reputed_customers_` + a + `_state" class="selectpicker form-control my-select" data-live-search="true" title="Select State" required="required"><option value="">Select State</option></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <label for="id_telephone">Telephone</label>
                            <input type="text" id="id_vendor_reputed_customers_`+ a + `_telephone" value="` + b.telephone + `" name="reputed_customers[` + a + `][telephone]" class="form-control numberonly maxlength_validation"  minlength="10" maxlength="14">
                        </div>
                    </div>
                    <div class="col-3 mb-3 col-md-3">
                        <div class="form-group">
                            <div class="input number">
                                <label for="reputed_faxno">Fax No.</label>
                                <input type="number" name="reputed_customers[`+ a + `][fax_no]" value="` + b.fax_no + `" id="id_vendor_reputed_customers_` + a + `_fax_no" class="form-control maxlength_validation" minlength="10" maxlength="14">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-1 mt-4 pt-4 hide">
                        <span class="badge redbadge delete" data-toggle="tooltip" data-id="`+ a + `" data-class="customer" data-placement="right" data-original-title="Delete">
                            <i class="fas fa-trash"></i>
                        </span>
                    </div>
                </div>`);
                $("#id_vendor_reputed_customers_" + a + "_country").val(b.country).trigger('change');
                setTimeout(function () { $("#id_vendor_reputed_customers_" + a + "_state").val(b.state); }, 1000);
            });
            break;
        case 'vendor_small_scales':
            $.each(v, function (a, b) {
                $(`#id_vendor_small_scales_` + a).val(b);
                if (a == 'certificate_file') { $(`.id_vendor_small_scales_certificate_file`).attr('target', '_blank').attr('href', baseurl+""+b).text(rmv_secondlast(b)); }
            });
            break;
        case 'vendor_temps':
            $("#id_vendor_temps_bank_country").append(country_code_option);
            $.each(v, function (x, y) {
                if (x == 'country_id') { $(`#id_vendor_temps_country`).val(y).trigger('change'); }
                else if (x == 'state_id') { setTimeout(function () { $(`#id_vendor_temps_state`).val(y); }, 2000); }
                else if (x == 'pan_file' || x == 'gst_file' || x == 'bank_file' && y != "" && y != undefined && y != null) { $(`.id_vendor_temps_` + x).attr('target', '_blank').attr('href', baseurl + "" + y).text(rmv_secondlast(y)); }
                else { $(`#id_vendor_temps_` + x).val(y); }
            });
            break;
        case 'vendor_turnovers':
            $.each(v, function (a, b) { if (b != "" && b != null) { $(`#id_vendor_turnovers_` + a.toLowerCase()).val(b); } $(`.id_vendor_turnovers_` + a).text(b); });
            break;
        default:
            break;
    }
    bsCustomFileInput.init();
}

$(document).on("click", "#id_branch_offices_add", function () {
    var lastid = [];
    $(".branch_offices").each(function (i, obj) { lastid[parseInt($(this).data("id"))] = $(this).data("id") });
    lid = lastid.length;
    for (let i = 0; i < lastid.length; i++) { if (lastid[i] == undefined || lastid[i] == null) { lid = i; break; } }
    $('#id_vendor_branch_offices_body').append(`
    <div class="card" id="bo_killme` + lid + `">
        <div class="card-body">
            <input required="required" type="hidden" value="`+ $("#id_vendor_temps_id").val() + `" name="branch_offices[` + lid + `][vendor_temp_id]" id="id_vendor_branch_offices_` + lid + `_vendor_temp_id" data-id="` + lid + `" class="vendor_temp_id branch_offices">
            <input required="required" type="hidden" name="branch_offices[`+ lid + `][id]" id="id_vendor_branch_offices_` + lid + `_id">
            <div class="row">
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_address">Address</label>
                    <input required="required" type="text" class="form-control" name="branch_offices[`+ lid + `][address]" id="id_vendor_branch_offices_` + lid + `_address">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_address_2">Address 1</label>
                    <input required="required" type="text" class="form-control" name="branch_offices[`+ lid + `][address_2]" id="id_vendor_branch_offices_` + lid + `_address_2">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_pincode">Pincode</label>
                    <input required="required" type="number" class="form-control maxlength_validation" name="branch_offices[`+ lid + `][pincode]" id="id_vendor_branch_offices_` + lid + `_pincode">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_city">City</label>
                    <input required="required" type="text" class="form-control" name="branch_offices[`+ lid + `][city]" id="id_vendor_branch_offices_` + lid + `_city">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_country">Country</label>
                    <select class="form-control country_code_option" data-state="id_vendor_branch_offices_` + lid + `_state" name="branch_offices[` + lid + `][country]" id="id_vendor_branch_offices_` + lid + `_country">` + country_code_option + `</select>
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_state">State</label>
                    <select class="form-control" name="branch_offices[`+ lid + `][state]" id="id_vendor_branch_offices_` + lid + `_state"><option>Please Select</option></select>
                </div>
                <div class="col-sm-12 col-md-3 mb-3">
                    <label for="id_vendor_branch_offices_telephone">Telephone</label>
                    <input type="text"  minlength="10" maxlength="14" class="form-control numberonly" name="branch_offices[`+ lid + `][telephone]" id="id_vendor_branch_offices_` + lid + `_telephone">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_registration_year"> Year of Registration</label>
                    <input required="required" type="text" class="form-control maxlength_validation" maxlength="4" name="branch_offices[`+ lid + `][registration_year]" id="id_vendor_branch_offices_` + lid + `_registration_year">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_branch_offices_registration_no">Registration No</label>
                    <input required="required" type="text" class="form-control" name="branch_offices[`+ lid + `][registration_no]" id="id_vendor_branch_offices_` + lid + `_registration_no">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label class="form-label">Registration Certificate</label>
                    <div class="custom-file">
                        <input name="branch_offices[`+ lid + `][registration_certificate]" type="file" accept=".pdf" class="custom-file-input" id="id_vendor_branch_offices_` + lid + `_registration_certificate">
                        <label class="custom-file-label">Choose File</label>
                    </div>
                </div>
                <div class="col-3 col-md-3 mt-4 pt-4">
                    <span class="badge redbadge" onclick="delte('bo_killme` + lid + `')" data-toggle="tooltip" data-placement="right" data-original-title="Delete">
                        <i class="fas fa-trash"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    `);
    bsCustomFileInput.init();
});

$(document).on("click", "#id_vendor_partner_add", function () {
    var lastid = [];
    $(".vendor_partner").each(function (i, obj) { lastid[parseInt($(this).data("id"))] = $(this).data("id") });
    lid = lastid.length;
    for (let i = 0; i < lastid.length; i++) { if (lastid[i] == undefined || lastid[i] == null) { lid = i; break; } }
    $("#id_vendor_partner_address_body").append(`
    <div class="card" id="vp_killme` + lid + `">
        <div class="card-body">
            <input required="required" type="hidden" name="partner_address[`+ lid + `][id]" id="id_vendor_partner_address_` + lid + `_id">
            <input required="required" type="hidden" value="`+ $("#id_vendor_temps_id").val() + `" class="vendor_partner vendor_temp_id" name="partner_address[` + lid + `][vendor_temp_id]" id="id_vendor_partner_address_` + lid + `_vendor_temp_id">
            <div class="row">
                <div class="col-2 mt-1">
                    <input required="required" type="radio" name="partner_address[`+ lid + `][type]" id="id_vendor_partner_address_` + lid + `_type1" value="Proprietor">
                    <label>Proprietor</label>
                </div>
                <div class="col-2 mt-1">
                    <input required="required" type="radio" name="partner_address[`+ lid + `][type]" id="id_vendor_partner_address_` + lid + `_type2" value="Partner">
                    <label>Partner</label>
                </div>
                <div class="col-2 mt-1">
                    <input required="required" type="radio" name="partner_address[`+ lid + `][type]" id="id_vendor_partner_address_` + lid + `_type3" checked="" value="Director">
                    <label>Director</label>
                </div>
                <div class="col-3 col-md-3">
                    <span class="badge redbadge" data-toggle="tooltip" onclick="delte('vp_killme`+ lid + `')" data-placement="right" data-original-title="Delete">
                        <i class="fas fa-trash"></i>
                    </span>
                </div>
                <div class="col-sm-12 col-md-12 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_name">Name</label>
                    <input required="required" type="text" class="form-control" name="partner_address[`+ lid + `][name]" id="id_vendor_partner_address_` + lid + `_name">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_address">Address</label>
                    <input required="required" type="text" class="form-control" name="partner_address[`+ lid + `][address]" id="id_vendor_partner_address_` + lid + `_address">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_address_2">Address 1</label>
                    <input required="required" type="text" class="form-control" name="partner_address[`+ lid + `][address_2]" id="id_vendor_partner_address_` + lid + `_address_2">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_pincode">Pincode</label>
                    <input required="required" type="number" class="form-control maxlength_validation" maxlength="6" name="partner_address[`+ lid + `][pincode]" id="id_vendor_partner_address_` + lid + `_pincode">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_city">City</label>
                    <input required="required" type="text" class="form-control" name="partner_address[`+ lid + `][city]" id="id_vendor_partner_address_` + lid + `_city">
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_country">Country</label>
                    <select class="form-control country_code_option" data-state="id_vendor_partner_address_` + lid + `_state" name="partner_address[` + lid + `][country]" id="id_vendor_partner_address_` + lid + `_country">` + country_code_option + `</select>
                </div>
                <div class="col-sm-12 col-md-3 mb-3 required">
                    <label for="id_vendor_partner_address_`+ lid + `_state">State</label>
                    <select class="form-control" name="partner_address[`+ lid + `][state]" id="id_vendor_partner_address_` + lid + `_state"></select>
                </div>
                <div class="col-sm-12 col-md-3 mb-3">
                    <label for="id_vendor_partner_address_`+ lid + `_telephone">Telephone</label>
                    <input  minlength="10" maxlength="14" type="number" class="form-control numberonly maxlength_validation" name="partner_address[`+ lid + `][telephone]" id="id_vendor_partner_address_` + lid + `_telephone">
                </div>
                <div class="col-sm-12 col-md-3 mb-3">
                    <label for="id_vendor_partner_address_`+ lid + `_fax_no">Fax No.</label>
                    <input  minlength="10" maxlength="14" type="text" class="form-control" name="partner_address[`+ lid + `][fax_no]" id="id_vendor_partner_address_` + lid + `_fax_no">
                </div>
            </div>
        </div>
    </div>`)
    bsCustomFileInput.init();
});

$(document).on("click", "#id_reputed_customer_add", function () {
    var lastid = [];
    $(".reputed_customer").each(function (i, obj) { lastid[parseInt($(this).data("id"))] = $(this).data("id") });
    lid = lastid.length;
    for (let i = 0; i < lastid.length; i++) { if (lastid[i] == undefined || lastid[i] == null) { lid = i; break; } }
    $('#id_vendor_reputed_customers_body').append(`
    <div class="row"  id="rc_killme` + lid + `">
        <div class="col-12"><hr></div>
        <div class="col-3 mb-3 col-md-3">
            <input required="required" type="hidden" name="reputed_customers[`+ lid + `][id]" id="id_vendor_reputed_customers_` + lid + `_id">
            <input required="required" type="hidden" value="`+ $("#id_vendor_temps_id").val() + `" name="reputed_customers[` + lid + `][vendor_temp_id]" id="id_vendor_reputed_customers_` + lid + `_vendor_temp_id" class="reputed_customer vendor_temp_id">
            <div class="form-group">
                <div class="input text required">
                    <label for="id_name">Customer Name</label>
                    <input required="required" type="text" name="reputed_customers[`+ lid + `][customer_name]" class="form-control alphaonly capitalize" required="required" id="id_vendor_reputed_customers_` + lid + `_customer_name" aria-required="true">
                </div>
            </div>
        </div>
        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <div class="input text required">
                    <label for="reputed-customer-0-address">Address</label>
                    <input required="required" type="text" name="reputed_customers[`+ lid + `][address]" required="required" class="form-control" id="id_vendor_reputed_customers_` + lid + `_address" aria-required="true">
                </div>
            </div>
        </div>
        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <div class="input number required">
                    <label for="reputed_pincode">Pincode</label>
                    <input required="required" type="number" name="reputed_customers[`+ lid + `][pincode]" required="required" class="form-control maxlength_validation" id="id_vendor_reputed_customers_` + lid + `_pincode" maxlength="6" aria-required="true">
                </div>
            </div>
        </div>

        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <div class="input text required">
                    <label for="">City</label>
                    <input required="required" type="text" name="reputed_customers[`+ lid + `][city]" class="form-control alphaonly capitalize" required="required" id="id_vendor_reputed_customers_` + lid + `_city" aria-required="true">
                </div>
            </div>
        </div>

        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <div class="input select required">
                    <label for="reputed-customer-0-country">Country</label>
                    <select name="reputed_customers[`+ lid + `][country]" class="selectpicker form-control my-select country_code_option" data-state="id_vendor_reputed_customers_` + lid + `_state" data-live-search="true" title="Select Country" required="required" id="id_vendor_reputed_customers_` + lid + `_country"><option value="">Please select</option>` + country_code_option + `</select>
                </div>
            </div>
        </div>

        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <div class="input select required">
                    <label for="reputed_customer_`+ lid + `_state">State</label>
                    <select name="reputed_customers[`+ lid + `][state]" id="id_vendor_reputed_customers_` + lid + `_state" class="selectpicker form-control my-select" data-live-search="true" title="Select State" required="required"><option value="">Select State</option></select>
                </div>
            </div>
        </div>
        <div class="col-3 mb-3 col-md-3">
            <div class="form-group">
                <label for="id_telephone">Telephone</label>
                <input  minlength="10" maxlength="14" type="text" id="id_vendor_reputed_customers_`+ lid + `_telephone" name="reputed_customers[` + lid + `][telephone]" class="form-control numberonly maxlength_validation" required="true">
            </div>
        </div>
        <div class="col-3 mb-3 col-md-2">
            <div class="form-group">
                <div class="input number">
                    <label for="reputed_faxno">Fax No.</label>
                    <input type="number" name="reputed_customers[`+ lid + `][fax_no]" id="id_vendor_reputed_customers_` + lid + `_fax_no" class="form-control maxlength_validation" minlength="10" maxlength="14">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-1 mt-4 pt-4">
            <span class="badge redbadge" data-toggle="tooltip" onclick="delte('rc_killme`+ lid + `')" data-placement="right" data-original-title="Delete">
                <i class="fas fa-trash"></i>
            </span>
        </div>
    </div>
    `);
    bsCustomFileInput.init();
});

$(document).on("click", "#id_vendor_factories_add", function () {
    var lastid = [];
    $(".vendor_factories").each(function (i, obj) { lastid[parseInt($(this).data("id"))] = $(this).data("id") });
    lid = lastid.length;
    for (let i = 0; i < lastid.length; i++) { if (lastid[i] == undefined || lastid[i] == null) { lid = i; break; } }
    $('#id_vendor_factories_body').append(`
        <div class="card mb-0 mt-3" id="vf_killme` + lid + `">
            <div class="card-body">
                <div class="row" id="factory_office_`+ lid + `_row0">
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <input required="required" type="hidden" name="factories[`+ lid + `][id]"
                            id="id_vendor_factories_`+ lid + `_id">
                        <input required="required" value="`+ $("#id_vendor_temps_id").val() + `" type="hidden" name="factories[` + lid + `][vendor_temp_id]"
                            id="id_vendor_factories_`+ lid + `_vendor_temp_id" data-id="` + lid + `"
                            class="vendor_factories vendor_temp_id">
                        <label for="id_vendor_factories_`+ lid + `_address">Address</label>
                        <input required="required" type="text" class="form-control" name="factories[`+ lid + `][address]"
                            id="id_vendor_factories_`+ lid + `_address">
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <label for="id_vendor_factories_`+ lid + `_address_2">Address 1</label>
                        <input required="required" type="text" class="form-control" name="factories[`+ lid + `][address_2]"
                            id="id_vendor_factories_`+ lid + `_address_2">
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <label for="id_vendor_factories_`+ lid + `_pincode">Pincode</label>
                        <input required="required" type="number" class="form-control maxlength_validation" maxlength="6" name="factories[`+ lid + `][pincode]"
                            id="id_vendor_factories_`+ lid + `_pincode">
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <label for="id_vendor_factories_`+ lid + `_city">City</label>
                        <input required="required" type="text" class="form-control" name="factories[`+ lid + `][city]"
                            id="id_vendor_factories_`+ lid + `_city">
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <label for="id_vendor_factories_`+ lid + `_country">Country</label>
                        <select class="form-control country_code_option" data-state="id_vendor_factories_`+ lid + `_state" name="factories[`+ lid + `][country]"
                            id="id_vendor_factories_`+ lid + `_country">` + country_code_option + `</select>
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 required">
                        <label for="id_vendor_factories_`+ lid + `_state">State</label>
                        <select class="form-control" name="factories[`+ lid + `][state]"
                            id="id_vendor_factories_`+ lid + `_state"></select>
                    </div>
                    <div class="col-sm-12 col-md-3 mb-3 mt-4 pt-3">
                        <span class="badge redbadge delete" id="id_vendor_factories_`+ lid + `_delete"
                            data-toggle="tooltip" data-id="0" data-placement="right"
                            onclick="delte('vf_killme`+ lid + `')" 
                            data-original-title="Delete Address" required="true">
                            <i class="fas fa-trash"></i>
                        </span>
                    </div>
                </div>

                <div class="row" id="factory_office_`+ lid + `_row1">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                <label class="text-info">Installed Capacity</label>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <input required="required" type="text" class="form-control" required="true"
                                    name="factories[`+ lid + `][installed_capacity]"
                                    placeholder="Installed Capacity"
                                    id="id_vendor_factories_`+ lid + `_installed_capacity">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="custom-file">
                                    <input required="required" name="factories[`+ lid + `][installed_capacity_file]" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label"
                                        id="id_vendor_factories_`+ lid + `_installed_capacity_file">
                                        Choose File
                                    </label>
                                </div>
                                <a class="id_vendor_facilities_installed_capacity_file"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                <label class="text-info">Power Consumption</label>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <input required="required" type="text" class="form-control"
                                    name="factories[`+ lid + `][power_available]"
                                    placeholder="Power Consumption" required="true"
                                    id="id_vendor_factories_`+ lid + `_power_available">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="custom-file">
                                    <input required="required" name="factories[`+ lid + `][power_available_file]" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label"
                                        id="id_vendor_factories_`+ lid + `_power_available_file">
                                        Choose File
                                    </label>
                                </div>
                                <a class="id_vendor_facilities_power_available_file"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                <label class="text-info">Machinery Available</label>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <input required="required" type="text" class="form-control"
                                    name="factories[`+ lid + `][machinery_available]"
                                    placeholder="Machinery Available" required="true"
                                    id="id_vendor_factories_`+ lid + `_machinery_available">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="custom-file">
                                    <input required="required" name="factories[`+ lid + `][machinery_available_file]" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label"
                                        id="id_vendor_factories_`+ lid + `_machinery_available_file">
                                        Choose File
                                    </label>
                                </div>
                                <a class="id_vendor_facilities_machinery_available_file"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                <label class="text-info">Raw Material Avi. and Source</label>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <input required="required" type="text" class="form-control"
                                    name="factories[`+ lid + `][raw_material]"
                                    placeholder="Raw Material Avi. and Source" required="true"
                                    id="id_vendor_factories_`+ lid + `_raw_material">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="custom-file">
                                    <input required="required" name="factories[`+ lid + `][raw_material_file]" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label"
                                        id="id_vendor_factories_`+ lid + `_raw_material_file">
                                        Choose File
                                    </label>
                                </div>
                                <a class="id_vendor_facilities_raw_material_file"></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="factory_office_`+ lid + `_row3">
                    <div class="card-header">
                        <p style="text-transform: uppercase; font-weight: 500; font-size: inherit;">
                            Actual production during preceding 3 years
                            <span class="badge lgreenbadge float-right" data-sup="` + lid + `" id="id_factory_commencement_add">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                        </p>
                    </div>
                    <div class="card-body" id="id_vendor_commencements_`+ lid + `_body">
                        <div class="row" id="vc_killme0">
                            <div class="col-sm-12 col-md-3 col-lg-3 required">
                                <input required="required" type="hidden" name="factories[`+ lid + `][commencements][0][id]"
                                id="id_vendor_factories_`+ lid + `_commencement_0_id">
                                <input required="required" value="`+ $("#id_vendor_temps_id").val() + `" type="hidden" name="factories[` + lid + `][commencements][0][vendor_temp_id]"
                                id="id_vendor_factories_`+ lid + `_commencement_0_vendor_temp_id" data-sup="` + lid + `" data-id="0"
                                class="factory_commencement vendor_temp_id">
                                <label for="id_vendor_factories_`+ lid + `_commencement_0_commencement_year">
                                    Year Of Commencement Of Production</label>
                                <input required="required" type="number" class="form-control maxlength_validation mb-2"
                                    name="factories[`+ lid + `][commencements][0][commencement_year]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_commencement_year"
                                    required="true" maxlength="4">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label
                                    for="id_vendor_factories_`+ lid + `_commencement_0_commencement_material">Material</label>
                                <input required="required" type="text" class="form-control mb-2"
                                    name="factories[`+ lid + `][commencements][0][commencement_material]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_commencement_material"
                                    placeholder="Material" required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label id="id_vendor_factories_`+ lid + `_commencement_0_first_year">` + year0 + '-' + year1 + `</label>
                                <input required="required" type="hidden" class="year1"
                                    name="factories[`+ lid + `][commencements][0][first_year]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_first_year"
                                    required="true">
                                <input required="required" type="number" class="form-control placeholder1 mb-2"
                                    name="factories[`+ lid + `][commencements][0][first_year_qty]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_first_year_qty" placeholder="` + year0 + `-` + year1 + `"
                                    required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label
                                    id="id_vendor_factories_`+ lid + `_commencement_0_second_year_qty">` + year1 + '-' + year2 + `</label>
                                <input required="required" type="hidden" class="year2"
                                    name="factories[`+ lid + `][commencements][0][second_year]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_second_year"
                                    required="true">
                                <input required="required" type="number" class="form-control placeholder2 mb-2"
                                    name="factories[`+ lid + `][commencements][0][second_year_qty]"
                                    id="id_vendor_factories_`+ lid + `_commencement_0_second_year_qty" placeholder="` + year1 + `-` + year2 + `"
                                    required="true">
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 required">
                                <label id="factory_office_`+ lid + `_commencement_0_third_year_qty">` + year2 + '-' + year3 + `</label>
                                <input required="required" type="hidden" class="year3"
                                    name="factories[`+ lid + `][commencements][0][third_year]"
                                    id="factory_office_`+ lid + `_commencement_0_third_year" required="true">
                                <input required="required" type="number" class="form-control placeholder3 mb-2"
                                    name="factories[`+ lid + `][commencements][0][third_year_qty]"
                                    id="factory_office_`+ lid + `_commencement_0_third_year_qty" placeholder="` + year2 + `-` + year3 + `" 
                                    required="true">
                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1 mt-2 hide">
                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                    data-placement="right" data-class="factory_office_`+ lid + `_commencement_0"
                                    data-original-title="Delete Address">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);

    bsCustomFileInput.init();
});

$(document).on("click", "#id_factory_commencement_add", function () {
    var lastid = [];
    sup = $(this).data("sup");
    $(".factory_commencement").each(function (i, obj) { if (sup == $(obj).data("sup")) { lastid[parseInt($(this).data("id"))] = $(this).data("id") } });
    lid = lastid.length;
    for (let i = 0; i < lastid.length; i++) { if (lastid[i] == undefined || lastid[i] == null) { lid = i; break; } }
    $('#id_vendor_commencements_' + sup + '_body').append(`
        <div class="row" id="vc_killme`+ sup + `` + lid + `">
            <div class="col-sm-12 col-md-3 col-lg-3 required">
                <input required="required" type="hidden" name="factories[`+ sup + `][commencements][` + lid + `][id]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_id">
                <input required="required" type="hidden" value="`+ $("#id_vendor_temps_id").val() + `" name="factories[` + sup + `][commencements][` + lid + `][vendor_temp_id]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_vendor_temp_id" data-sup="` + sup + `" data-id="` + lid + `"
                    class="factory_commencement vendor_temp_id">
                <input required="required" type="number" class="form-control maxlength_validation mb-2"
                    name="factories[`+ sup + `][commencements][` + lid + `][commencement_year]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_commencement_year"
                    required="true" maxlength="4">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 required">
                <input required="required" type="text" class="form-control mb-2"
                    name="factories[`+ sup + `][commencements][` + lid + `][commencement_material]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_commencement_material"
                    placeholder="Material" required="true">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 required">
                <input required="required" type="hidden" class="year1"
                    name="factories[`+ sup + `][commencements][` + lid + `][first_year]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_first_year"
                    required="true" value="`+ year0 + `">
                <input required="required" type="number" class="form-control placeholder1 mb-2"
                    name="factories[`+ sup + `][commencements][` + lid + `][first_year_qty]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_first_year_qty" placeholder="` + year0 + `-` + year1 + `"
                    required="true">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 required">
                <input required="required" type="hidden" class="year2"
                    name="factories[`+ sup + `][commencements][` + lid + `][second_year]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_second_year"
                    required="true" value="`+ year1 + `">
                <input required="required" type="number" class="form-control placeholder2 mb-2"
                    name="factories[`+ sup + `][commencements][` + lid + `][second_year_qty]"
                    id="id_vendor_factories_0_commencement_`+ sup + `_second_year_qty" placeholder="` + year1 + `-` + year2 + `"
                    required="true">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 required">
                <input required="required" type="hidden" class="year3"
                    name="factories[`+ sup + `][commencements][` + lid + `][third_year]"
                    id="factory_office_`+ sup + `_commencement_` + sup + `_third_year"
                    required="true" value="`+ year2 + `">
                <input required="required" type="number" class="form-control placeholder3 mb-2"
                    name="factories[`+ sup + `][commencements][` + lid + `][third_year_qty]"
                    id="factory_office_`+ sup + `_commencement_` + sup + `_third_year_qty" placeholder="` + year2 + `-` + year3 + `"
                    required="true">
            </div>
            <div class="col-sm-12 col-md-1 col-lg-1 mt-2">
                <span class="badge redbadge delete" data-toggle="tooltip" data-placement="right"
                    data-original-title="Delete Address" onclick="delte('vc_killme`+ sup + `` + lid + `')">
                    <i class="fas fa-trash"></i>
                </span>
            </div>
        </div>
    `);
    bsCustomFileInput.init();
});

function validateMaxLength(inputElement) {
    var inputValue = inputElement.val();
    var maxLength = parseInt(inputElement.attr("maxlength"));
    if (inputValue.length > maxLength) { inputValue = inputValue.slice(0, maxLength); inputElement.val(inputValue); }
}

$(document).on("input", ".maxlength_validation", function () { validateMaxLength($(this)); });

$(document).on("click", ".id_vendor_facilities_lab_facility", function () {
    if ($(this).val() == "yes") { $("#id_vendor_facilities_lab_facility_file").show(); }
    else { $("#id_vendor_facilities_lab_facility_file").hide(); }
});

$(document).on("click", ".id_vendor_facilities_isi_registration", function () {
    if ($(this).val() == "yes") { $("#id_vendor_facilities_isi_registration_file").show(); }
    else { $("#id_vendor_facilities_isi_registration_file").hide(); }
});

$(document).on("click", ".id_vendor_facilities_test_facility", function () {
    if ($(this).val() == "yes") { $("#id_vendor_facilities_test_facility_file").show(); }
    else { $("#id_vendor_facilities_test_facility_file").hide(); }
});

$(document).on("click", ".id_vendor_facilities_sales_services", function () {
    if ($(this).val() == "yes") { $("#id_vendor_facilities_sales_services_file").show(); }
    else { $("#id_vendor_facilities_sales_services_file").hide(); }
});

$(document).on("click", ".id_vendor_facilities_quality_control", function () {
    if ($(this).val() == "yes") { $("#id_vendor_facilities_quality_control_file").show(); }
    else { $("#id_vendor_facilities_quality_control_file").hide(); }
});
