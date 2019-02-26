<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('isLoggedIn'))
{
	function isLoggedIn() 
	{
		return ci()->the_auth->loggedIn();
	}
}

if (!function_exists('isUserAdmin'))
{
	function isUserAdmin() 
	{
		return ci()->the_auth->isUserAdmin();
	}
}

if (!function_exists('userHasModule'))
{
    function userHasModule($module)
    {
		if (isUserAdmin()) {
			return true;
		}

        return isset(ci()->permissions[$module]);
    }
}

if (!function_exists('userHasModuleSection'))
{
    function userHasModuleSection($module, $section)
    {
		if (isUserAdmin()) {
			return true;
		}

        return isset(ci()->permissions[$module][$section]);
    }
}

if (!function_exists('userHasRole'))
{
	function userHasRole($role, $module, $section = null)
	{
		if (!ci()->currentUser) {
			return false;
		}

		if (isUserAdmin()) {
			return true;
        }

		if (empty($section)) {
            $permission = ci()->permissions[$module];
        } else {
		    $permission = ci()->permissions[$module][$section];
        }

        if (!$permission) {
		    return false;
        }

        if (in_array($role, $permission)) {
            return true;
        }

        return false;
	}
}

if (!function_exists('userHasRoleOrDie'))
{
	function userHasRoleOrDie($role, $module, $section = null, $redirectTo = '/', $message = null)
	{
        if (!ci()->currentUser) {
            return false;
        }

        if (isUserAdmin()) {
            return true;
        }

        if (ci()->input->is_ajax_request() && !userHasRole($role, $module, $section)) {
            ci()->output->set_status_header(401);
            die(json_encode([
                'success' => false,
                'authenticated' => false,
                'message' => ($message ? $message : lang('auth::msg:access_denied'))
            ]));
            // return false;
        }
        else if (!userHasRole($role, $module, $section)) {
            ci()->session->set_flashdata('message.error', ($message ? $message : lang('auth::msg:access_denied')) );
            redirect($redirectTo);
        }

        return true;
	}
}
