<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
 	{
		parent::__construct();
 	}

	function index()
	{
		$this->load->model('user');
                $data['users'] = $this->user->getAllUsers();

                $this->load->helper(array('form'));
                $this->load->view('templates/header');
		$this->load->view('login_view', $data);
                $this->load->view('templates/footer');
	}

}

?>