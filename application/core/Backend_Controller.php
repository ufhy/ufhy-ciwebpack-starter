<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_Controller extends MY_Controller
{
    public $_section = null;

    public function __construct()
    {
        parent::__construct();

        if (!$this->_checkAccess()) {
            $this->session->set_flashdata('message.error', lang('msg::access_denied'));
            redirect();
        }

        $ufhy['permissions'] = $this->_permissions;
        $ufhy['isAdmin'] = $this->the_auth->isUserAdmin();
        $this->load->vars($ufhy);

        // set default language already loaded
        ci()->template->append_metadata(
            sprintf(
                '<script>ufhy.LANGS = %s;</script>',
                json_encode($this->lang->language)
            )
        );

        // set profile user login
        if (isLoggedIn()) {
            ci()->template->append_metadata(
                sprintf(
                    '<script>ufhy.USER = %s;</script>',
                    json_encode([
                        'fullName' => $this->_currentUser->profile->full_name,
                        'username' => $this->_currentUser->username,
                        'email' => $this->_currentUser->email,
                    ])
                )
            );
        }

        $this->template->active_section = $this->_section;
        $this->_buildNavigation();
    }

    private function _checkAccess()
    {
        $defaultPages = [
            '/index',
            '/dashboard',
            '/profile',
        ];
        $currentPage = $this->uri->segment(1, '') . '/' . $this->uri->segment(2, 'index');

        if (!$this->_currentUser) {
            $this->session->set_flashdata('message.error', lang('msg::must_login'));
            $redirectKey = $this->config->item('auth_redirect_key');
            $this->session->set_userdata($redirectKey, $this->uri->uri_string());
            redirect('auth');
        }

        if ($this->the_auth->isUserAdmin()) {
            return true;
        }

        if ($this->_currentUser) {
            if (in_array($currentPage, $defaultPages) && $this->_permissions) {
                return true;
            }

            if (array_key_exists($this->_module, $this->_permissions)) {
                $permissionSection = $this->_permissions[$this->_module];
                if (is_array($permissionSection)) {
                    return array_key_exists($this->_section, $permissionSection);
                }
                return $permissionSection;
            }
        }

        return false;
    }

    public function _buildNavigation()
    {
        $menuItems = [];
        $orderedMenu = [];
        foreach (ci()->enabledModules as $module)
        {
            if ( $module['menu'] && (isset($this->permissions[$module['slug']]) OR $this->the_auth->isUserAdmin()) )
            {
                $menuItems['menu::group:'.$module['menu_group']]
                ['menu::'.$module['menu']]
                ['menu::'.$module['slug']] = new stdClass();
                $menuItems['menu::group:'.$module['menu_group']]
                ['menu::'.$module['menu']]
                ['menu::'.$module['slug']]->url = $module['slug'];
                $menuItems['menu::group:'.$module['menu_group']]
                ['menu::'.$module['menu']]
                ['menu::'.$module['slug']]->active = $this->_module === $module['slug'];
            }

            if (method_exists($module['module'], 'admin_menu')) {
                $module['module']->admin_menu($menuItems, $this->_section ? $this->_section : $this->_module);
            }
        }

        if ($menuItems)
        {
            $translatedMenuItems = array();

            // translate any additional top level menu keys so the array_merge works
            foreach ($menuItems as $key => $menuItem)
            {
                $translatedMenuItems[$key] = $menuItem;
            }

            $orderedMenu = array_merge_recursive($orderedMenu, $translatedMenuItems);
        }

        $this->template->menu_items = $orderedMenu;
        ci()->template->append_metadata(
            sprintf(
                '<script>ufhy.MENU_ITEMS = %s;</script>',
                json_encode($orderedMenu)
            )
        );
    }
}