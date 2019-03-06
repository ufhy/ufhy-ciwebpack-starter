<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Group_api
 *
 * @property Group_model $group_model
 * @property Group_permission_model $group_permission_model
 */
class Group_api extends Api_Controller
{
    public $_section = 'group';

    protected $_validationRules = [
        ['field' => 'name', 'label' => 'lang:users::group:name', 'rules' => 'trim|required|max_length[255]|callback__check_name'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('users/group_model');
        $this->lang->load('users/group');
    }

    public function index()
    {
        $this->load->library('datatables_server');
        $request = $this->input->get();
        $columns = [
            ['db' => 'id', 'bt' => 'id'],
            ['db' => 'name', 'bt' => 'name'],
            ['db' => 'description', 'bt' => 'descr'],
            ['db' => 'is_default', 'bt' => 'isDefault', 'formatter' => function($value) {
                return $value > 0;
            }],
            ['db' => 'is_admin', 'bt' => 'isAdmin', 'formatter' => function ($value) {
                return $value > 0;
            }],
            ['db' => 'created_at', 'bt' => 'createdAt'],
            ['db' => 'updated_at', 'bt' => 'updatedAt'],
        ];

        $results = $this->datatables_server->process($request, $columns, $this->group_model->table);
        $this->template->build_json($results);
    }

    public function item()
    {
        if (!$this->input->get('id')) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        $id = $this->input->get('id', TRUE);
        $item = $this->group_model->get(['id' => $id]);
        if (!$item) {
            $this->output->set_status_header('404');
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
            return false;
        }

        $data = [
            'id'        => $item->id,
            'name'      => $item->name,
            'descr'     => $item->description,
            'isDefault' => $item->is_default > 0,
            'isAdmin'   => $item->is_admin > 0,
            'createdAt' => $item->created_at,
            'updatedAt' => $item->updated_at,
        ];

        $this->template->build_json([
            'success' => true,
            'data' => $data
        ]);
        return true;
    }

    public function permissions()
    {
        userHasRoleOrDie('change_permission', 'users', 'group');

        if (!$this->input->get('id')) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        $id = $this->input->get('id', TRUE);
        $group = $this->group_model->get(['id' => $id]);
        if (!$group) {
            $this->output->set_status_header('404');
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
            return false;
        }

        if ($_POST) {
            $modules = $this->input->post('modules');
            $roles = $this->input->post('module_roles');
            
            if ($this->group_permission_model->save($id, $modules, $roles)) {
                Events::trigger('users::group:permission_changed', [$id, $modules, $roles]);
                $this->template->build_json([
                    'success' => true,
                    'message' => sprintf(lang('msg::saving_success_fmt'), 'permissions')
                ]);
            }
            else {
                $this->template->build_json([
                    'success' => false,
                    'message' => lang('msg::saving_failed')
                ]);
            }
            return false;
        }

        $groupIsAdmin = (boolean) ($group->is_admin);
        if ($groupIsAdmin) {
            $this->template->build_json([
                'success' => false,
                'groupId' => $group->id,
                'groupName' => $group->name,
                'isAdmin' => true,
                'message' => lang('msg::users::group:cant_set_permission_admin')
            ]);
            return false;
        }

        $this->load->model('users/group_permission_model');
        $editPermissions = ($groupIsAdmin) ? array() : $this->group_permission_model->getByGroup($id);
        $permissionList = $this->module_model->getAllPermissions();

        $this->template->build_json([
            'success' => true,
            'groupId' => $group->id,
            'groupName' => $group->name,
            'modules' => $permissionList,
            'editPermissions' => $editPermissions ? $editPermissions : new stdClass() 
        ]);
        return true;
    }

    public function create()
    {
        userHasRoleOrDie('create', 'users', 'group');

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->form_validation->set_rules($this->_validationRules);
        if ($this->form_validation->run())
        {
            $data = [
                'name'          => $this->input->post('name', TRUE),
                'description'   => $this->input->post('descr', TRUE),
                'is_default'    => (bool) $this->input->post('isDefault'),
                'is_admin'      => (bool) $this->input->post('isAdmin'),
            ];

            $result = $this->group_model->create($data);
            if ($result) {
                Events::trigger('users::group:created', $result);
                $this->template->build_json([
                    'success' => true,
                    'message' => lang('msg::saving_success')
                ]);
            } else {
                $this->template->build_json([
                    'success' => false,
                    'message' => lang('msg::saving_failed')
                ]);
            }
        }
        else {
            $this->output->set_status_header('400', lang('msg::saving_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => $this->form_validation->error_array()
            ]);
        }
    }

    public function edit()
    {
        userHasRoleOrDie('edit', 'users', 'group');

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->form_validation->set_rules($this->_validationRules);
        $this->form_validation->set_rules('id', 'lang:label::id', 'trim|required');
        if ($this->form_validation->run())
        {
            $data = [
                'name'          => $this->input->post('name', TRUE),
                'description'   => $this->input->post('descr', TRUE),
                'is_default'    => (bool) $this->input->post('isDefault'),
                'is_admin'      => (bool) $this->input->post('isAdmin'),
            ];

            $result = $this->group_model->edit($this->input->post('id', TRUE), $data);
            if ($result) {
                Events::trigger('users::group:edited', $this->input->post('id', TRUE));
                $this->template->build_json([
                    'success' => true,
                    'message' => lang('msg::saving_success')
                ]);
            } else {
                $this->template->build_json([
                    'success' => false,
                    'message' => lang('msg::saving_failed')
                ]);
            }
        }
        else {
            $this->output->set_status_header('400', lang('msg::saving_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => $this->form_validation->error_array()
            ]);
        }
    }

    public function _check_name($name = '')
    {
        if ($this->group_model->with_trashed()->check_unique_field('name', $name, $this->input->post('id')))
        {
            $this->form_validation->set_message('_check_name', sprintf(lang('msg::users::group:name_already_exist'), $name));
            return false;
        }

        return true;
    }

    public function remove()
    {
        if (!$this->input->get('id')) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        $id = $this->input->get('id', TRUE);
        $group = $this->group_model->fields('id,name')->get(['id' => $id]);    // find partner
        if (!$group) {
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
        }

        $remove = $this->group_model
            ->delete(['id' => $this->input->get('id', TRUE)]);
        if (!$remove) {
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::delete_failed')
            ]);
            return false;
        }

        $this->template->build_json([
            'success' => true,
            'message' => sprintf(lang('msg::delete_success_fmt'), $group->name)
        ]);

        return true;
    }
}