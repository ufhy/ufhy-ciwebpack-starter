<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['files/api(/:any)']    = 'files_api$1';
$route['files/api']           = 'files_api/index';
