<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['users/api/group(/:any)'] = 'group/group_api$1';
$route['users/api/group'] = 'group/group_api/index';

$route['users/api/user/get-groups'] = 'user/user_api/get_groups';
$route['users/api/user/get-branch-office'] = 'user/user_api/get_branch';
$route['users/api/user(/:any)'] = 'user/user_api$1';
$route['users/api/user'] = 'user/user_api/index';