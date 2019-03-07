<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class User_api
 *
 * @property User_model $user_model
 * @property Group_model $group_model
 * @property Profile_model $profile_model
 * @property Branch_office_model $branch_office_model
 */
class User_api extends Api_Controller
{
    public $_section = 'user';

    protected $_validationRules = [
        ['field' => 'fullname', 'label' => 'lang:users::user:full_name', 'rules' => 'trim|required|max_length[50]'],
        ['field' => 'email', 'label' => 'lang:users::user:email', 'rules' => 'trim|required|max_length[100]|min_length[5]|callback__check_email'],
        ['field' => 'groupId', 'label' => 'lang:users::user:group', 'rules' => 'trim|required|callback__check_group'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('users/user_model');
        $this->lang->load('users/user');
    }

    public function index()
    {
        $this->load->library('datatables_server');

        $this->load->model('users/profile_model');
        $this->load->model('users/group_model');

        $request = $this->input->get();
        $userTable = $this->user_model->table;
        $columns = [
            ['db' => $userTable.'.id', 'bt' => 'id'],
            ['db' => $userTable.'.username', 'bt' => 'username'],
            ['db' => $userTable.'.email', 'bt' => 'email'],
            ['db' => $userTable.'.active', 'bt' => 'active', 'formatter' => function($val) {
                return $val >= 1;
            }],
            ['db' => $userTable.'.lang', 'bt' => 'lang'],
            ['db' => $userTable.'.group_id', 'bt' => 'groupId'],
            ['db' => $userTable.'.last_login', 'bt' => 'lastLogin'],
            ['db' => $userTable.'.created_at', 'bt' => 'createdAt'],
            ['db' => $userTable.'.updated_at', 'bt' => 'updatedAt'],
        ];
        $profileTable = $this->profile_model->table;
        $groupTable = $this->group_model->table;

        $results = $this->datatables_server
            ->setTableJoin(
                $profileTable,
                sprintf('%s.user_id = %s.id' , $profileTable, $userTable),
                [
                    ['db' => $profileTable.'.full_name', 'bt' => 'fullName'],
                    ['db' => $profileTable.'.phone', 'bt' => 'phone'],
                    ['db' => $profileTable.'.photo_file', 'bt' => 'photoFile']
                ],
                'LEFT'
            )
            ->setTableJoin(
                $groupTable,
                sprintf('%s.group_id = %s.id' , $userTable, $groupTable),
                [ ['db' => $groupTable.'.name', 'bt' => 'groupName'] ],
                'LEFT'
            )
            ->process($request, $columns, $userTable);
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
        $item = $this->user_model->fields('*')
            ->with('profile', ['fields:full_name,phone,photo_file'])
            ->with('group', ['fields:id,name,is_admin'])
            ->get(['id' => $id]);
        if (!$item) {
            $this->output->set_status_header('404');
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
            return false;
        }

        $data = [
            'id'                => $item->id,
            'email'             => $item->email,
            'username'          => $item->username,
            'groupId'           => $item->group_id,
            'active'            => (bool) $item->active,
            'lastLogin'         => $item->last_login,
            'lang'              => $item->lang,
            'createdAt'         => $item->created_at,
            'updatedAt'         => $item->updated_at,
            'profile' => [
                'fullName'      => $item->profile ? $item->profile->full_name : '',
                'phone'         => $item->profile ? $item->profile->phone : '',
                'photo'         => $item->profile ? $item->profile->photo_file : '',
            ],
            'group' => [
                'id'            => $item->group ? $item->group->id : '',
                'name'          => $item->group ? $item->group->name : '',
                'isAdmin'       => $item->group ? $item->group->is_admin > 0 : '',
            ],
        ];

        $this->template->build_json([
            'success' => true,
            'row' => $data
        ]);
        return true;
    }

    public function formoptions()
    {
        // get group options
        $this->load->model('users/group_model');
        $allGroups = $this->group_model->fields('id,name')
            ->order_by('name')
            ->get_all();
        $groups = [];
        if ($allGroups) {
            foreach ($allGroups as $group) {
                $groups[] = [
                    'value' => $group->id,
                    'text' => $group->name
                ];
            }
        }

        $this->template->build_json([
            'groups' => $groups
        ]);
    }

    public function create()
    {
        userHasRoleOrDie('create', 'users', 'user');

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $passMinLength = $this->the_auth->getMinPasswordLength();
        $passMaxLength = $this->the_auth->getMaxPasswordLength();

        $this->form_validation->set_rules($this->_validationRules);
        $this->form_validation->set_rules('password', 'lang:users::user:password', 'trim|required|min_length['.$passMinLength.']|max_length['.$passMaxLength.']');
        $this->form_validation->set_rules('passwordConfirm', 'lang:users::user:rePassword', 'trim|required|matches[password]');
        if ($this->the_auth->getLoginIdentity() === 'username') {
            $this->form_validation->set_rules('username', 'lang:users::user:username', 'trim|required|min_length[5]|max_length[100]|callback__check_username');
        }

        if ($this->form_validation->run())
        {
            $profile = [
                'full_name'  => $this->input->post('fullname', TRUE),
                'phone'      => $this->input->post('phone', TRUE),
            ];
            $active = (bool) $this->input->post('active');
            $register = $this->the_auth->register(
                $this->input->post('username'),
                $this->input->post('password'),
                $this->input->post('email'),
                $this->input->post('groupId'),
                $profile,
                'id',
                $active
            );
            if ($register) {
                Events::trigger('users::user:created', $register);
                $this->template->build_json([
                    'success' => true,
                    'message' => $this->the_auth->getMessageStr()
                ]);
            } else {
                $this->template->build_json([
                    'success' => false,
                    'message' => $this->the_auth->getErrorStr()
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

    /**
     * todo edit user list
     */
    public function edit()
    {
        userHasRoleOrDie('edit', 'users', 'user');

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->form_validation->set_rules($this->_validationRules);
        if ($this->the_auth->getLoginIdentity() === 'username') {
            $this->form_validation->set_rules('username', 'lang:users::user:username', 'trim|required|min_length[5]|max_length[100]|callback__check_username');
        }

        if ($this->form_validation->run())
        {
            $profileData = [
                'full_name'  => $this->input->post('fullName', TRUE),
                'phone'      => $this->input->post('phone', TRUE),
                'position'   => $this->input->post('position', TRUE),
                'nik'        => $this->input->post('nik', TRUE),
            ];
            $userData = [
                'username'  => $this->input->post('username', TRUE),
                'email'     => $this->input->post('email', TRUE),
                'branch_id' => $this->input->post('branchId'),
                'group_id'  => $this->input->post('groupId'),
                'active'    => (bool) $this->input->post('active'),
            ];

            $result = $this->the_auth->updateUser($this->input->post('id', TRUE), $userData, $profileData);
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

    public function _check_nik($nik = '')
    {
        if ($this->profile_model->with_trashed()->check_unique_field('nik', $nik, $this->input->post('id'), 'user_id'))
        {
            $this->form_validation->set_message('_check_nik', sprintf(lang('msg::users::user:nik_already_exist'), $nik));
            return false;
        }

        return true;
    }

    public function _check_email($email = '')
    {
        if ($this->user_model->with_trashed()->check_unique_field('email', $email, $this->input->post('id')))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('msg::users::user:email_already_exist'), $email));
            return false;
        }

        return true;
    }

    public function _check_username($username = '')
    {
        if ($this->user_model->with_trashed()->check_unique_field('username', $username, $this->input->post('id')))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('msg::users::user:username_already_exist'), $username));
            return false;
        }

        return true;
    }

    public function _check_branch($branchId)
    {
        $this->load->model('reference/branch_office_model');
        if (!$this->branch_office_model->get(['id' => $branchId]))
        {
            $this->form_validation->set_message('_check_branch', lang('msg::users::user:branch_not_exist'));
            return false;
        }

        return true;
    }

    public function _check_group($groupId)
    {
        $this->load->model('users/group_model');
        if (!$this->group_model->get(['id' => $groupId]))
        {
            $this->form_validation->set_message('_check_group', lang('msg::users::user:group_not_exist'));
            return false;
        }

        return true;
    }

    public function remove()
    {
        userHasRoleOrDie('remove', 'users', 'user');

        if (!$this->input->get('id')) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        $id = $this->input->get('id', TRUE);
        $profile = $this->profile_model->fields('user_id,full_name')->get(['user_id' => $id]);
        if (!$profile) {
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
        }

        $remove = $this->the_auth->deleteUser($id);
        if (!$remove) {
            $this->template->build_json([
                'success' => false,
                'message' => $this->the_auth->getErrorStr()
            ]);
            return false;
        }

        $this->template->build_json([
            'success' => true,
            'message' => sprintf(lang('msg::delete_success_fmt'), $profile->full_name)
        ]);

        return true;
    }

    public function get_groups()
    {
        $this->load->model('users/group_model');
        $groups = $this->group_model
            ->fields('id,name')
            ->order_by('name', 'ASC')
            ->as_array()
            ->get_all();
        if (!$groups) {
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::users::user:group_empty')
            ]);
            return false;
        }

        $this->template->build_json([
            'success' => true,
            'rows' => $groups
        ]);
        return true;
    }

    public function change_password()
    {
        userHasRoleOrDie('change_password', 'users', 'user');

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $passMinLength = $this->the_auth->getMinPasswordLength();
        $passMaxLength = $this->the_auth->getMaxPasswordLength();

        $this->form_validation->set_rules('id', 'lang:label::id', 'trim|required');
        $this->form_validation->set_rules('newPassword', 'lang:users::user:password', 'trim|required|min_length['.$passMinLength.']|max_length['.$passMaxLength.']');
        $this->form_validation->set_rules('reNewPassword', 'lang:users::user:rePassword', 'trim|required|matches[newPassword]');
        if ($this->form_validation->run())
        {
            $changed = $this->the_auth->changeUserPassword(
                $this->input->post('id', TRUE),
                $this->input->post('newPassword', TRUE)
            );
            if ($changed) {
                Events::trigger('users::user:changed_password', $this->input->post('id', TRUE));
                $this->template->build_json([
                    'success' => true,
                    'message' => $this->the_auth->getMessageStr()
                ]);
            } else {
                $this->template->build_json([
                    'success' => false,
                    'message' => $this->the_auth->getErrorStr()
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
}