<div id="hlgf-form-container">
    <h2>Lead Form</h2>
    <form action="#" id="hlgf-form" method="post">
        <input type="hidden" name="action" value="hlgf_ajax" />
        <input type="hidden" name="customer_info_nonce" id="hlgf-form-nonce" value="" />
        <div class="hlgf-form-input">
            <label for="name"><?php echo isset( $attr['name_label'] ) ? $attr['name_label'] : 'Name'; ?> <abbr>*</abbr></label>
            <input type="text" maxlength="<?php echo isset( $attr['name_max_length'] ) ? $attr['name_max_length'] : 50; ?>" class="validate" data-validate="required" name="_name" value="" />
        </div>
        <div class="hlgf-form-input">
            <label for="phone_number"><?php echo isset( $attr['phone_number_label'] ) ? $attr['phone_number_label'] : 'Phone Number'; ?> <abbr>*</abbr></label>
            <input type="text" maxlength="<?php echo isset( $attr['phone_number_max_length'] ) ? $attr['phone_number_length'] : 50; ?>" class="validate" data-validate="required" name="_phone_number" value="" />
        </div>
        <div class="hlgf-form-input">
            <label for="email_address"><?php echo isset( $attr['email_address_label'] ) ? $attr['email_address_label'] : 'Email Address'; ?> <abbr>*</abbr></label>
            <input type="email" maxlength="<?php echo isset( $attr['email_address_max_length'] ) ? $attr['email_address_max_length'] : 50; ?>" class="validate" data-validate="required,valid_email" name="_email_address" value="" />
        </div>
        <div class="hlgf-form-input">
            <label for="desired_budget"><?php echo isset( $attr['desired_budget_label'] ) ? $attr['desired_budget_label'] : 'Desired Budget'; ?> <abbr>*</abbr></label>
            <input type="text" maxlength="<?php echo isset( $attr['desired_budget_max_length'] ) ? $attr['desired_budget_max_length'] : 50; ?>" class="validate" data-validate="required" name="_desired_budget" value="" />
        </div>
        <div class="hlgf-form-input">
            <label for="message"><?php echo isset( $attr['message_label'] ) ? $attr['message_label'] : 'Message'; ?> <abbr>*</abbr></label>
            <textarea rows="<?php echo isset( $attr['message_rows'] ) ? $attr['message_rows'] : 4; ?>" cols="<?php echo isset( $attr['message_cols'] ) ? $attr['message_cols'] : 50; ?>" name="_message" maxlength="<?php echo isset( $attr['message_max_length'] ) ? $attr['message_max_length'] : 50; ?>" class="validate" data-validate="required"></textarea>
        </div>
        <div class="hlgf-form-input" style="text-align: right;">
            <button type="submit" id="hlgf-form-button" >Submit</button>
        </div>
    </form>

</div>