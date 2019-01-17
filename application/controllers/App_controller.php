<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_controller extends Backend_Controller {

	public function index()
	{
        $this->template->build('app_view');
	}
}
