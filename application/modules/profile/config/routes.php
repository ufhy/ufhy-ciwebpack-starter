<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['profile/api/save-changes'] = 'profile_api/save_changes';
$route['profile/api(/:any)'] = 'profile_api$1';