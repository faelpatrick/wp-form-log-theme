<div class="form-container">
    <form id="customForm" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
        <input type="hidden" name="action" value="save_form_data">

        <input type="text" id="name" name="name" placeholder="Name" required>
        <label for="name" class="hidden-label label">Name:</label>

        <input type="tel" id="phone" name="phone" placeholder="Phone number">
        <label for="phone" class="hidden-label label">Phone number:</label>

        <input type="email" id="email" name="email" placeholder="Email" required>
        <label for="email" class="hidden-label label">Email:</label>

        <textarea id="message" name="message" rows="5" placeholder="Message" required></textarea>
        <label for="message" class="hidden-label label">Message:</label>

        <button type="submit">Submit</button>
    </form>
</div>