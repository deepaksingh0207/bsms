var dtable;

$('.chosen').select2({
    closeOnSelect: false,
    placeholder: 'Select',
    allowClear: true,
    tags: false,
    tokenSeparators: [',', ' '],
    templateSelection: function (selection) {
        if (selection.element && $(selection.element).attr('data-select') !== undefined) {
            return $(selection.element).attr('data-select');
        } else {
            return selection.text;
        }
    }
});

dtable = $("#example1").DataTable({
    "paging": true,
    "responsive": false,
    "lengthChange": false,
    "autoWidth": false,
    "searching": true,
    "ordering": true,
    "destroy": true,
    dom: 'Blfrtip',
    buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export', title:'' }],
});

$("#addvendorform").validate({
    rules: { vendor_code: { required: false, }, },
    messages: { vendor_code: { required: "Please enter a first name", }, },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) { $(element).addClass("is-invalid"); },
    unhighlight: function (element, errorClass, validClass) { $(element).removeClass("is-invalid"); },
    submitHandler: function (form) {
        $.ajax({
            type: "POST",
            url: url_list,
            data: $("#addvendorform").serialize(),
            dataType: "json",
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (response) {
                console.log(response);
                if (response.status) {
                    dtable.clear().draw();
                    dtable.rows.add(response.data).draw(); // Add new data
                    dtable.columns.adjust().draw();
                } else { dtable.clear().draw(); }
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    },
});

$(".confirm-input").keyup(function () {
    var id = $(this).attr('data-id');
    var val = parseFloat($(this).val().trim());
    var maxQty = parseFloat($("#plan_qty_" + id).val().trim());
    console.log(val + "=" + maxQty);
    if (val < 1) { $(this).val(""); }
});

$('#OpenImgUpload').click(function () { $('#bulk_file').trigger('click'); });

$('#bulk_file').change(function () {
    var file = $(this).prop('files')[0].name;
    $("#filessnames").append(file);
});

$("#id_exportme").click(function() {
    var fd = new FormData($('#formUpload')[0]);

    $.ajax({
        url: uploadConfirmedProductionUrl,
        type: "post",
        dataType: 'json',
        processData: false, // important
        contentType: false, // important
        data: fd,
        beforeSend: function () { $("#gif_loader").show(); },
        success: function(response) {
            if (response.status) {
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });

                $("#example1 tbody").empty();

                // Loop through the response data and build the table rows dynamically
                $.each(response.data, function (key, val) { 
                    var rowHtml = `<tr>
                    <td> `+ val.factory_code + `</td>
                    <td> `+ val.line + `</td>
                    <td> `+ val.material +`</td>
                    <td> `+ val.material_description +`</td>
                    <td> `+ val.plan_date + `</td>
                    <td> `+ val.target_production + `</td>
                    <td> `+ val.confirm_production + `</td>
                    <td> `+ val.error + `</td>
                    </tr>`;
                    $("#example1 tbody").append(rowHtml);
                });

            } else {
                Toast.fire({
                    icon: 'error',
                    title: response.message
                });
            }
        },
        error: function() {
            Toast.fire({
                icon: 'error',
                title: 'An error occured, please try again.'
            });
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});

$("#id_sub").trigger("click");