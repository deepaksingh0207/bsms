var branch_office = [0];
var today = new Date();
var year3 = today.getMonth() > 3 ? today.getFullYear() : today.getFullYear() - 1;
var year2 = year3 - 1;
var year1 = year2 - 1;
var year0 = year1 - 1;

$(function () {
    $("#productionyear3").text(year2 + "-" + year3);
    $("#productionyear2").text(year1 + "-" + year2);
    $("#productionyear1").text(year0 + "-" + year1);
    $(".placeholder3").attr("placeholder", year2 + " - " + year3);
    $(".placeholder2").attr("placeholder", year1 + " - " + year2);
    $(".placeholder1").attr("placeholder", year0 + " - " + year1);
    $(".year3").val(year2 + " - " + year3);
    $(".year2").val(year1 + " - " + year2);
    $(".year1").val(year0 + " - " + year1);
    bsCustomFileInput.init();
});

$(document).on("change", "#copypermanant", function () {
    if ($(this).is(":checked")) {
        $("#register_office_address").val($("#id_permanent_address_address").val());
        $("#register_office_address_2").val($("#id_permanent_address_address_2").val());
        $("#register_office_pincode").val($("#id_permanent_address_pincode").val());
        $("#register_office_city").val($("#id_permanent_address_city").val());
        $("#register_office_country").val(getRemote(getCountryCodeById + '/' + $("#id_permanent_address_country").val()));
        $("#register_office_state").val(getRemote(getStateRegioncodeById + '/' + $("#id_permanent_address_state").val()));
    } else {
        $("#register_office_address").val("");
        $("#register_office_address_2").val("");
        $("#register_office_pincode").val("");
        $("#register_office_city").val("");
        $("#register_office_country").val("");
        $("#register_office_state").val("");
    }
});

$(document).on("click", ".fully_manufactured_radio", function () {
    if ($(this).val() === "no") { $(".sub-contractors-info").show(); }
    else {
        $(".sub-contractors-info").hide();
        $("#other_manufacturer").val("");
    }
});

$(document).on("click", ".add", function () {
    var clas = $(this).data("class");
    var sub = $(this).data("sub");
    var havesub = $(this).data("havesub");
    var lastid;
    $("." + clas).each(function (i, obj) { lastid = $(this).data("sub") == "1" ? $(this).data("sub_id") : $(this).data("id"); });

    var str = $("#" + clas + "_" + lastid).html();
    nextid = lastid + 1;

    str = str.replaceAll(clas + "_" + lastid + "_", clas + "_" + nextid + "_"); // Change id
    str = str.replaceAll(" hide", ""); // Show Delete
    str = str.replaceAll('disabled="disabled"', ""); // Remove Disabled
    str = str.replaceAll("[" + clas + "]" + "[" + lastid + "]", "[" + clas + "]" + "[" + nextid + "]"); // Change name
    str = str.replaceAll('data-id="' + lastid + '"', 'data-id="' + nextid + '"'); // Change data-id
    str = str.replaceAll('value="', 'value=""'); // Set Value Empty

    if (sub == 1) { str = str.replaceAll('data-sub_id="' + lastid + '"', 'data-sub_id="' + nextid + '"'); }
    else { str = str.replaceAll('data-id="' + lastid + '"', 'data-id="' + nextid + '"'); }
    $("." + clas + "_card_body").append(`<div class="row ` + clas + ` ` + clas + `_` + nextid + `" data-id="` + nextid + `" id="` + clas + `_` + nextid + `">` + str + `</div><hr class="` + clas + `_` + nextid + `" style="border: revert;">`);

    if (havesub == 1) {
        var subclass = $(this).data("subclass");
        $("." + clas + "_" + nextid + "_" + subclass).each(function (i, obj) {
            if (i > 0) { $(obj).remove(); $("." + clas + "_" + nextid + "_" + subclass + "_" + i).remove(); }
        });
    }
});

// maximum length validation funcation
function validateMaxLength(inputElement) {
    var inputValue = inputElement.val();
    var maxLength = parseInt(inputElement.attr("maxlength"));
    if (inputValue.length > maxLength) { inputValue = inputValue.slice(0, maxLength); inputElement.val(inputValue); }
}

$(document).on("input", ".maxlength_validation", function () { validateMaxLength($(this)); });

$(document).on("keypress", ".alphaonly", function (event) {
    var regex = new RegExp("^[A-Za-z ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) { event.preventDefault(); return false; }
});

$(".UpperCase").on("keyup", function () {
    var upperCaseText = $(this).val().toUpperCase();
    $(this).val(upperCaseText);
});

$("#id_bank_country").on("change", function () {
    var swiftBicField = $("#id_swift_bic").closest(".col-3");
    if ($(this).val() === "India") { swiftBicField.hide().val(""); id_swift_bic; }
    else { swiftBicField.show(); }
});

$(document).ready(function () {
    var isFirstInput = true;

    $(document).on("keypress", ".alphaafternumberonly", function (event) {
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

        if (isFirstInput) {
            if (/^[0-9]+$/.test(key)) { event.preventDefault(); return false; }
            isFirstInput = false;
        } else {
            if (!/^[A-Za-z0-9 ]+$/.test(key)) { event.preventDefault(); return false; }
        }
    });
});

function capitalizeFirstLetter(str) { return str.charAt(0).toUpperCase() + str.slice(1); }

$(document).on("input", ".capitalize", function () {
    var inputValue = $(this).val();
    if (inputValue.length > 0) { $(this).val(capitalizeFirstLetter(inputValue)); }
});

datePickerId.max = new Date().toISOString().split("T")[0];

$(document).on("click", ".showme", function () {
    var trigger = $(this).data("trigger");
    if ($(this).val() == trigger) { $("#" + $(this).data("show")).show(); }
    else { $("#" + $(this).data("show")).hide(); }
});

$(document).on("click", ".delete", function () {
    var nextid = $(this).data("id");
    var clas = $(this).data("class");
    var getarray = [];
    var lastid;
    $("." + clas).each(function (i, obj) { lastid = $(obj).data("id"); getarray.push(lastid); });
    if (getarray.length != 1) { $("." + clas + "_" + nextid).remove(); }
});


$(document).on("change", ".my-country", function () {
    var r = getRemote(stateByCountry + '/' + $(this).val());
    var state_options = "<option selected=''>Please Select</option>";
    $.each(r["message"], function (i, v) { state_options += `<option value="` + v.region_code + `">` + v.name + `</option>`; });
    $("#" + $(this).data("state")).empty().append(state_options);
});

$.ajax({
    type: "GET",
    url: vendorView + "/" + $("#vendor_id").val(),
    contentType: "application/x-www-form-urlencoded; charset=utf-8",
    dataType: "json",
    async: false,
    success: function (r) {
        var countries = getRemote(getCountries);
        var country_option = state_options = `<option>Please Select</option>`;
        $.each(countries.message, function (key, value) { country_option += `<option value="` + value.country_code + `">` + value.country_name + `</option>`; });
        $.each(r.message[0], function (i, v) {
            if (i == "branch_office") {
                if (v.length > 0) {
                    $(".branch_office_card_body").empty();
                    $.each(v, function (j, w) {
                        $(".branch_office_card_body").append(`<div class="row branch_office branch_office_` + j + `" data-id="` + j + `" id="branch_office_` + j + `">
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="branch_office_`+ j + `_address">Address</label><input type="text" name="branch[branch_office][` + j + `][address]" value="` + w.address + `" id="branch_office_` + j + `_address" class="form-control branch_office_` + j + `_address"></div>
                            </div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="branch_office_`+ j + `_address2">Address 1</label><input type="text" name="branch[branch_office][` + j + `][address_2]" value="` + w.address_2 + `"  id="branch_office_` + j + `_address_2" class="form-control branch_office_` + j + `_address_2"></div>
                            </div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input number"><label for="branch_office_`+ j + `_pincode">Pincode</label><input type="number" name="branch[branch_office][` + j + `][pincode]" value="` + w.pincode + `"  class="form-control maxlength_validation pincode-input branch_office_` + j + `_pincode" id="branch_office_` + j + `_pincode" maxlength="6"></div>
                            </div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="branch_office_`+ j + `_city">City</label><input type="text" name="branch[branch_office][` + j + `][city]" value="` + w.city + `"   class="form-control alphaonly capitalize branch_office_` + j + `_city" id="branch_office_` + j + `_city"></div>
                            </div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input select"><label for="branch_office_`+ j + `_country">Country</label><select name="branch[branch_office][` + j + `][country]" data-state="branch_office_` + j + `_state" class="selectpicker form-control my-select my-country branch_office_` + j + `_country" data-live-search="true" title="Select Country" id="branch_office_` + j + `_country">` + country_option + `</select></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3">
                            <div class="form-group">
                                <div class="input select"><label for="branch_office_`+ j + `_state">State</label><select name="branch[branch_office][` + j + `][state]"  class="selectpicker form-control my-select branch_office_` + j + `_state" data-live-search="true" title="Select State" id="branch_office_` + j + `_state"></select></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3">
                            <div class="form-group">
                                <div class="input number"><label for="branch_office_`+ j + `_telephone">Tel No</label><input type="number" name="branch[branch_office][` + j + `][telephone]" value="` + w.telephone + `"  class="form-control maxlength_validation branch_office_` + j + `_telephone" id="branch_office_` + j + `_telephone" maxlength="1` + j + `"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3">
                            <label>Year of Registration:</label>
                            <input name="branch[branch_office][`+ j + `][registration_year]" value="` + w.registration_year + `"  type="number" class="form-control maxlength_validation branch_office_` + j + `_registration_year" maxlength="4">
                        </div>

                        <div class="col-sm-12 col-md-3 mt-3">
                            <label>Registration No.</label>
                            <input name="branch[branch_office][`+ j + `][registration_no]" value="` + w.registration_no + `"  type="text" value="" class="form-control branch_office_` + j + `_registration_no">
                        </div>

                        <div class="col-sm-12 col-md-3 mt-3">
                            <label class="form-label">Registration Certificate</label>
                            <div class="custom-file">
                                <input name="branch[branch_office][`+ j + `][registration_certificate]" disabled="disabled" type="file" accept=".pdf" class="custom-file-input branch_office_` + j + `_registration_certificate">
                                <label class="custom-file-label">Choose File</label>
                                <a class="branch_office_` + j + `_registration_certificate"></a>
                            </div>
                        </div>
                        <div class="col-3 col-md-3 mt-4 pt-4 hide">
                            <span class="badge redbadge delete" data-toggle="tooltip" data-class="branch_office" data-placement="right" data-id="`+ j + `" data-original-title="Delete">
                                <i class="fas fa-trash"></i>
                            </span>
                        </div>
                    </div>`);
                        setTimeout(function () { $(".branch_office_" + j + "_country").val(w.country).trigger('change'); }, 1000);
                        setTimeout(function () { $(".branch_office_" + j + "_state").val(w.state); }, 4000);
                    })
                }
                $.each(v, function (j, w) {
                    $.each(w, function (k, x) {
                        if (k != "registration_certificate") { $(".branch_office_" + j + "_" + k).val(x); }
                        else { $(".branch_office_" + j + "_" + k).attr('href', x).text(x.split('/')[x.split('/').length - 1]); }
                    })
                })
            }
            else if (i == "factory") {
                if (v.length > 0) {
                    $(".factory_office_card_body").empty();
                    $.each(v, function (j, w) {
                        var commencement;
                        $.each(w.vendor_commencements, function (k, x) {
                            commencement += `<div class="col-12 col-md-12 col-lg-12 mt-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5>Actual production during preceding 3 years</h5>
                                            </div>
                                            <div class="col-2">
                                                <span class="badge lgreenbadge add float-right"
                                                    data-id="`+ k + `" data-sub="1" data-sub_id="` + k + `"
                                                    data-toggle="tooltip"
                                                    data-class="factory_office_`+ k + `_commencement"
                                                    data-placement="right" id="id_commencement_add"
                                                    title="" data-original-title="Add Commencement">
                                                    <i class="fas fa-plus-circle"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body factory_office_`+ k + `_commencement_card_body">
                                        <div class="row mb-3 factory_office_`+ k + `_commencement" data-id="` + k + `"
                                            data-sub="1" data-sub_id="`+ k + `"
                                            id="factory_office_`+ k + `_commencement_` + k + `">
                                            <div class="col-sm-12 col-md-3 col-lg-3">
                                                <label for="">Year Of Commencement Of Production</label>
                                                <input type="number" class="form-control" required="true"
                                                    value="`+ x.commencement_year + `"
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][commencement_year]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_commencement_year">
                                            </div>
                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                <label for="">Material</label>
                                                <input type="text" class="form-control" required="true"
                                                value="`+ x.commencement_material + `"
                                                name="prdflt[factory_office][`+ k + `][commencement][` + k + `][commencement_material]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_commencement_material"
                                                    placeholder="Material">
                                            </div>
                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                <label id="productionyear1">2`+ k + `2` + k + `-2` + k + `21</label>
                                                <input type="hidden" class="year1" required="true"
                                                    value="`+ x.first_year + `"
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][first_year]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_first_year">
                                                <input type="number" class="form-control placeholder1"
                                                    value="`+ x.first_year_qty + `"    
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][first_year_qty]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_first_year_qty">
                                            </div>
                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                <label id="productionyear2">2`+ k + `21-2` + k + `22</label>
                                                <input type="hidden" class="year2" required="true"
                                                    value="`+ x.second_year + `"
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][second_year]"
                                                    id="">
                                                <input type="number" class="form-control placeholder2"
                                                    value="`+ x.second_year_qty + `"
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][second_year_qty]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_second_year_qty">
                                            </div>
                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                <label id="productionyear3">2`+ k + `22-2` + k + `23</label>
                                                <input type="hidden" class="year3" required="true"
                                                    value="`+ x.third_year + `"
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][third_year]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_third_year">
                                                <input type="number" class="form-control placeholder3"
                                                    value="`+ x.third_year_qty + `"    
                                                    name="prdflt[factory_office][`+ k + `][commencement][` + k + `][third_year_qty]"
                                                    id="factory_office_`+ k + `_commencement_` + k + `_third_year_qty">
                                            </div>
                                            <div class="col-sm-12 col-md-1 col-lg-1 mt-3 pt-3 hide">
                                                <span class="badge redbadge delete"
                                                    data-toggle="tooltip" data-id="`+ k + `"
                                                    data-placement="right"
                                                    data-class="factory_office_`+ k + `_commencement"
                                                    data-original-title="Delete Address">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="factory_office_`+ k + `_commencement_` + k + `" style="border: revert;">
                                    </div>
                                </div>
                            </div>` });
                        $(".factory_office_card_body").append(`<div class="row factory_office factory_office_` + j + `" data-id="` + j + `" data-sub="` + j + `"
                        id="factory_office_`+ j + `">
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text">
                                    <label for="factory_`+ j + `_address">Address</label>
                                    <input type="text" name="prdflt[factory_office][`+ j + `][address]" required="true" value="` + w.address + `" id="factory_` + j + `_address" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text">
                                    <label for="factory_`+ j + `_address2">Address 1</label>
                                    <input type="text" name="prdflt[factory_office][`+ j + `][address_2]" required="true" value="` + w.address_2 + `" id="factory_` + j + `_address2" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input number">
                                    <label for="factory_`+ j + `_pincode">Pincode</label>
                                    <input type="number" name="prdflt[factory_office][` + j + `][pincode]" required="true" value="` + w.pincode + `" class="form-control maxlength_validation" id="factory_` + j + `_pincode" maxlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="factory_`+ j + `_city">City</label><input type="text" required="true" name="prdflt[factory_office][` + j + `][city]" class="form-control alphaonly capitalize" value="` + w.city + `" id="factory_` + j + `_city">
                                </div>
                            </div>
                        </div>

                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <label for="factory_` + j + `_country">Country</label>
                                <select name="prdflt[factory_office][`+ j + `][country]" id="factory_` + j + `_country" required="true" data-state="factory_` + j + `_state" data-live-search="true" class="selectpicker my-select my-country form-control factory_` + j + `_country">` + country_option + `</select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3 mt-3">
                            <div class="form-group">
                                <label for="factory_` + j + `_state">State</label>
                                <select name="prdflt[factory_office][`+ j + `][state]" id="factory_` + j + `_state" data-state="factory_` + j + `_state" data-live-search="true" class="selectpicker form-control my-select factory_` + j + `_state" required="true"></select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3"></div>

                        <div class="col-sm-12 col-md-3 mt-4 pt-4 hide">
                            <span class="badge redbadge delete" data-toggle="tooltip" data-id="`+ j + `" data-placement="right" data-class="factory_office"
                                data-original-title="Delete Address">
                                <i class="fas fa-trash"></i>
                            </span>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mt-4">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                    <label class="text-info">Installed Capacity</label>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" value="` + w.installed_capacity + `" required="true" name="prdflt[factory_office][` + j + `][installed_capacity]" placeholder="Installed Capacity" id="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="custom-file">
                                    <input name="prdflt[factory_office][`+ j + `][installed_capacity_file]" disabled="disabled" required="true" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label">Choose File</label>
                                    <a id="branch_office_` + j + `_installed_capacity_file" href="` + w.installed_capacity_file + `">` + (w.installed_capacity_file).split('/')[(w.installed_capacity_file).split('/').length - 1] + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mt-4">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                    <label class="text-info">Power Available</label>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" value="` + w.power_available + `" required="true" name="prdflt[factory_office][` + j + `][power_available]" placeholder="Power Available" id="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="custom-file">
                                    <input name="prdflt[factory_office][`+ j + `][power_available_file]" disabled="disabled" required="true" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label">Choose File</label>
                                    <a id="branch_office_` + j + `_power_available_file" href="` + w.power_available_file + `">` + (w.power_available_file).split('/')[(w.power_available_file).split('/').length - 1] + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mt-4">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                    <label class="text-info">Machinery Available</label>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" value="` + w.machinery_available + `" required="true" name="prdflt[factory_office][` + j + `][machinery_available]" placeholder="Machinery Available" id="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="custom-file">
                                    <input name="prdflt[factory_office][`+ j + `][machinery_available_file]" disabled="disabled" required="true" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label">Choose File</label>
                                    <a id="branch_office_` + j + `_machinery_available_file" href="` + w.machinery_available_file + `">` + (w.machinery_available_file).split('/')[(w.machinery_available_file).split('/').length - 1] + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mt-4">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                    <label class="text-info">Raw Material Avi. and Source</label>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <input type="text" class="form-control" value="` + w.raw_material + `" required="true" name="prdflt[factory_office][` + j + `][raw_material]" placeholder="Raw Material Avi. and Source" id="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="custom-file">
                                    <input name="prdflt[factory_office][`+ j + `][raw_material_file]" required="true" disabled="disabled" type="file" accept=".pdf" class="custom-file-input">
                                    <label class="custom-file-label">Choose File</label>
                                    <a id="branch_office_` + j + `_raw_material_file" href="` + w.raw_material_file + `">` + (w.raw_material_file).split('/')[(w.raw_material_file).split('/').length - 1] + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `+ commencement + `
                    </div><hr class="factory_office_`+ j + `" style="border: revert;">`);
                        setTimeout(function () { $(".factory_" + j + "_country").val(w.country).trigger('change'); }, 1000);
                        setTimeout(function () { $(".factory_" + j + "_state").val(w.state); }, 4000);
                    })
                }
            }
            else if (i == "partner_address") {
                if (v.length > 0) {
                    $(".partner_card_body").empty();
                    $.each(v, function (j, w) {
                        $(".partner_card_body").append(`<div class="row partner partner_` + j + `" data-id="` + j + `" id="partner_` + j + `">
                        <div class="col-2 mt-1">
                            <input type="radio" name="other_address[partner][`+ j + `][type]" ` + (w.type == 'Proprietor' ? `checked=""` : ``) + ` value="Proprietor">
                            <label>Proprietor</label>
                        </div>
                        <div class="col-2 mt-1">
                            <input type="radio" name="other_address[partner][`+ j + `][type]" ` + (w.type == 'Partner' ? `checked=""` : ``) + ` value="Partner">
                            <label>Partner</label>
                        </div>
                        <div class="col-2 mt-1">
                            <input type="radio" name="other_address[partner][`+ j + `][type]" ` + (w.type == 'Director' ? `checked=""` : ``) + ` value="Director">
                            <label>Director</label>
                        </div>
                        <div class="col-3 col-md-3 hide">
                            <span class="badge redbadge delete" data-toggle="tooltip" data-id="`+ j + `" data-class="partner" data-placement="right" data-original-title="Delete">
                                <i class="fas fa-trash"></i>
                            </span>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="form-group">
                                <div class="input text"><label for="other-address-`+ j + `-name">Name</label><input type="text" name="other_address[partner][` + j + `][name]" class="form-control form-control-sm alphaonly capitalize"  value="` + w.name + `" id="other-address-` + j + `-name"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="id_address">Address</label><input type="text" value="` + w.address + `"  name="other_address[partner][` + j + `][address]" class="form-control" id="id_address"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="id_address2">Address 1</label><input type="text"  value="` + w.address_2 + `" name="other_address[partner][` + j + `][address]" id="id_address2" class="form-control"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input number"><label for="id_pincode">Pincode</label><input type="number" value="` + w.pincode + `" name="other_address[partner][` + j + `][pincode]" class="form-control maxlength_validation" id="id_pincode" maxlength="6"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="id_city">City</label><input type="text" value="` + w.city + `" name="other_address[partner][` + j + `][city]" class="form-control alphaonly capitalize" id="id_city"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input select"><label for="id_country">Country</label><select name="other_address[partner][`+ j + `][country]" id="other_address_partner_` + j + `_country" data-state="other_address_partner_` + j + `_state" class="other_address_partner_` + j + `_country selectpicker form-control my-select my-country" data-live-search="true" title="Please select" empty="">` + country_option + `</select></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input select"><label for="id_state">State</label><select name="other_address[partner][`+ j + `][state]" id="other_address_partner_` + j + `_state" class="other_address_partner_` + j + `_state selectpicker form-control my-select" data-live-search="true" title="Select State"></select></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input number"><label for="id_telephone">Telephone</label><input type="number" value="` + w.telephone + `" name="other_address[partner][` + j + `][telephone]" id="id_telephone" class="form-control maxlength_validation" maxlength="10"></div></div>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3">
                            <div class="form-group">
                                <div class="input number"><label for="id_faxno">Fax No.</label><input type="number" value="` + w.fax_no + `" name="other_address[partner][` + j + `][fax_no]" id="id_faxno" class="form-control maxlength_validation" maxlength="10"></div></div>
                        </div>
                    </div><hr class="other_address_`+ j + `" style="border: revert;">`);
                        setTimeout(function () { $(".other_address_partner_" + j + "_country").val(w.country).trigger('change'); }, 1000);
                        setTimeout(function () { $(".other_address_partner_" + j + "_state").val(w.state); }, 4000);
                    })
                }
            }
            else if (i == "reputed_customer") {
                if (v.length > 0) {
                    $(".customer_card_body").empty();
                    $.each(v, function (j, w) {
                        $(".customer_card_body").append(`<div class="row customer customer_` + j + `" data-id="` + j + `" id="customer_` + j + `">
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="id_name">Customer Name</label><input type="text" value="`+ w.customer_name + `" name="reputed[customer][` + j + `][customer_name]" class="form-control alphaonly capitalize" id="id_name"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="reputed-`+ j + `-address">Address</label><input type="text" value="` + w.address + `" name="reputed[customer][` + j + `][address]" class="form-control" id="reputed-` + j + `-address"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input number"><label for="reputed_pincode">Pincode</label><input type="number" value="`+ w.pincode + `" name="reputed[customer][` + j + `][pincode]" class="form-control maxlength_validation" id="reputed_pincode" maxlength="6"></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input text"><label for="">City</label><input type="text" value="`+ w.city + `" name="reputed[customer][` + j + `][city]" class="form-control alphaonly capitalize" id=""></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input select"><label for="reputed-`+ j + `-country">Country</label><select name="reputed[customer][` + j + `][country]" class="selectpicker form-control my-select my-country reputed_customer_` + j + `_country" data-state="reputed_customer_` + j + `_state" data-live-search="true" title="Select Country" id="reputed-` + j + `-country">` + country_option + `</select></div></div>
                        </div>

                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <div class="input select"><label for="reputed[customer][`+ j + `][state]">State</label><select name="reputed[customer][` + j + `][state]" id="reputed_customer_` + j + `_state" class="selectpicker form-control my-select reputed_customer_` + j + `_state" data-live-search="true" title="Select State"></select></div></div>
                        </div>
                        <div class="col-3 mt-3 col-md-3">
                            <div class="form-group">
                                <label for="id_telephone">Telephone</label>
                                <input type="number" id="reputed_telephone" name="reputed[customer][`+ j + `][telephone]" value="` + w.telephone + `" class="form-control maxlength_validation" maxlength="10">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2 mt-3">
                            <div class="form-group">
                                <div class="input number"><label for="reputed_faxno">Fax No.</label><input type="number" value="`+ w.fax_no + `" name="reputed[customer][` + j + `][fax_no]" id="reputed_faxno" class="form-control maxlength_validation" maxlength="10"></div></div>
                        </div>
                        <div class="col-sm-12 col-md-1 mt-4 pt-4 hide">
                            <span class="badge redbadge delete" data-toggle="tooltip" data-id="`+ j + `" data-class="customer" data-placement="right" data-original-title="Delete">
                                <i class="fas fa-trash"></i>
                            </span>
                        </div>
                    </div><hr class="customer_`+ j + `" style="border: revert;">`);
                        setTimeout(function () { $(".reputed_customer_" + j + "_country").val(w.country).trigger('change'); }, 1000);
                        setTimeout(function () { $(".reputed_customer_" + j + "_state").val(w.state); }, 4000);
                    });
                }
            }
            else if (i == "facility") { $.each(v, function (j, a) { $.each(a, function (k, b) { if (k.slice(k.length - 4, k.length) != 'file') { $(".facility_" + k + "_" + b).prop('checked', true).trigger('click'); } }) }) }
            else if (i == "other_details") { $.each(v, function (j, a) { $(".other_details_" + j).val(a); }) }
            else if (i == "questionnaire") {
                if (v.length > 0) {
                    $(".questionnaire").empty();
                    $.each(v, function (j, w) { $(".questionnaire").append(`<div class="col-lg-12 mt-3"><label>` + w.question + `</label><input type="hidden" name="questionnaire[` + j + `][question]" value="` + w.question + `"><textarea placeholder="" name="questionnaire[` + j + `][answer]" class="form-control" cols="30" rows="3">` + w.answer + `</textarea></div>`); })
                }
            }
            else if (i == "income_tax") { $.each(v, function (j, w) { $(".income_tax_" + j).val(w); }) }
            else if (i == "registered_office") {
                $.each(v, function (j, w) {
                    $(".registered_office_" + j).val(w);
                    if (j == 'country') { setTimeout(function () { $(".registered_office_country").val(w); }, 1000); }
                    if (j == 'state') { setTimeout(function () { $(".registered_office_state").val(w); }, 4000); }
                })
            }
            else if (i == "small_scale") { $.each(v, function (j, w) { if ('certificate_file' != j) { $(".small_scale_" + j).val(w); } }) }
            else if (i == "turnover") { $.each(v, function (j, w) { $(".turnover_" + j).val(w); }) }
            else { $(".id_" + i).val(v) }
        });
    },
});


$(document).on("click", ".profile_submit", function () {
    var profile_form = new FormData($("#id_form_" + $(this).data('id'))[0]);
    var resp = getRemote(window.location.href, "POST", profile_form, 'json', false, false, false);
    if (resp.status == 1) { Toast.fire({ icon: "success", title: resp.msg }); }
    else { Toast.fire({ icon: "error", title: resp.msg }); }
});
