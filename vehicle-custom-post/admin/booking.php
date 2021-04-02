<div class="wrap">
<h1>Booking List</h1>
    <table class="wp-list-table widefat">
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
            <th class="manage-column column-id column-primary sortable">
                <a href=""><span>Booking ID</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="first_name" class="manage-column column-user_id sortable">
            <a href=""><span>First Name</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="last_name" class="manage-column column-user_id sortable ">
            <a href=""><span>Last Name</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="email" class="manage-column column-email sortable">
            <a href="javascript:void(0);"><span>Email</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="phone" class="manage-column column-product_url sortable">
            <a href=""><span>Phone</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="status" class="manage-column column-status sortable ">
            <a href=""><span>Vehicle Type</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="status" class="manage-column column-status sortable">
            <a href=""><span>Vehicle Name</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="status" class="manage-column column-status sortable">
            <a href=""><span>Vehicle Price</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="status" class="manage-column column-status sortable">
            <a href=""><span>Message</span><span class="sorting-indicator"></span></a></th>
            <th scope="col" id="status" class="manage-column column-status sortable">
            <a href=""><span>status</span><span class="sorting-indicator"></span></a></th>
        </tr>
    </thead>
    <tbody id="the-list">
    <?php
        if(!empty($resData)){ //echo '<pre>'; print_r($resData);
            foreach($resData as $data){    
        ?>
                <tr>
                    <th scope="row" class="check-column"><input type="checkbox" name="boolingids[]" value="<?php echo $data['id']; ?>"></th>
                    <td class="id column-req_id has-row-actions column-primary" data-colname="Booking ID"><?php echo $data['id']; ?>
                    </td>
                    <td class="first_name column-req_id has-row-actions column-primary" data-colname="First name"><?php echo $data['first_name']; ?>
                    </td>
                    <td class="last_name column-req_id has-row-actions column-primary" data-colname="Last name"><?php echo $data['last_name']; ?>
                    </td>
                    <td class="email column-req_id has-row-actions column-primary" data-colname="Email"><?php echo $data['email']; ?>
                    </td>
                    <td class="phone column-req_id has-row-actions column-primary" data-colname="Phone"><?php echo $data['phone']; ?>
                    </td>
                    <td class="type column-req_id has-row-actions column-primary" data-colname="Vehicle Type"><?php echo get_term( $data['vehicle_type'] )->name; ?>
                    </td>
                    <td class="name column-req_id has-row-actions column-primary" data-colname="Vehicle Name"><?php echo $data['post_name']; ?>
                    </td>
                    <td class="price column-req_id has-row-actions column-primary" data-colname="Vehicle Price"><?php echo $data['price']; ?>
                    <td class="msg column-req_id has-row-actions column-primary" data-colname="Message"><?php echo $data['message']; ?>
                    </td>
                    <td>
                        <select id="booking_status" data-id="<?php echo $data['id'] ?>">
                            <option value="0" <?php echo ($data['status'] == 0) ? 'selected' : '' ?>>Pending</option>
                            <option value="1" <?php echo ($data['status'] == 1) ? 'selected' : '' ?>>Approved</option>
                            <option value="2" <?php echo ($data['status'] == 2) ? 'selected' : '' ?>>Reject</option>
                            <option value="3" <?php echo ($data['status'] == 3) ? 'selected' : '' ?>>Complete</option>
                        </select>
                    </td>
                </tr>
        <?php
            }
        }
    ?>
    </tbody>
    </table>
</div>