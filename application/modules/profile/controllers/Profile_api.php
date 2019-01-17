<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Profile_api
 *
 * @property Branch_office_model $branch_office_model
 * @property User_model $user_model
 * @property Profile_model $profile_model
 */
class Profile_api extends Api_Controller
{
    protected $_validationRules = [
        ['field' => 'fullName', 'label' => 'lang:profile::full_name', 'rules' => 'trim|required|max_length[50]'],
        ['field' => 'email', 'label' => 'lang:profile::email', 'rules' => 'trim|required|max_length[100]|min_length[5]|callback__check_email'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('profile/profile');
        $this->load->model('profile/profile_model');
    }

    public function index()
    {
        if (empty($this->_currentUser)) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        $user = $this->the_auth->getUserLogin();
        if (!$user) {
            $this->output->set_status_header('404');
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_empty')
            ]);
            return false;
        }

        $data = [
            'id' => $user->id,
            'fullName'  => $user->profile->full_name,
            'phone'      => $user->profile->phone,
            'email'   => $user->email,
            'username' => $user->username,
        ];

        $this->template->build_json([
            'success' => true,
            'data' => $data
        ]);
        return true;
    }

    public function save_changes()
    {
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $changePassword = (boolean) $this->input->post('changePassword');

        $this->form_validation->set_rules($this->_validationRules);
        // if login using username
        if ($this->the_auth->getLoginIdentity() === 'username') {
            $this->form_validation->set_rules('username', 'lang:profile::username', 'trim|required|min_length[5]|max_length[100]|callback__check_username');
        }
        // if user change password
        if ($changePassword) {
            $passMinLength = $this->the_auth->getMinPasswordLength();
            $passMaxLength = $this->the_auth->getMaxPasswordLength();
            $this->form_validation->set_rules('oldPassword', 'lang:profile::old_password', 'trim|required|callback__check_old_password');
            $this->form_validation->set_rules('newPassword', 'lang:profile::new_password', 'trim|required|min_length['.$passMinLength.']|max_length['.$passMaxLength.']');
            $this->form_validation->set_rules('confirNewPassword', 'lang:profile::confirm_new_password', 'trim|required|matches[newPassword]');
        }

        if ($this->form_validation->run())
        {
            $userId = $this->the_auth->getUserLoginId();
            $user = [
                'username' => $this->input->post('username', TRUE),
                'email' => $this->input->post('email', TRUE),
            ];
            $profile = [
                'full_name' => $this->input->post('fullName', TRUE),
                'phone' => $this->input->post('phone', TRUE),
            ];
            $update = $this->the_auth->updateUser($userId, $user, $profile);
            if ($update) {
                Events::trigger('users::user:updated', $update);
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

    public function _check_nik($nik = '')
    {
        if (!empty($nik)) {
            if ($this->profile_model->with_trashed()->check_unique_field('nik', $nik, $this->the_auth->getUserLoginId(), 'user_id')) {
                $this->form_validation->set_message('_check_nik', sprintf(lang('msg::profile::nik_already_exist'), $nik));
                return false;
            }
        }

        return true;
    }

    public function _check_email($email = '')
    {
        if (!empty($email)) {
            if ($this->user_model->with_trashed()->check_unique_field('email', $email, $this->the_auth->getUserLoginId())) {
                $this->form_validation->set_message('_check_email', sprintf(lang('msg::profile::email_already_exist'), $email));
                return false;
            }
        }

        return true;
    }

    public function _check_username($username = '')
    {
        if (!empty($username)) {
            if ($this->user_model->with_trashed()->check_unique_field('username', $username, $this->the_auth->getUserLoginId())) {
                $this->form_validation->set_message('_check_username', sprintf(lang('msg::profile::username_already_exist'), $username));
                return false;
            }
        }

        return true;
    }

    public function _check_old_password($old_password = '')
    {
        if (!empty($old_password)) {
            $password = $this->the_auth->hashPasswordDb($this->the_auth->getUserLoginId(), $old_password);
            if (!$password) {
                $this->form_validation->set_message('_check_old_password', lang('msg::profile::old_password_wrong'));
                return false;
            }
        }

        return true;
    }
}