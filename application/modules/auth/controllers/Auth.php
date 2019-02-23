<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 *
 * @property User_model $user_model
 */
class Auth extends Public_Controller
{
    public $_themeName = 'auth-theme';

    public function __construct()
    {
        parent::__construct();

        $this->template->set_layout('auth');
        $this->lang->load('auth/auth');
        $this->template->title('Auth');
    }

    // login page
    public function index()
    {
        $redirectKey = $this->config->item('auth_redirect_key');
        $redirectUri = $this->session->userdata($redirectKey);

        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;

        $this->form_validation->set_rules('user_login', 'lang:auth::identity', 'required');
        $this->form_validation->set_rules('user_password', 'lang:auth::password', 'required');
        if ($this->form_validation->run())
        {
            $remember = (bool)$this->input->post('remember');
            $userLogin = $this->input->post('user_login', TRUE);
            $password = $this->input->post('user_password', TRUE);
            if ($this->the_auth->doLogin($userLogin, $password, $remember))
            {
                $this->session->set_flashdata('message.success', $this->the_auth->getMessageStr());
                redirect($redirectUri ? $redirectUri : '');
            }
            else {
                $this->session->set_flashdata('message.error', $this->the_auth->getErrorStr('', '<br/>'));
                redirect('auth', 'refresh');
            }
        }

        $this->template
            ->pageTitle('Login')
            ->set('error_msg', $this->session->flashdata('message.error'))
            ->set('success_msg', $this->session->flashdata('message.success'))
            ->build('pages/login');
    }

    public function logout()
    {
        $this->the_auth->doLogout();
        $this->session->set_flashdata('message.success', $this->the_auth->getMessageStr());

        redirect('auth');
    }
}