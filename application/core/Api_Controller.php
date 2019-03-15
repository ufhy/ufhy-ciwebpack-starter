<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Controller extends MY_Controller
{
    public $_section = null;

    public function __construct()
    {
        parent::__construct();

        if (!$this->_checkAccess()) {
            $this->output->set_status_header('405');
            $this->output->set_header('Content-Type: application/json; charset=utf-8');
            die(json_encode([
                'success' => false,
                'message' => lang('msg::access_denied') . ' Refresh browser anda'
            ]));
        }

        $this->output->enable_profiler(FALSE);
    }

    private function _checkAccess()
    {
        $defaultPages = [
            'api/index',
            'api/dashboard',
            'api/profile',
            'api/notifications',
            'api/addons',
        ];

        $currentPage = $this->uri->segment(1, '') . '/' . $this->uri->segment(2, 'index');
        
        if (!$this->_currentUser) {
            $this->output->set_status_header('403');
            $this->output->set_header('Content-Type: application/json; charset=utf-8');
            die(json_encode([
                'success' => false,
                'message' => lang('msg::must_login') . ' Refresh browser anda'
            ]));
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
                if (empty($this->_section)) {
                    // every controler must be have "read" role
                    $filter = array_filter($permissionSection, function($row) {
                        return $row === 'read';
                    });
                    return count($filter) > 0;
                }
                else if (is_array($permissionSection)) {
                    return userHasModuleSection($this->_module, $this->_section);
                }

                return false;
            }
        }

        return false;
    }
}