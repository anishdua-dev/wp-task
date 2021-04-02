<div id="msg"></div>
<form name="booking_form" id="booking_form" method="post">
    <input type="hidden" name="action" value="send_booking_form_action" required>
    <input type="text" name="first_name" placeholder="First name" required>
    <input type="text" name="last_name" placeholder="Last name" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <select name="vehicle_type" id="vehicle_type">
        <option value="">--Please choose vehicle Type--</option>
    <?php foreach($vehicle_taxonomies as $vehicle_type){ ?>
        <option value="<?php echo $vehicle_type->term_id?>"><?php echo $vehicle_type->name; ?></option>
    <?php  } ?>
    </select>
    <select name="vehicle_name" id="vehicle_name">
    <option value="">--Please choose vehicle--</option>
    <?php foreach($all_vehicles as $vehicle){ ?>
        <option value="<?php echo $vehicle->ID?>"><?php echo $vehicle->post_title; ?></option>
    <?php  } ?>
    </select>
    <input type="text" name="vehicle_price" placeholder="starting price" readonly id="vehicle_price">
    <textarea name="message" placeholder="Message"></textarea>
    <input type="submit" name="Booking" id="booking_sbmt">
</form>