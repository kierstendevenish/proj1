<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Home extends CI_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
     $data['username'] = $session_data['username'];
     $this->load->model('user');
     //$location = $this->user->getLocation($data['username']);
     //$data['lat'] = $location['lat'];
     //$data['long'] = $location['long'];

     $token = $this->user->getFoursquareToken($data['username']);

     if(($token == '') or ($token == NULL))
     {
        $data['connected'] = false;
     }
     else
     {
        $data['connected'] = true;
     }

        $data['checkins'] = $this->getCheckins();
     
        $this->load->view('templates/header');
        $this->load->view('home_view', $data);
        $this->load->view('templates/footer');
   }
   else
   {
     //If no session, redirect to login page
     redirect('login', 'refresh');
   }
 }

 function logout()
 {
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('home', 'refresh');
 }
 
 function esl()
 {
     $session_data = $this->session->userdata('logged_in');
     $username = $session_data['username'];
     $this->load->model('user');
     $data['esl'] = $this->user->getEsl($username);
     
     $this->load->view('templates/header');
     $this->load->view('esl', $data);
     $this->load->view('templates/footer');
 }
 
 function setEsl()
 {
     $session_data = $this->session->userdata('logged_in');
     $username = $session_data['username'];
     $this->load->model('user');
     
     $esl = $this->input->post('esl');
     
     $this->user->setEsl($username, $esl);
     
     redirect('home');
 }

 function makeEsl()
 {
    $uid = uniqid();
    $esl = site_url() . "/rfq/index/" . $uid;

    $session_data = $this->session->userdata('logged_in');
    $username = $session_data['username'];

    //insert esl into db
    $this->load->model('user');
    $this->user->saveEsl($username, $esl);

    //redirect to home page (need to load all curr esls on home page)
    redirect('driver/listEsls');
 }

        function getCheckins()
        {
            $this->load->model('user');
            $session_data = $this->session->userdata('logged_in');
            $username = $session_data['username'];
            $token = $this->user->getFoursquareToken($username);

            $url = "https://api.foursquare.com/v2/users/self/checkins?oauth_token=".$token."&limit=10&sort=newestfirst";
            $json = file_get_contents($url);
            $result = json_decode($json, true);
            $checkins = $result['response']['checkins']['items'];

            $checkinData = array();
            foreach ($checkins as $c)
            {
                array_push($checkinData, array('venue' => $c['venue']['name'], 'location' => $c['venue']['location']['lat'].', '.$c['venue']['location']['lng'], 'createdAt' => $c['createdAt']));
            }

            return $checkinData;
        }

}

?>