jQuery(document).ready(function($){
    $('#vehicle_type').on('change', function(event){
        event.preventDefault();
        var vehicle_type = $(this).val();
        $.ajax({
            type: "POST",
            url: frontend_ajax_object.ajaxurl,
            data: {
                action: 'get_vechile_by_cat',
                "cat_id": vehicle_type,
            },
            dataType: 'json',
            success: function (data) {
            if(data.success){
                console.log(data);
                $('#vehicle_name').html();
                var html = '';
                html += '<option value="">--Please choose vehicle--</option>';
                for(var i = 0; i < data.data.length; i++){
                    html += '<option value="'+ data.data[i].ID +'">'+ data.data[i].post_name +'</option>';
                }
                $('#vehicle_name').html(html);
            }else{
                // Do the stuff
            }
          }
        });
    });

    $('#vehicle_name').on('change', function(event){
        event.preventDefault();
        var vehicle_id = $(this).val();
        $.ajax({
            type: "POST",
            url: frontend_ajax_object.ajaxurl,
            data: {
                action: 'get_vechile_price',
                "vehicle_id": vehicle_id,
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
            if(data.success){
                console.log(data);
                $('#vehicle_price').val(data.data);
            }else{
                // Do the stuff
            }
          }
        });
    });

    $('#booking_sbmt').click(function(event){
        event.preventDefault();
        var formdata = new FormData($('#booking_form')[0]);
        jQuery.ajax({
            type: "POST",
            url: frontend_ajax_object.ajaxurl,
            data: formdata,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
            if(data.success){
                console.log(data);
                $('#msg').html('<span>Your booking is done successfully with status pending.</span>');
                $('#booking_form')[0].reset();
            }else{
                // Do the stuff
            }
          }
        });
    });
});