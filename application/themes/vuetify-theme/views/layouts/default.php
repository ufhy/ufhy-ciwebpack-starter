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
  <?php echo $template['body']; ?>
</div>

<?php echo file_partial('script-bottom'); ?>
</body>
</html>