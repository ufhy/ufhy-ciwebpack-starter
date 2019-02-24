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
        if (!$this->input->get('module'))
        {
            $this->template->build_json($this->lang->language);
            return false;
        }

        $module = $this->input->get('module');
        $langs = [];
        if ($module) {
            $lang = '';
            $deft_lang = $this->config->item('language');
            $idiom = ($lang == '') ? $deft_lang : $lang;
            list($path, $_langfile) = Modules::find($module . '_lang', $module, 'language/' . $idiom . '/');

            if ($path) {
                $langs = Modules::load_file($_langfile, $path, 'lang');
            }
        }

        $this->template->build_json($langs);
    }
}