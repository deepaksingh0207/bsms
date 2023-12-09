var dtable, stable;

$(".chosen").multiselect({
    enableClickableOptGroups: false,
    enableCollapsibleOptGroups: false,
    enableFiltering: true,
    includeSelectAllOption: false,
    buttonText: function (options, select) {
        if (options.length === 0) {
            return 'Select';
        }
        else if (options.length > 1) { return options.length + 'Filter'; }
        else {
            var labels = [];
            options.each(function () {
                if ($(this).attr('label') !== undefined) { labels.push($(this).attr('label')); }
                else { labels.push($(this).html()); }
            });
            return labels.join(', ') + '';
        }
    }
});

$(function () {
    dtable = $("#example1").DataTable({
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "destroy": true,
        dom: 'Blfrtip',
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export' }]
    });

    stable = $("#example1").DataTable({
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "destroy": true,
        dom: 'Blfrtip',
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export' }]
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
                url: materiallist_url,
                data: $("#addvendorform").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    if (response.status) {
                        dtable.clear().draw();
                        dtable.rows.add(response.data[0]).draw();
                        dtable.columns.adjust().draw();

                        // stable.clear().draw();
                        // stable.rows.add(response.data[1]).draw();
                        // stable.columns.adjust().draw();
                    } else { dtable.clear().draw(); stable.clear().draw(); }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        },
    });

    $("#id_sub").trigger("click");
});