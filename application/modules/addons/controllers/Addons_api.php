<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Addons_api
 *
 */
class Addons_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {}

    public function i18n()
    {
        $this->template->build_json($this->lang->language);
    }
}