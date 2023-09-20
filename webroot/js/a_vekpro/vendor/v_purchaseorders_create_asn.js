$('#OpenImgUpload').click(function () { $('#imgupload').trigger('click'); });

$(document).on('click', '#continueSub', function () {
    $('.checkBoxClass').each(function () { if ($(this).is(':checked')) { $("#qty" + $(this).data("id")).attr("name", "footer_id_qty[]") } });
    $('#asnForm').submit();
});

$(document).on("click", ".flu", function () {
    if ($(this).data('alt') == '+') { $(this).data('alt', '-').empty().append('Remove'); }
    else { $(this).data('alt', '+').empty().append('add'); }
});

$(document).on('click', 'div.details-control', function () {
    $('div.details-control').removeClass('active');
    $(this).addClass('active');
    $(".continue_btn").removeClass('btn-success').attr('disabled', 'disabled');
    $("#po_header_id").val($(this).attr('header-id'));
    format($(this).attr('header-id'));
});

$(document).on("click", "#ckbCheckAll", function () {
    if (this.checked) {
        $('.checkBoxClass').each(function (key, val) {
            if ($("#qty" + $(val).data("id")).val() == 0) { $("#qty" + $(val).data("id")).val($(val).data("pendingqty")); }
            this.checked = true;
            $("#select" + $(this).data("id")).trigger("change");
        });
    } else {
        $('.checkBoxClass').each(function () {
            $("#qty" + $(this).data("id")).val('0');
            this.checked = false;
            $("#select" + $(this).data("id")).trigger("change");
        });
    }
    if ($('.checkBoxClass:checked').length) { $(".continue_btn").addClass('btn-success').removeAttr('disabled').removeClass('btn-secondary'); }
    else { $(".continue_btn").addClass('btn-secondary ').removeClass('btn-success').attr('disabled', 'disabled'); }
});

$(document).on("change", ".checkBoxClass", function () {
    if ($(this).is(':checked')) { if ($("#qty" + $(this).data("id")).val() == "0" || $("#qty" + $(this).data("id")).val() == "") { $("#qty" + $(this).data("id")).val($(this).data("pendingqty")); } }
    else { $("#qty" + $(this).data("id")).val(''); }
});

$(document).on("change", ".checkBoxClass", function () {
    if ($('.checkBoxClass:checked').length == $('.checkBoxClass').length) { $('#ckbCheckAll').prop('checked', true); }
    else { $('#ckbCheckAll').prop('checked', false); }
    if ($('.checkBoxClass:checked').length) { $(".continue_btn").addClass('btn-success').removeAttr('disabled').removeClass('btn-secondary'); }
    else { $(".continue_btn").addClass('btn-secondary ').removeClass('btn-success').attr('disabled', 'disabled'); }
});

$(document).on("change focusout", ".check_qty", function () { if ($(this).val() > $(this).data('max')) { $(this).val($(this).data('max')) }});

$('.search-box').on('keypress', function (event) {
    if (event.which === 13) {
        var searchName = $(this).closest('.search-bar').find('.search-box').val();
        $(".right-side tbody:first").empty().hide().append(`<tr><td colspan="6" class="text-center"><p>No data found !</p></td></tr>`);
        poform(searchName);
        return false;
    }
});

$('.search-box').on('keydown', function (event) {
    if (event.which === 8) {
        // Check if Backspace key is pressed 
        var searchName = $(this).closest('.search-bar').find('.search-box').val();
        if (searchName.length === 1) {
            $(".right-side tbody:first").empty().hide();
            poform(searchName);
        }
    }
});

function format(rowData) {
    $.ajax({
        type: "GET",
        url: get_po_data + "/" + rowData,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        async: false,
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status == 1) {
                $(".card-header").html(`
                <table class="table table-bordered material-list">
                    <thead>
                        <tr>   
                        <th>Sap Vendor Code</th>
                        <th>Po No</th>
                        <th>Document Type</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>`+ response.data[0].sap_vendor_code + `</td>
                        <td>`+ response.data[0].po_no + `</td>
                        <td>`+ response.data[0].document_type + `</td>
                        <td>`+ response.data[0].created_by + `</td>
                        <td>`+ response.data[0].created_on + `</td>
                    </tr></tbody>
                </table>`);

                var tbody = ``;
                $.each(response.data, function (key, val) {
                    tbody += `<tr>
                        <td><input type="checkbox" name="footer_id[]" value="`+ val['PoFooters'].id + `" style="max-width: 20px;" class="form-control form-control-sm checkBoxClass"  data-pendingqty="` + val.actual_qty + `" data-id="` + val['PoItemSchedules'].id + `"></td>
                     <td>`+ val['PoFooters'].item + `</td>
                     <td>`+ val['PoFooters'].material + `</td>
                     <td>` + val.delivery_date + `</td>
                     <td>`+ val['PoFooters'].short_text + `</td>
                     <td>`+ val.actual_qty + ` ` + val['PoFooters'].order_unit + `</td>
                     <td><input type="number" name="" class="form-control form-control-sm check_qty" data-max="` + val.maxqty + `" max="` + val.maxqty + `" required="required" data-item="` + val['PoFooters'].item + `" id="qty` + val['PoItemSchedules'].id + `" value="0"></td>
                    </tr>`;
                });
                var thead = `<table class="table table-bordered material-list" id="example2">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="form-control form-control-sm" style="max-width: 20px;" id="ckbCheckAll">
                        </th>
                        <th>Item</th>
                        <th>Material</th>
                        <th>Delivery Date</th>
                        <th>Short Text</th>
                        <th>Pending Qty</th>
                        <th>Set Delivery Qty</th>
                    </tr>
                </thead>
                <tbody>`+ tbody + `</tbody>
                </table>`;
                $(".right-side").html(thead);
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
}

function poform(search = "", createAsn = "as") {
    $("#poItemss").empty();
    $(".right-side tbody:first").show();
    if (search != "") { get_po_for_asn += "/" + search }
    $.ajax({
        type: "GET",
        url: get_po_for_asn,
        dataType: 'json',
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status) {
                $.each(response.data, function (key, val) {
                    $("#poItemss").append(`<div class="po-box details-control  ponum" header-id="` + val.id + `">
                                            <p class="po-no mb-0">PO No.</p>
                                            <b class="text-info">` + val.po_no + `</b>
                                        </div>`);
                });
                $('div.details-control:first').click();
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
}


$(document).ready(function () {
    poform();
    $('div.details-control:first').click();
});
