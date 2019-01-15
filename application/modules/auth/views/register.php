<div class="login-form row justify-content-md-center position-fixed fixed-top fixed-bottom">
  <div class="col-md-4 mt-3">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4 mt-3 text-center">
          <?php echo lang('auth::heading'); ?>
        </h4>

        <?php echo form_open(); ?>
        <div class="form-group">
          <input type="text" name="full_name"
                 value="<?php echo set_value('full_name') ?>"
                 placeholder="Full name"
                 class="form-control" autofocus>
        </div>

        <div class="form-group">
          <input type="text" name="phone"
                 value="<?php echo set_value('phone') ?>"
                 placeholder="Phone"
                 class="form-control">
        </div>

        <div class="form-group">
          <input type="text" name="email"
                 value="<?php echo set_value('email') ?>"
                 placeholder="Email"
                 class="form-control">
        </div>

        <div class="form-group">
          <input type="text" name="username"
                 value="<?php echo set_value('username') ?>"
                 placeholder="Username"
                 class="form-control">
        </div>

        <div class="form-group">
          <input type="password"
                 name="user_password"
                 class="form-control"
                 placeholder="<?php echo lang('auth::password'); ?>">
        </div>

        <div class="form-group">
          <button class="btn btn-success btn-block"><?php echo lang('auth::login_btn'); ?></button>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>