var global_poid, schexp_dtbl, nonschexp_dtbl, dd_data;
var unload_dd = true;
$('#OpenImgUpload').click(function () { $('#bulk_file').trigger('click'); });

$('#bulk_file').change(function () {
    var file = $(this).prop('files')[0];
    var fileName = file ? file.name : '';
    $('#OpenImgUpload').text(fileName ? fileName : 'Choose File');
});

$("#id_import").click(function () {
    var fd = new FormData($('#formUpload')[0]);

    $.ajax({
        url: po_upload_url,
        type: "post",
        dataType: 'json',
        processData: false, // important
        contentType: false, // important
        data: fd,
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status) {
                Toast.fire({ icon: 'success', title: response.message });
                $("#uploadInfoTable tbody").empty();
                $("#upload_info").modal("toggle");

                // Loop through the response data and build the table rows dynamically
                $.each(response.data, function (key, val) {
                    var rowHtml = `<tr>
                        <td> `+ val.sap_vendor_code + `</td>
                        <td> `+ val.po_no + `</td>
                        <td> `+ val.item_no + `</td>
                        <td> `+ val.material + `</td>
                        <td> `+ val.schedule_qty + `</td>
                        <td> `+ val.delivery_date + `</td>
                        <td> `+ val.error + `</td>
                        </tr>`;
                    $("#uploadInfoTable tbody").append(rowHtml);
                });

            } else { Toast.fire({ icon: 'error', title: response.message }); }
        },
        error: function () { Toast.fire({ icon: 'error', title: 'An error occured, please try again.' }); },
        complete: function () { $("#gif_loader").hide(); }
    });
});

function searchPo(search = "") {
    $("#poItemss").html('');
    $(".related tbody:first").show();
    $.ajax({
        type: "GET",
        url: po_api + "/" + search,
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status == "success") {
                $.each(response.message, function (key, val) {
                    var vendor_tmp = val['V'].name
                    if ((vendor_tmp).length > 22) { vendor_tmp = vendor_tmp.substring(0, (vendor_tmp).length - ((vendor_tmp).length - 22)) + '...'; }
                    $("#poItemss").append(
                        `<div class="po-box details-control" data-id="` + val.id + `">
                            <div class="pono" style="display: flex; align-items: center;">
                                <small class="mb-0"> PO No:   </small><br>
                                <small class="pl-1"><b>` + val.po_no + `</b></small>
                            </div>
                            <div class="po-code po-code-block" style="display: flex; align-items: center;">
                                <small class="mb-0"> Vendor Code: </small><br>
                                <small class="pl-1"><b>` + val.sap_vendor_code + `</b></small>
                            </div>
                            <div class="po-code">
                                <small>` + vendor_tmp + `</small>
                            </div>
                        </div>`
                    );
                });
                $("div.po-box:first").click();
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
}


function poform(search = "") {
    $("#poItemss").html('');
    $(".related tbody:first").show();
    if (search != "") {
        po_api += "/" + search;
    }
    $.ajax({
        type: "GET",
        url: po_api,
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status == "success") {
                $.each(response.message, function (key, val) {
                    var vendor_tmp = val['V'].name
                    if ((vendor_tmp).length > 22) { vendor_tmp = vendor_tmp.substring(0, (vendor_tmp).length - ((vendor_tmp).length - 22)) + '...'; }
                    $("#poItemss").append(
                        `<div class="po-box details-control" data-id="` + val.id + `">
                            <div class="pono" style="display: flex; align-items: center;">
                                <small class="mb-0"> PO No:   </small><br>
                                <small class="pl-1"><b>` + val.po_no + `</b></small>
                            </div>
                            <div class="po-code po-code-block" style="display: flex; align-items: center;">
                                <small class="mb-0"> Vendor Code: </small><br>
                                <small class="pl-1"><b>` + val.sap_vendor_code + `</b></small>
                            </div>
                            <div class="po-code">
                                <small>` + vendor_tmp + `</small>
                            </div>
                        </div>`
                    );
                });
                $("div.po-box:first").click();
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
}

poform();

function getRemote(remote_url, method = "GET", type = "json", convertapi = true) {
    var resp = $.ajax({ type: method, dataType: type, url: remote_url, async: false }).responseText;
    if (convertapi) { return JSON.parse(resp); }
    return resp;
}

$(".btnSub").on("click", function (e) { e.preventDefault(); });

$("#expme").hide();

nonschexp_dtbl = $("#expandedTable").DataTable({
    searching: false,
    paging: true,
    dom: 'Bfrtip',
    buttons: [{
        extend: 'excel',
        text: 'Export to Excel',
        attr: { id: 'memebt' },
        filename: 'Non_Scheduled_PO_Items'
    }]
});

schexp_dtbl = $("#meme").DataTable({
    searching: false,
    paging: false,
    dom: 'Bfrtip',
    buttons: [{
        extend: 'excel',
        text: 'Export to Excel',
        attr: { id: 'memebtn' },
        filename: 'PO_Items_Status'
    }]
});

$("#purViewId").on("click", ".po-box", function () {
    $("#id_pofooter").empty();
    $(".po-box").removeClass("active");
    $(this).addClass("active");
    var poid = $(this).attr("data-id");
    global_poid = poid;
    $.ajax({
        type: "GET",
        url: get_po_Footers + poid,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        beforeSend: function () { $("#loaderss").show(); },
        success: function (response) {
            $("#id_potableresp").empty().hide()
                .append(`<table class="table" id="example1">
                            <thead>
                                <tr>
                                <th width="2%"></th>
                                <th width="2%"><input type="checkbox" class="form-control checkall"></th>
                                <th>Item</th>
                                <th>Material</th>
                                <th>Short Text</th>
                                <th>Po Qty</th>
                                <th>Grn Qty</th>
                                <th>Pending Qty</th>
                                <th>Order Unit</th>
                                <th>Net Price</th>
                                <th>Price Unit</th>
                                <th>Net Value</th>
                                <th>Gross Value</th>
                                <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="id_pofooter"></tbody>
                        </table>`);

            // console.log(response.status + "=" + response.data);
            if ((response.status || !response.status) && response.data) {
                populateItemData(response.status, response.data);
                $(".schexp").show();
                if (!response.status) {
                    $("#action_schedule").hide();
                    $("#res_message").html(response.message);
                    $(".schexp").hide();
                }

            } else {
                $("#action_schedule").hide();
                $("#res_message").html(response.message);
            }
        },
        complete: function () { $("#loaderss").hide(); },
    });

    $.ajax({
        type: "GET",
        url: po_schedule_export + global_poid,
        data: $("#addvendorform").serialize(),
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status) {
                schexp_dtbl.clear().draw();
                schexp_dtbl.rows.add(response.data).draw();
                schexp_dtbl.columns.adjust().draw();
            } else { schexp_dtbl.clear().draw(); }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});


function tableToExcel() {
    $("#memebtn").trigger("click");
    // var location = 'data:application/vnd.ms-excel;base64,';
    // var excelTemplate = '<html> '+
    //     '<head> '+
    //     '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> '+
    //     '</head> '+
    //     '<body> '+
    //     document.getElementById("expme").innerHTML +
    //     '</body> '+
    //     '</html>'
    // window.location.href = location + window.btoa(excelTemplate);
}


function populateItemData(status, itemData) {
    $.each(itemData.po_footers, function (key, val) {
        $("#action_schedule").show();
        $("#res_message").html('');
        $("#id_pofooter").append(
            `<tr class="odd" data-trid="id_tr` + val.id + `">
            <td class="details-control" data-id="` + val.id + `" footer-id="` + val.id + `">
                <span class="material-symbols-outlined flu" id="id_st` + val.id + `" data-id="` + val.id + `" data-alt="+">add</span>
            </td>
            <td><input type="checkbox" class="form-control check" id="check_` + val.id + `"></td>
            <td>` + val.item + `</td>
            <td>` + val.material + `</td>
            <td>` + val.short_text + `</td>
            <td class="poqtyvalu">` + val.po_qty + `</td>
            <td>` + val.grn_qty + `</td>
            <td>` + val.pending_qty + `</td>
            <td>` + val.order_unit + `</td>
            <td>` + val.net_price + `</td>
            <td>` + val.price_unit + `</td>
            <td>` + val.net_value + `</td>
            <td>` + val.gross_value + `</td>
            <td class="actions">
                <div class="btn-group">
                    <a id="schedulebutton_` + val.id + `" class="schedule_item btn btn-sm bg-gradient-button" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#scheduleModal" item-id="` + val.item + `" po-no="` + itemData.po_no + `" po_qty="` + val.po_qty + `" header-id="` + val.po_header_id + `" footer-id="` + val.id + `" item-no=` + val.item + `>Schedule</a>
                </div>
            </td>
        </tr>`
        );
    });

    if (!status) { $(".schedule_item, .check, .checkall, .flu").hide(); }

    setTimeout(function () {
        $("#id_potableresp").show();
        $("#example1").DataTable({
            paging: true,
            responsive: false,
            lengthChange: false,
            autoWidth: false,
            ordering: true,
            searching: true,
            destroy: true,
            initComplete: function () {
                var searchInput = $('.dataTables_filter input');
                searchInput.attr('placeholder', 'Search material in PO');
            }
        });
    }, 500);
}

var rowId = "";
$(document).on("click", ".schedule_item", function () {

    $(".alert-body").addClass("d-none");
    $(".a-data").removeClass("d-none");
    $("#btnClose").addClass("d-none");
    $(".btnSub").removeClass("d-none");
    $(".dismiss-btn").show();
    $(".btn-success").addClass("d-none");

    $("#error_msg").html("");
    var id = $(this).attr("footer-id");
    rowId = "id_st" + id;
    $(".check").prop("checked", false);
    if (!$("#check_" + id).is(":checked")) {
        $("#check_" + id).prop("checked", true);
    }
    prepare();
});

$(document).on("click", ".check", function () {
    var check_cnt = (uncheck_cnt = 0);
    $(".check").each(function (key, val) {
        if ($(val).is(":checked")) {
            check_cnt++;
            $("#action_schedule").attr("disabled", false);
        } else {
            uncheck_cnt++;
        }
    });
    if (check_cnt == 0) {
        $(".checkall").prop("checked", false);
        $("#action_schedule").attr("disabled", true);
    }
    if (uncheck_cnt == 0) {
        $(".checkall").prop("checked", true);
        $("#action_schedule").attr("disabled", false);
    }
});

$(document).on("click", ".checkall", function () {
    var checkall = $(this).is(":checked");
    if (checkall) {
        $("#action_schedule").attr("disabled", false);
    } else {
        $("#action_schedule").attr("disabled", true);
    }
    $(".check").each(function (key, val) {
        $(val).prop("checked", checkall);
    });
});

function prepare() {
    var count = 0;
    var today = new Date().toISOString().slice(0, 10);
    $("#id_scheduletbl").empty();
    $(".schedule_item").each(function (key, val) {
        var id = $(val).attr("footer-id");
        if ($("#check_" + id).is(":checked")) {
            var hid = $(val).attr("header-id");
            var fid = $(val).attr("footer-id");
            var iid = $(val).attr("item-no");
            var pid = $(val).attr("po-no");
            var pqty = $(val).attr("po_qty");
            response = getRemote(get_schedules + fid);
            max = pqty - response.totalQty;
            $("#id_scheduletbl").append(`<tr>
                <td><input type="hidden" name="`+ count + `[po_header_id]" value="` + hid + `">` + pid + `</td>
                <td><input type="hidden" name="`+ count + `[po_footer_id]" value="` + fid + `">` + iid + `</td>
                <td><input type="number" name="`+ count + `[actual_qty]" class="form-control act_qty" placeholder="Max ` + max + `" max="` + max + `" value=""></td>
                <td><input type="date" name="`+ count + `[delivery_date]" class="form-control dly_dt"  min="` + today + `" value=""></td>
            </tr>`);
            count++;
        }
    });
}

$("#scheduleModal").on("hidden.bs.modal", function (e) {
    $("#scheduleModal form")[0].reset();
});

$("#actual_qty").keyup(function () {
    var totalvalueqty = parseInt($(".poqtyvalu").text());
    var totallistqty = parseInt($(".actualTotalValue").text());
    var actualTotalqty = totalvalueqty - totallistqty;
    var userqtyvalues = parseInt($("#actual_qty").val());
    if (actualTotalqty >= userqtyvalues) {
        $("#scheduleForm").valid();
    }
});

$(document).on("click", ".notify_item", function () {
    $("#notifyModal").modal("show");
    $("#schedule_id").val($(this).attr("schedue-id"));
    $.ajax({
        type: "GET",
        url: get_schedule_messages + $(this).attr("schedue-id"),
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status == "success") {
                $("#past_messages").html(response.html);
            }
            $(".overlay").hide();
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});


$(".search-box").on("keyup", function (event) {
    //if (event.which === 13) {
    var searchName = $(this).val();
    $(".related tbody:first").empty().hide().append(`<tr>
                <td colspan="13" class="text-center">
                    <p>No data found !</p>
                </td>
            </tr>`);
    searchPo(searchName);
    //return false;
    //}
});


/*
$(".search-box").on("keydown", function (event) {
    if (event.which === 8) {
        var searchName = $(this).val();
        if (searchName.length === 1) {
            $(".related tbody:first").empty().hide();
            poform(searchName);
        }
    }
});
*/

$(document).on("click", ".flu", function () {
    $("#error_msg").html("");
    var id = $(this).attr("data-id");
    var po_no = $("#schedulebutton_" + id).attr("po-no");
    var item_no = $("#schedulebutton_" + id).attr("item-no");
    rowId = "id_st" + id;

    var response = "";
    if ($(this).data("alt") == "+") {
        $(this).data("alt", "-").empty().append("Remove");
        response = getRemote(get_schedules + $(this).data("id"));
        var currTR = this.parentNode.parentNode;
        var newTR = document.createElement("tr");
        newTR.setAttribute("id", "id_subtr" + $(this).data("id"));
        if (response.totalQty) {
            $(".actualTotalValue").text(response.totalQty);
        }
        if (response.status == 1) {
            var subtable = `<td colspan="6" style="background-color:white !important;">
                                <table class="table mb-0" id="example2">
                                <thead>
                                        <tr>
                                            <th>Schedule Qty</th>
                                            <th>ASN Qty</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                </thead>
                                <tbody>`;
            $.each(response.message, function (key, val) {
                // if (val.status == 1) {
                var currentDate = new Date();
                var deliveryDate = new Date(val.delivery_date);

                var dt = val.delivery_date.split('-');
                delDt = dt[2] + "-" + dt[1] + "-" + dt[0];

                var updateButton = val.received_qty > 0 ? '' : `<span class="badge schedule_update_button lbluebadge mt-2 ml-2" data-toggle="tooltip" data-placement="right" schedue-id='` + val.id + `' delivery_date='` + delDt + `' actual_qty='` + val.actual_qty + `' data-po='` + po_no + `' data-item='` + item_no + `' data-actual-qty='` + val.actual_qty + `' data-target='#modal-sm'  title="Modify" data-original-title="Modify"><i class="fas fa-user-edit"></i></span>`;

                // var status = val.received_qty > 0 ? 'Received' : 'Schedule'
                var deliveryDate = new Date(currentDate);
                var cancelButton = val.received_qty > 0 || currentDate.setHours(0, 0, 0, 0) > deliveryDate.setHours(0, 0, 0, 0) ? '' : `<span class="badge redbadge schedule_cancel_button mt-2 ml-2" data-toggle="tooltip" data-placement="right" schedue-id='` + val.id + `' delivery_date='` + val.delivery_date + `' actual_qty='` + val.actual_qty + `' data-po='` + po_no + `' data-item='` + item_no + `' data-actual-qty='` + val.actual_qty + `'  title="Cancel" data-original-title="Cancel"><i class="fas fa-trash"></i></span>`;
                subtable += `<tr>
                            <td>`+ val.actual_qty + `</td>
                            <td>`+ val.received_qty + `</td>
                            <td>`+ val.delivery_date + `</td>
                            <td>`+ val.status + `</td>
                            <td><!-- <span class="badge  mt-2 dbluebadge notify_item" schedue-id='`+ val.id + `' ata-toggle='modal' data-target='#notifyModal' data-toggle="tooltip" data-placement="right" title="Notify"><i class="fas fa-comments"></i></span> -->` + updateButton + cancelButton + `</td>
                        </tr>`;
                // }
            });
            newTR.innerHTML = subtable;
        } else {
            newTR.innerHTML =
                `<td colspan="6">` +
                response.message +
                `</td><td colspan="7"></td>`;
        }
        currTR.parentNode.insertBefore(newTR, currTR.nextSibling);
    } else {
        $(this).data("alt", "+").empty().append("add");
        $("#id_subtr" + $(this).data("id")).remove();
    }
});

$(document).ready(function () {
    var summernoteForm = $(".form-validate-summernote");
    var summernoteElement = $(".summernote");
    var summernoteValidator = summernoteForm.validate({
        errorElement: "div",
        errorClass: "is-invalid",
        validClass: "is-valid",
        ignore: ":hidden:not(.summernote),.note-editable.card-block",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            console.log(element);
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.siblings("label"));
            } else if (element.hasClass("summernote")) {
                error.insertAfter(element.siblings(".note-editor"));
            } else {
                error.insertAfter(element);
            }
        },
    });

    summernoteElement.summernote({
        height: 150,
        callbacks: {
            onChange: function (contents, $editable) {
                summernoteElement.val(
                    summernoteElement.summernote("isEmpty") ? "" : contents
                );
                summernoteValidator.element(summernoteElement);
            },
        },
    });

    $.validator.setDefaults({
        submitHandler: function () {
            var formdatas = new FormData($("#scheduleForm")[0]);
            $.ajax({
                type: "POST",
                url: create_schedule,
                data: $("#scheduleForm").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        Toast.fire({
                            icon: "success",
                            title: response.message,
                        });
                        $("#scheduleModal").modal("toggle");

                        $("#" + rowId).trigger("click");
                        $("#" + rowId).trigger("click");

                    } else {
                        $("#scheduleModal").modal("toggle");
                        Toast.fire({ icon: "error", title: response.message });
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
            return false;
        },
    });

    $("#scheduleForm").validate({
        rules: {
            actual_qty: {
                required: true,
                number: true,
                checkQty: true,
            },
            delivery_date: {
                required: true,
            },
        },
        messages: {
            actual_qty: {
                required: "Please provide a quantity",
                number: "Please enter a valid number",
                checkQty: "Do not exceed PO qty value",
            },
            delivery_date: {
                required: "Please select a date",
            },
        },

        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $.validator.addMethod(
        "checkQty",
        function (value, element) {
            var totalvalueqty = parseInt($(".poqtyvalu").text());
            var totallistqty = parseInt($(".actualTotalValue").text());
            var actualTotalqty = totalvalueqty - totallistqty;
            var userqtyvalues = parseInt($("#actual_qty").val());

            if (actualTotalqty >= userqtyvalues) {
                return true;
            }
            return false;
        },
        "message"
    );

    var table = $("#example2").DataTable({
        paging: true,
        responsive: false,
        lengthChange: false,
        autoWidth: false,
        searching: false,
    });

    $("#notifyForm").validate({
        ignore: ":not(.summernote),.note-editable.panel-body",
        rules: { message: { required: true } },
        messages: { message: { required: "Please enter remarks" } },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function () {
            var formdatas = new FormData($("#notifyForm")[0]);
            $.ajax({
                type: "POST",
                url: save_schedule_remarks,
                data: $("#notifyForm").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status == "success") {
                        $("#notifyModal").modal("toggle");
                        Toast.fire({
                            icon: "success",
                            title: response.message,
                        });
                    } else {
                        Toast.fire({ icon: "error", title: response.message });
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
            return false;
        },
    });
});

// =================================PurchaseOrder Update schedule ===============================

$(document).on("click", ".schedule_update_button", function () {
    var today = new Date().toISOString().slice(0, 10);
    $("#modal-sm").modal("show");
    var sid = $(this).attr("schedue-id");
    $(".schedule_button").attr("data-id", sid);

    var delivery_date = $(this).attr("delivery_date");
    $("#delivery_dates").val(delivery_date).attr('min', today);

    var po = $(this).attr("data-po");
    $("#schedule_po").text(po);

    var item_no = $(this).attr("data-item");
    $("#schedule_item").text(item_no);

    var actual_qty = $(this).attr("data-actual-qty");
    $("#schedule_actual_qty").text(actual_qty);
});

$(document).on("click", ".schedule_button", function () {
    if (
        $("#delivery_dates").val() == null ||
        $("#delivery_dates").val() == undefined ||
        $("#delivery_dates").val() == ""
    ) {
        $("#error_msg_update").text("Schedule Date Mandatory");
        $("#delivery_dates").focus();
    } else {
        var currentDate = new Date();
        var inputDate = new Date($("#delivery_dates").val());
        if (inputDate.setHours(0, 0, 0, 0) >= currentDate.setHours(0, 0, 0, 0)) {
            $.ajax({
                type: "POST",
                url: create_schedule_update + $(this).attr("data-id"),
                data: $("#scheduleUpdate").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status == "success") {
                        $("#modal-sm").modal("hide");
                        Toast.fire({ icon: "success", title: response.message });
                        $("#" + rowId).trigger("click");
                        $("#" + rowId).trigger("click");
                    } else {
                        Toast.fire({ icon: "error", title: response.message });
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        } else { Toast.fire({ icon: "error", title: 'Past Date Restricted' }); }
    }
    // return false;
});

$(document).on("click", ".schedule_cancel_button", function () {
    $("#modal-cancel").modal("show");
    var sid = $(this).attr("schedue-id");
    $(".schedule_cancel_ok").attr("data-id", sid);
});

$(".schedule_cancel_ok").click(function () {
    $.ajax({
        type: "GET",
        url: create_schedule_cancel + $(this).attr("data-id"),
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status == "success") {
                $("#modal-cancel").modal("hide");
                Toast.fire({ icon: "success", title: response.message });
                $("#" + rowId).trigger("click");
                $("#" + rowId).trigger("click");
            } else {
                Toast.fire({ icon: "error", title: response.message });
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});

$(".btnSub").click(function (event) {
    var status = true;

    // jthayil
    $(".act_qty").each(function (key, obj) {

        console.log("QTY=" + $(obj).attr("max") + "=" + $(obj).val());
        if (
            $(obj).val() == "" ||
            $(obj).val() == null ||
            $(obj).val() == undefined
        ) {
            $("#error_msg").text("Actual Qty Mandatory");
            status = false;
            $(obj).focus();
        } else if (parseFloat($(obj).attr("max")) < parseFloat($(obj).val())) {
            $("#error_msg").text("Actual Qty exceeds pending max PO Qty");
            status = false;
            $(obj).focus();
        }
    });

    if (status) {
        $(".dly_dt").each(function (key, obj) {
            //var inputDate = new Date($(obj).val()).toLocaleDateString(undefined, { year: 'numeric', month: 'numeric', day: 'numeric' });
            //var currentDate = new Date().toLocaleDateString(undefined, { year: 'numeric', month: 'numeric', day: 'numeric' });
            var inputDate = new Date($(obj).val());
            var currentDate = new Date();
            if (
                $(obj).val() == null ||
                $(obj).val() == undefined ||
                $(obj).val() == ""
            ) {
                $("#error_msg").text("Schedule Date Mandatory");
                status = false;
                $(obj).focus();
            }
            if (inputDate.setHours(0, 0, 0, 0) < currentDate.setHours(0, 0, 0, 0)) {
                $("#error_msg").text("Past Schedule Restricted");
                status = false;
                $(obj).focus();
            }
        });
    }

    // Display alert body and OK button
    if (status) {
        $(".a-data").addClass("d-none");
        $(".u-data").addClass("d-block");
        $(".alert-body").removeClass("d-none");
        $(".btnSub").addClass("d-none");
        $("#btnClose").removeClass("d-none");
        $(".dismiss-btn").hide();
        $(".btn-success").removeClass("d-none");
    }
});

$("#btnClose").click(function () {
    $(".a-data").addClass("d-block");
    $(".dismiss-btn").show();
    $(".btnSub").addClass("d-block");
    $(".alert-body").hide();
    $(".btn-success").hide();
    $("#btnClose").hide();
    $("#btnClose").removeClass("d-none");
});

document.getElementById("expandButton").addEventListener("click", function () {
    var table = document.getElementById("expandedTable");
    if (table.style.display === "none") {
        table.style.display = "table";
        document.getElementById("expandButton").innerText = "Collapse";
    } else {
        table.style.display = "none";
        document.getElementById("expandButton").innerText = "Expand";
    }
});

$(document).on("click", "#expandButton", function () {
    $("#expanded_tbl, .searchy").toggle();
});

$(document).on("change", "#id_sap_vendor_code, #id_material, #id_po_no", function () {
    $.ajax({
        type: "POST",
        url: non_schedule_po_export,
        data: {sap_vendor_code:$('#id_sap_vendor_code').val(),material:$('#id_material').val(),po_no:$('#id_po_no').val(),},
        headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') },
        dataType: "json",
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (response) {
            if (response.status) {
                dd_data = response.data;
                nonschexp_dtbl.clear().draw();
                nonschexp_dtbl.rows.add(dd_data['records']).draw();
                nonschexp_dtbl.columns.adjust().draw();
            } else { nonschexp_dtbl.clear().draw(); }
            if (unload_dd) {
                $.each(dd_data['vendor'], function (key, value) {
                    $('#id_sap_vendor_code')
                        .append($("<option></option>")
                            .attr("value", key)
                            .text(key + " - " + value));
                });
                $.each(dd_data['material'], function (key, value) {
                    $('#id_material')
                        .append($("<option></option>")
                            .attr("value", key)
                            .text(key + " - " + value));
                });
                $.each(dd_data['po'], function (key, value) {
                    $('#id_po_no')
                        .append($("<option></option>")
                            .attr("value", value)
                            .text(value));
                });
                $('.chosen').select2({
                    closeOnSelect : false,
                    placeholder: 'Select',
                    allowClear: true,
                    tags: false,
                    tokenSeparators: [','],
                    templateSelection: function(selection) {
                        if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                            return $(selection.element).attr('data-select');
                        } else {
                            return selection.text;
                        }
                    }
                });
                unload_dd = false;
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});

$("#expandButton").trigger('click');
$("#id_sap_vendor_code").trigger('change');
