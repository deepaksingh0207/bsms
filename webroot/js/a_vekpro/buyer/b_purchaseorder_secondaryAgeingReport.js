var dtable, stable;

// $(".chosen").multiselect({
//     enableClickableOptGroups: false,
//     enableCollapsibleOptGroups: false,
//     enableFiltering: true,
//     includeSelectAllOption: false,
//     buttonText: function (options, select) {
//         if (options.length === 0) {
//             return 'Select';
//         }
//         // else if (options.length > 1) { return options.length + 'Filter'; }
//         else {
//             var labels = [];
//             options.each(function () {
//                 if ($(this).attr('data-select') !== undefined) { labels.push($(this).attr('data-select')); }
//                 else { labels.push($(this).html()); }
//             });
//             return labels.join(', ');
//         }
//     }
// });

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
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export', title:'' }]
    });

    d3table = $("#example3").DataTable({
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "destroy": true,
        dom: 'Blfrtip',
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export', title:'' }]
    });


    stable = $("#example2").DataTable({
        "paging": false,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": false,
        "ordering": false,
        "destroy": true,
        "createdRow": function(row, data, dataIndex) {
            if(data[0] == 'Grand Total'){
                $(row).attr("style","background-color: #F7941D !important; color: black;");
            } else if (data[1] == "" && data[2] == "" && data[3] == "" && data[4] == "") {
                $(row).attr("style","background-color:#F7941D !important;color:white;");
            }
            console.log(data);
        }
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

                        d3table.clear().draw();
                        d3table.rows.add(response.data[0]).draw();
                        d3table.columns.adjust().draw();

                        stable.clear().draw();
                        stable.rows.add(response.data[1]).draw();
                        stable.columns.adjust().draw();
                    } else { dtable.clear().draw(); stable.clear().draw(); }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        },
    });

    $("#id_sub").trigger("click");
});