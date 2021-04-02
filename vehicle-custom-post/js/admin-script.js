jQuery(document).ready(function(){
    jQuery('#booking_status').on('change', function(){
        var booking_id = jQuery(this).data('id');
        var status = jQuery(this).val();
        console.log(booking_id + '' + status);
        jQuery.ajax({
            type: "POST",
            url: backend_ajax_object.ajaxurl,
            data: {
                action: 'change_booking_status',
                "booking_id": booking_id,
                "status": status,
            },
            dataType: 'json',
            success: function (data) {
                if(data.success){
                    location.reload()();
                }
            }
        });
    });
});
