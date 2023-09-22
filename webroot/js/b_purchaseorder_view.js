function poform(search = "") {
    $("#poItemss").empty();
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
                    $("#poItemss").append(
                        `<div class="po-box details-control" data-id="` +
                        val.id +
                        `">
                                            <div class="pono">
                                                <small class="mb-0"> PO No </small><br>
                                                <b>` +
                        val.po_no +
                        `</b>
                                            </div>
                                            <div class="po-code">
                                                <small class="mb-0"> Vendor Code </small><br>
                                                <small><b>` +
                        val.sap_vendor_code +
                        `</b></small>
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

$(".btnSub").on("click", function (e) {
    e.preventDefault();
});

$("#purViewId").on("click", ".po-box", function () {
    $("#id_pofooter").empty();
    $(".po-box").removeClass("active");
    $(this).addClass("active");
    var poid = $(this).attr("data-id");

    $.ajax({
        type: "GET",
        url: get_po_Footers + poid,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            $("#loaderss").show();
        },
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

            console.log(response.status + "=" + response.data);
            if ((response.status || !response.status) && response.data) {
                populateItemData(response.status, response.data);
                if (!response.status) {
                    $("#action_schedule").hide();
                    $("#res_message").html(response.message);
                }

            } else {
                $("#action_schedule").hide();
                $("#res_message").html(response.message);
            }
        },
        complete: function () {
            $("#loaderss").hide();
        },
    });
});

function populateItemData(status, itemData) {
    $.each(itemData.po_footers, function (key, val) {
        $("#action_schedule").show();
        $("#res_message").html('');
        $("#id_pofooter").append(
            `<tr class="odd" data-trid="id_tr` +
            val.id +
            `">
        <td class="details-control" data-id="` +
            val.id +
            `" footer-id="` +
            val.id +
            `"><span class="material-symbols-outlined flu" id="id_st` +
            val.id +
            `" data-id="` +
            val.id +
            `" data-alt="+">add</span></td>
            <td><input type="checkbox" class="form-control check" id="check_` +
            val.id +
            `"></td>
            <td>` +
            val.item +
            `</td>
            <td>` +
            val.material +
            `</td>
            <td>` +
            val.short_text +
            `</td>
            <td class="poqtyvalu">` +
            val.po_qty +
            `</td>
            <td>` +
            val.grn_qty +
            `</td>
            <td>` +
            val.pending_qty +
            `</td>
            <td>` +
            val.order_unit +
            `</td>
            <td>` +
            val.net_price +
            `</td>
            <td>` +
            val.price_unit +
            `</td>
            <td>` +
            val.net_value +
            `</td>
            <td>` +
            val.gross_value +
            `</td>
            <td class="actions">
                <div class="btn-group">
                    <a id="schedulebutton_` +
            val.id +
            `" class="schedule_item btn btn-sm bg-gradient-button" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#scheduleModal" item-id="` +
            val.item +
            `" po-no="` +
            itemData.po_no +
            `" po_qty="` +
            val.po_qty +
            `" header-id="` +
            val.po_header_id +
            `" footer-id="` +
            val.id +
            `" item-no=` +
            val.item +
            `>Schedule</a>
                </div>
            </td>
        </tr>`
        );
    });

    if (!status) {
        $(".schedule_item, .check, .checkall, .flu").hide();
    }

    setTimeout(function () {
        $("#id_potableresp").show();
        $("#example1").DataTable({
            paging: true,
            responsive: false,
            lengthChange: false,
            autoWidth: false,
            ordering: false,
            searching: false,
        });
    }, 500);
}

$(document).on("click", ".schedule_item", function () {
    $("#btnClose").trigger('click');
    $("#error_msg").html("");
    var id = $(this).attr("footer-id");
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
                <td><input type="text" name="`+ count + `[actual_qty]" class="form-control act_qty" placeholder="Max ` + max + `" max="` + max + `" value=""></td>
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

$(".search-box").on("keypress", function (event) {
    if (event.which === 13) {
        var searchName = $(this)
            .closest(".search-bar")
            .find(".search-box")
            .val();
        $(".related tbody:first").empty().hide().append(`<tr>
                <td colspan="13" class="text-center">
                    <p>No data found !</p>
                </td>
            </tr>`);
        poform(searchName);
        return false;
    }
});

$(".search-box").on("keydown", function (event) {
    if (event.which === 8) {
        var searchName = $(this)
            .closest(".search-bar")
            .find(".search-box")
            .val();
        if (searchName.length === 1) {
            $(".related tbody:first").empty().hide();
            poform(searchName);
        }
    }
});

$(document).on("click", ".flu", function () {
    $("#error_msg").html("");
    var id = $(this).attr("data-id");
    var po_no = $("#schedulebutton_" + id).attr("po-no");
    var item_no = $("#schedulebutton_" + id).attr("item-no");

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
                                            <th>Actual Qty</th>
                                            <th>Received Qty</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                </thead>
                                <tbody>`;
            $.each(response.message, function (key, val) {
                if (val.status == 1) {
                    var currentDate = new Date().toISOString().split('T')[0];
                    var deliveryDate = val.delivery_date;


                    var updateButton = val.received_qty > 0 ? '' : `<span class="badge schedule_update_button lbluebadge mt-2 ml-2" data-toggle="tooltip" data-placement="right" schedue-id='` + val.id + `' delivery_date='` + val.delivery_date + `' actual_qty='` + val.actual_qty + `' data-po='` + po_no + `' data-item='` + item_no + `' data-actual-qty='` + val.actual_qty + `' data-target='#modal-sm'  title="Modify" data-original-title="Modify"><i class="fas fa-user-edit"></i></span>`;

                    var status = val.received_qty > 0 ? 'Received' : 'Schedule'

                    var cancelButton = val.received_qty > 0 || Date.parse(currentDate) > Date.parse(deliveryDate) ? '' : `<span class="badge redbadge schedule_cancel_button mt-2 ml-2" data-toggle="tooltip" data-placement="right" schedue-id='` + val.id + `' delivery_date='` + val.delivery_date + `' actual_qty='` + val.actual_qty + `' data-po='` + po_no + `' data-item='` + item_no + `' data-actual-qty='` + val.actual_qty + `'  title="Cancel" data-original-title="Cancel"><i class="fas fa-trash"></i></span>`;
                    subtable += `<tr>
                            <td>`+ val.actual_qty + `</td>
                            <td>`+ val.received_qty + `</td>
                            <td>`+ val.delivery_date + `</td>
                            <td>`+ status + `</td>
                            <td><span class="badge  mt-2 dbluebadge notify_item" schedue-id='`+ val.id + `' ata-toggle='modal' data-target='#notifyModal' data-toggle="tooltip" data-placement="right" title="Notify"><i class="fas fa-comments"></i></span>` + updateButton + cancelButton + `</td>
                        </tr>`;
                }
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
    $("#modal-sm").modal("show");
    var sid = $(this).attr("schedue-id");
    $(".schedule_button").attr("data-id", sid);

    var delivery_date = $(this).attr("delivery_date");
    $("#delivery_dates").val(delivery_date);

    var po = $(this).attr("data-po");
    $("#schedule_po").text(po);

    var item_no = $(this).attr("data-item");
    $("#schedule_item").text(item_no);

    var actual_qty = $(this).attr("data-actual-qty");
    $("#schedule_actual_qty").text(actual_qty);
});

$(document).on("click", ".schedule_button", function () {
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
            } else {
                Toast.fire({ icon: "error", title: response.message });
            }
        },
        complete: function () { $("#gif_loader").hide(); }
    });
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
            if (
                $(obj).val() == null ||
                $(obj).val() == undefined ||
                $(obj).val() == ""
            ) {
                $("#error_msg").text("Schedule Date Mandatory");
                status = false;
                $(obj).focus();
            }
        });
    }

    // Display alert body and OK button
    if (status) {
        $(".a-data, .btnSub, .dismiss-btn").hide();
        $("#btnClose").show();
        $(".alert-body, .btn-success").show();
    }
});

$("#btnClose").click(function () {
    $(".a-data").show();
    $(".dismiss-btn").show();
    $(".btnSub").show();
    $(".alert-body").hide();
    $(".btn-success").hide();
    $("#btnClose").hide();
});
