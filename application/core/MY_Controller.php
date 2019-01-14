<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class MY_Controller
 *
 * @property MY_Loader $load
 * @property MY_Router $router
 * @property MY_Input $input
 * @property CI_Output $output
 * @property CI_Security $security
 * @property CI_Session $session
 * @property CI_URI $uri
 * @property Template $template
 * @property MY_Lang $lang
 * @property MX_Config $config
 * @property MY_Form_validation $form_validation
 */
class MY_Controller extends MX_Controller
{
    public $_themeName = 'default-theme';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(['asset','template']);
        $this->load->helper(['language','url','form','html','application']);

        // set template theme
        $this->template->set_theme($this->_themeName);
        $this->template->set_layout('default');
        Asset::add_path('theme', [
            'path' => 'themes/' . $this->_themeName . '/',
            'js_dir' => 'assets/js/',
            'css_dir' => 'assets/css/',
            'img_dir' => 'assets/img/'
        ]);

        $this->_loadThemeConfiguration();

        // using xss clean by default if method using POST
        $_POST = $this->security->xss_clean($_POST);

        if (ENVIRONMENT === 'development' || $this->input->get('_debug'))
        {
            !$this->input->is_ajax_request() && $this->output->enable_profiler(true);
        }
    }

    protected function _loadThemeConfiguration()
    {
        $detailFile = APPPATH.'themes/'.$this->_themeName.'/detail'.EXT;
        if ( ! is_file($detailFile)) {
            return false;
        }
        include_once $detailFile;

        $class = 'Theme_'.ucfirst(strtolower(str_replace('-', '_', $this->_themeName)));
        if ( ! class_exists($class)) {
            throw new Exception("Theme $this->_themeName has an incorrect details.php class. It should be called '$class'.");
        }

        $theme = new $class;
        if (method_exists($theme, 'init')) {
            $theme->init();
        }
    }
}

function ci() {
    return get_instance();
}