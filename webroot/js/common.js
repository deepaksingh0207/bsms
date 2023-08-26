function getRemote(remote_url, method = "GET", request_data = null, response_type = "json", convertapi = true, process_data = false, content_type = 'application/x-www-form-urlencoded; charset=UTF-8') {
    var resp = $.ajax({ type: method, data: request_data, dataType: response_type, url: remote_url, async: false, processData: process_data, contentType: content_type }).responseText;
    if (convertapi) { return JSON.parse(resp); }
    return resp;
}


$(function () {
    $("#product").change(function () {
        var productId = $(this).val();
        if (productId != "") {
            $.ajax({
                type: "get",
                url: baseUrl + "productsubcategories/getlist/" + productId,
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function (response) {
                    if (response) {
                        $("#product_sub_category_id")
                            .find("option")
                            .remove()
                            .end()
                            .append('<option value="">Select</option>');
                        $.each(response, function (index, value) {
                            $("#product_sub_category_id").append(
                                '<option value="' +
                                    value.id +
                                    '">' +
                                    value.name +
                                    "</option>"
                            );
                        });
                    }
                },
                error: function (e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
            });
        }
    });


    $.ajax({
        url: "http://localhost/bsms/api/notification",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.notifications.length > 0) {
                $('.navbar-badge').text(response.notifications.length);

                $('.notification-list .notifyView').text(response.notifications.length + ' Notifications');
    
                $('.notification-lists').empty();
    
                $.each(response.notifications, function (index, notification) {
                    var notificationItem = $('<span class="dropdown-item notificationId"></span>');
    
                    var icon = $('<i class="fas fa-envelope text-info mr-2"></i>');

                    notificationItem.attr('data-id', notification.id);
                    notificationItem.append(icon);

                    if(notification.notification_type == "asn_material"){

                        var message = $('<span class="notificationTittle"></span>').text(notification.message_count + ' Asn Material');
                      
                     
                    }else if(notification.notification_type == "create_schedule"){
                        var message = $('<span class="notificationTittle"></span>').text(notification.message_count + ' Create Schedule');
                    
                    }else if(notification.notification_type == "po_acknowledge"){
                        var message = $('<span class="notificationTittle"></span>').text(notification.message_count + ' PO Acknowledge');
                    }else if(notification.notification_type == "vendor_material"){
                    var message = $('<span class="notificationTittle"></span>').text(notification.message_count + ' Vendor Material');
                }else if(notification.notification_type == "production_line"){
                    var message = $('<span class="notificationTittle"></span>').text(notification.message_count + ' Production Line');
                }

                     message.attr('data-class', notification.notification_type);
                
                    var clearButton = $('<span class="clearNotifications float-right" style="color:#004d87">Clear</span>');
                    clearButton.attr('id', notification.id);
                    notificationItem.append(message);
                    notificationItem.append(clearButton);
        
                    var divider = $('<div class="dropdown-divider"></div>');  
                    $('.notification-lists').append(notificationItem, divider);
                });
            } else {      
                $('.navbar-badge').text('0');
                $('.notification-list').html('<span class="dropdown-header">No Notifications</span>');
                
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
    
});
