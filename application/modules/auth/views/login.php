<div class="login-form row">
  <div class="col-md-4 mt-3 offset-md-4">

    <div class="card mt-5">
      <div class="card-body">
        <h5 class="card-title mb-4 mt-3 text-center">
          <?php echo lang('auth::heading'); ?>
        </h5>

        <p class="text-center"><?php echo lang('auth::description'); ?></p>

        <?php if (!empty($error_msg)) { ?>
        <div class="alert alert-danger">
          <?php echo $error_msg; ?>
        </div>
        <?php } ?>
          <?php if (!empty($success_msg)) { ?>
            <div class="alert alert-success">
                <?php echo $success_msg; ?>
            </div>
          <?php } ?>

        <?php echo form_open(); ?>
        <div class="form-group">
          <input type="text" name="user_login"
                 value="<?php echo set_value('user_login') ?>"
                 placeholder="<?php echo lang('auth::identity'); ?>"
                 class="form-control<?php echo formInvalid('user_login'); ?>"
                 autofocus autocomplete="off">
          <?php echo formInvalidFeedback('user_login'); ?>
        </div>

        <div class="form-group">
          <input type="password"
                 name="user_password"
                 class="form-control<?php echo formInvalid('user_password'); ?>"
                 placeholder="<?php echo lang('auth::password'); ?>">
          <?php echo formInvalidFeedback('user_password'); ?>
        </div>

        <div class="form-group row">
          <div class="col-md-6 mb-md-0 mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="remember-check" name="remember">
              <label class="form-check-label" for="remember-check">
                  <?php echo lang('auth::remember_me'); ?>
              </label>
            </div>
          </div>

          <div class="col-md-6 text-md-right text-left">
              <?php // echo anchor('auth/lupa-password', lang('auth::forgot_password')) ?>
          </div>
        </div>

        <div class="form-group">
          <button class="btn btn-login btn-primary btn-block"><?php echo lang('auth::login_btn'); ?></button>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>