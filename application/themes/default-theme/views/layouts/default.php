<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  echo file_partial('meta');
  echo file_partial('script-top');
  echo $template['metadata'];
  ?>
</head>
<body>

<div id="root">
  <b-navbar toggleable="md" type="light" variant="light" class="mb-3">
    <b-navbar-brand href="#">
      <?php echo $site_name_abbr; ?>
    </b-navbar-brand>

    <b-collapse is-nav id="nav_collapse">
      <b-navbar-nav>
        <?php echo file_partial('navbar-menu'); ?>
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
        <b-nav-item-dropdown
          text="<?php echo $current_user->profile->full_name ? $current_user->profile->full_name : 'User login'; ?>"
          right>
          <b-dropdown-item to="/profile">
            Profile
          </b-dropdown-item>
          <div class="dropdown-divider"></div>
          <b-dropdown-item href="<?php echo site_url('auth/logout') ?>">
            Logout
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>

  <?php echo $template['body']; ?>
</div>

<?php echo file_partial('script-bottom'); ?>
</body>
</html>