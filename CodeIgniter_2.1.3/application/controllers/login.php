<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
 	{
		parent::__construct();
 	}

	function index()
	{
		$this->load->model('user');
                $users = $this->user->getAllUsers();
                $data['users'] = array();
                foreach ($users as $u)
                {
                    array_push($data['users'], $u);
                }

                $this->load->helper(array('form'));
                $this->load->view('templates/header');
		$this->load->view('login_view', $data);
                $this->load->view('templates/footer');
	}

}

?>