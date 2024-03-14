$('.segment').select2({
    closeOnSelect: false,
    placeholder: 'Segment Filter',
    allowClear: true,
    tags: false,
    tokenSeparators: [','],
    templateSelection: function (selection) {
        if (selection.element && $(selection.element).attr('data-select') !== undefined) {
            return $(selection.element).attr('data-select');
        } else {
            return selection.text;
        }
    }
});

$('.types').select2({
    closeOnSelect: false,
    placeholder: 'Type Filter',
    allowClear: true,
    tags: false,
    tokenSeparators: [','],
    templateSelection: function (selection) {
        if (selection.element && $(selection.element).attr('data-select') !== undefined) {
            return $(selection.element).attr('data-select');
        } else {
            return selection.text;
        }
    }
});

$('.vendor').select2({
    closeOnSelect: false,
    placeholder: 'Vendor Filter',
    allowClear: true,
    tags: false,
    tokenSeparators: [','],
    templateSelection: function (selection) {
        if (selection.element && $(selection.element).attr('data-select') !== undefined) {
            return $(selection.element).attr('data-select');
        } else {
            return selection.text;
        }
    }
});

$(document).on("click", ".mydash", function () {
    $.ajax({
        type: "POST",
        url: dashboard_url,
        data: $("#addvendorform").serialize(),
        dataType: "json",
        beforeSend: function () { console.log($("#addvendorform").serialize()); $("#gif_loader").show(); },
        success: function (r) {
            var data = r['data'];
            console.log(data);
            series.data.setAll(data[0]);
            series2.data.setAll(data[1]);
            
            tmp = [];
            t_data = data[2];
            for (var i = 0; i < t_data.length; i++) {
                tmp.push({"network":t_data[i]['network'], "value": parseFloat(t_data[i]['value'])})
            }
            yAxis3.data.setAll(tmp);
            series3.data.setAll(tmp);

            tmp = [];
            t_data = data[3];
            for (var i = 0; i < t_data.length; i++) {
                tmp.push({"country":t_data[i]['country'], "value": parseFloat(t_data[i]['value'])})
            }
            yAxis4.data.setAll(tmp);
            series4.data.setAll(tmp);

            series5.data.setAll(data[1]);
        },
        complete: function () { $("#gif_loader").hide(); }
    });
});
