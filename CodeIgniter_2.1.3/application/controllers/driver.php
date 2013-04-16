<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Driver extends CI_Controller {

	function __construct()
 	{
		parent::__construct();
 	}

	function index()
	{
	}

        function listEsls()
        {
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];

            $this->load->model('user');
            $data['esls'] = $this->user->getUserEsls($data['username']);

            $this->load->view('templates/header');
            $this->load->view('list_esls', $data);
            $this->load->view('templates/footer');
        }

        function listBids()
        {
            $session_data = $this->session->userdata('logged_in');
            $data['username'] = $session_data['username'];

            $this->load->model('user');
            $data['bids'] = $this->user->getUserBids($data['username']);

            $this->load->view('templates/header');
            $this->load->view('list_bids', $data);
            $this->load->view('templates/footer');
        }

        function foursquareAuth()
        {
            redirect('https://foursquare.com/oauth2/authenticate?client_id=KGQB13TVIW2RYWWAKYA2UB0VCEHQ4C3K2QDEKSGMYFJIC3VS&response_type=code&redirect_uri=https://students.cs.byu.edu/~kdevenis/proj1/proj1/CodeIgniter_2.1.3/index.php/driver/code');

            /*$fields_str = "client_id=KGQB13TVIW2RYWWAKYA2UB0VCEHQ4C3K2QDEKSGMYFJIC3VS&return_type=code&redirect_uri=https://students.cs.byu.edu/~kdevenis/proj1/proj1/CodeIgniter_2.1.3/index.php/driver/code";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://foursquare.com/oauth2/authenticate");
                curl_setopt($ch, CURLOPT_POST, 3);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_str);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                curl_close($ch);*/
        }

        function code()
        {
            $code = $_GET['code'];

            /*$fields_str = "client_id=KGQB13TVIW2RYWWAKYA2UB0VCEHQ4C3K2QDEKSGMYFJIC3VS&client_secret=V5C2DSS0GTSTBSNFONGWKH0K0XMRGQ3BRF3WXD0KCKWSF1NR&grant_type=authorization_code&redirect_uri=https://students.cs.byu.edu/~kdevenis/proj1/proj1/CodeIgniter_2.1.3/index.php/driver/token&code=".$code;
            $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://foursquare.com/oauth2/access_token");
                curl_setopt($ch, CURLOPT_POST, 5);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_str);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOP_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($result, true);
                $token = $json['access_token'];
                var_dump($result);*/
                $url = "https://foursquare.com/oauth2/access_token?client_id=KGQB13TVIW2RYWWAKYA2UB0VCEHQ4C3K2QDEKSGMYFJIC3VS&client_secret=V5C2DSS0GTSTBSNFONGWKH0K0XMRGQ3BRF3WXD0KCKWSF1NR&grant_type=authorization_code&redirect_uri=https://students.cs.byu.edu/~kdevenis/proj1/proj1/CodeIgniter_2.1.3/index.php/driver/token&code=".$code;
                $json = file_get_contents($url);
                $result = json_decode($json, true);
                $token = $result['access_token'];

                $this->load->model('user');
                $session_data = $this->session->userdata('logged_in');
                $username = $session_data['username'];
                $this->user->saveFoursquareToken($username, $token);

                $url = "https://api.foursquare.com/v2/users/self?oauth_token=".$token;
                $json = file_get_contents($url);
                $result = json_decode($json, true);
                $userId = $result['response']['user']['id'];
                $this->user->saveFoursquareId($username, $userId);

                $this->load->view('foursquare_success');
        }

        function token()
        {
            $this->load->model('user');
            $session_data = $this->session->userdata('logged_in');
            $username = $session_data['username'];
            $token = $this->user->getFoursquareToken($username);

            $url = "https://api.foursquare.com/v2/users/self/?oauth_token=".$token;
            $json = file_get_contents($url);
            $result = json_decode($json, true);
            log_message("info", 'got token');
            log_message("info", $result);
            $userId = $result['response']['user']['id'];
            $this->user->saveFoursquareId($username, $userId);

            $this->load->view('foursquare_success');
        }

        function updateLocation()
        {
            log_message("info", "checkin");
            $checkin = $this->input->post('checkin');
            $json = json_decode($checkin, true);
            $foursquareId = $json['user']['id'];
            $lat = $json['venue']['location']['lat'];
            $long = $json['venue']['location']['lng'];
log_message("info", "got json");
            $this->load->model('user');
            $username = $this->user->getUserByFoursquareId($foursquareId);
log_message("info", $username);
            $this->user->saveLocation($username, $lat, $long);
        }

        function checkin($username = '')
        {
            $this->load->model('user');
            $token = $this->user->getFoursquareToken($username);

            $url = "https://api.foursquare.com/v2/users/self/checkins?oauth_token=".$token."&limit=10&sort=newestfirst";
            $json = file_get_contents($url);
            $result = json_decode($json, true);
            $data['venue'] = $result['response']['checkins']['items'][0]['venue']['name'];
            $data['user'] = $username;

            $this->load->view('templates/header');
            $this->load->view('checkin', $data);
            $this->load->view('templates/footer');
        }
}

?>