<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

	function __construct()
 	{
		parent::__construct();
 	}

	function index()
	{
		$this->load->helper(array('form'));
                $this->load->view('templates/header');
		$this->load->view('registration_view');
                $this->load->view('templates/footer');
	}

        function save()
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $this->load->model('user');
            $this->user->register($username, $password);
            
            redirect('home');
        }
}

?>