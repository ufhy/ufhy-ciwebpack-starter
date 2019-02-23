<v-content>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md4>
        <form autocomplete="off" method="post">
          <v-card>
            <?php if (!empty($error_msg)) { ?>
              <v-alert :value="true" type="error" transition="scale-transition" dismissible>
                <?php echo $error_msg; ?>
              </v-alert>
            <?php } ?>
            <?php if (!empty($success_msg)) { ?>
              <v-alert :value="true" type="success" transition="scale-transition" dismissible>
                <?php echo $success_msg; ?>
              </v-alert>
            <?php } ?>
            
            <v-card-text>
              <h5 class="mb-4 mt-3 title font-weight-bold text-xs-center">
                <?php echo lang('auth::heading'); ?>
              </h5>
              <p class="text-center"><?php echo lang('auth::description'); ?></p>

              <v-text-field autofocus solo flat
                autocomplete="false"
                label="<?php echo lang('auth::identity'); ?>"
                name="user_login"
                error-messages="<?php echo form_error('user_login') ?>"
                prepend-inner-icon="la-user"
              ></v-text-field>

              <v-text-field autofocus solo flat
                type="password"
                label="<?php echo lang('auth::password'); ?>"
                name="user_password"
                error-messages="<?php echo form_error('user_password') ?>"
                prepend-inner-icon="la-lock"
              ></v-text-field>

              <v-checkbox 
                label="<?php echo lang('auth::remember_me'); ?>" 
                value="1" 
                name="remember"
              ></v-checkbox>

              <v-btn class="ml-0" color="primary" type="submit">
                <?php echo lang('auth::login_btn'); ?>
              </v-btn>

              <?php // echo anchor('auth/lupa-password', lang('auth::forgot_password')) ?>
            </v-card-text>
          </v-card>
        </form>
      </v-flex>
    </v-layout>
  </v-container>
</v-content>
