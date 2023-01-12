<h2>Form Shortcode</h2>
<div class="hlgf-box">
    <h3>Shorcode Builder</h3>
    <div class="hlgf-container">
        <div>
            <h4>Name</h4>
            <div class="field-name" data-type="name" style="padding: 10px;">
                <div class="hlgf-input">
                    <label class="label">Label</label>
                    <input type="text" name="label" class="input-field" value="Name" />
                </div>
                <div class="hlgf-input">
                    <label class="max_length">Max Length</label>
                    <input type="number" name="max_length" value="" />
                </div>
            </div>
            <hr />
            <h4>Phone Number</h4>
            <div class="field-name" data-type="phone_number" style="padding: 10px;">
                <div class="hlgf-input">
                    <label class="label">Label</label>
                    <input type="text" name="label" value="Phone Number" />
                </div>
                <div class="hlgf-input">
                    <label class="max_length">Max Length</label>
                    <input type="number" name="max_length" value="" />
                </div>
            </div>
            <hr />
            <h4>Email address</h4>
            <div class="field-name" data-type="email_address" style="padding: 10px;">
                <div class="hlgf-input">
                    <label class="label">Label</label>
                    <input type="text" name="label" value="Email Address" />
                </div>
                <div class="hlgf-input">
                    <label class="max_length">Max Length</label>
                    <input type="number" name="max_length" value="" />
                </div>
            </div>
            <hr />
            <h4>Desired Budget</h4>
            <div class="field-name" data-type="desired_budget" style="padding: 10px;">
                <div class="hlgf-input">
                    <label class="label">Label</label>
                    <input type="text" name="label" value="Desired Budget" />
                </div>
                <div class="hlgf-input">
                    <label class="max_length">Max Length</label>
                    <input type="number" name="max_length" value="" />
                </div>>
            </div>
            <hr />
            <h4>Message</h4>
            <div class="field-name" data-type="message" style="padding: 10px;">
                <div class="hlgf-input">
                    <label class="label">Label</label>
                    <input type="text" name="label" value="Messages" />
                </div>
                <div class="hlgf-input">
                    <label class="max_length">Max Length</label>
                    <input type="number" name="max_length" value="" />
                </div>
                <div class="hlgf-input">
                    <label class="rows">Rows</label>
                    <input type="number" name="rows" value="3" />
                </div>
                <div class="hlgf-input">
                    <label class="cols">Columns</label>
                    <input type="number" name="cols" value="50" />
                </div>
            </div>
        </div>
        <div>
            <h4>Copy the shortcode below</h4>
            <div id="hlgf-shortcode" data-shortcodename="<?php echo $this->shortcode_name; ?>"></div>
        </div>
    </div>
</div>