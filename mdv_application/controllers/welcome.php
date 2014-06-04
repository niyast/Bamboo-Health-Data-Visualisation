<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('loguser', '', TRUE);
    }


    public function index() {
//            echo base_url();exit;
        require_once 'public/src/Google_Client.php';
        require_once 'public/src/contrib/Google_PlusService.php';
        require_once 'public/src/contrib/Google_Oauth2Service.php';

        session_start();
        $client = new Google_Client();
        $client->setApplicationName("Bamboo Mobile Health data visualization"); // Set your applicatio name
        $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me')); // set scope during user login
        $client->setClientId('604161453489.apps.googleusercontent.com'); // paste the client id which you get from google API Console
        $client->setClientSecret('RX6ImZQgzDgmNLXKym-Of2ex'); // set the client secret
        $client->setRedirectUri('http://www.adoxsolutions.in/projects/mdv/'); // paste the redirect URI where you given in APi Console. You will get the Access Token here during login success
        $client->setDeveloperKey('AIzaSyA67M94yqZxoTWH0UakbslnB0T_bv65L4E'); // Developer key
        $plus = new Google_PlusService($client);
        $oauth2 = new Google_Oauth2Service($client); // Call the OAuth2 class for get email address
        if (isset($_GET['code'])) {
            $client->authenticate(); // Authenticate
            $_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }

        if (isset($_SESSION['access_token'])) {
            $client->setAccessToken($_SESSION['access_token']);
        }

        if ($client->getAccessToken()) {
            $user = $oauth2->userinfo->get();
            $me = $plus->people->get('me');
            $optParams = array('maxResults' => 100);
            $activities = $plus->activities->listActivities('me', 'public', $optParams);
            // The access token may have been updated lazily.
            $_SESSION['access_token'] = $client->getAccessToken();
            $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL); // get the USER EMAIL ADDRESS using OAuth2
        } else {
            $authUrl = $client->createAuthUrl();
        }

        if (isset($me)) {
            $_SESSION['gplusuer'] = $me; // start the session
//            print_r($user); echo $me['emails'][0]['value'];exit;

            $usercheck = $this->loguser->checkUser($user['id']);

            if (empty($usercheck)) {
//echo $user['id'];exit;
                $inputarry['g_user_id'] = $user['id'];
                $inputarry['user_name'] = $user['name'];
                $inputarry['user_email'] = $user['email'];
                if (!empty($user['gender']))
                    $inputarry['user_gender'] = $user['gender'];
                if (!empty($user['picture']))
                    $inputarry['user_image'] = $user['picture'];
                $inputarry['log_time'] = time();

                $new = $this->loguser->addUser($inputarry);
                $data['text'] = 'Hi ' . $user['name'] . '. Thanks For Registering.';
            } else {
                $data['text'] = 'Welocome back ' . $user['name'];
            }
        }
        $data['authUrl'] = isset($authUrl) ? $authUrl : '';
        $data['user'] = isset($user) ? $user : '';
        $data['me'] = isset($me) ? $me : '';
        if (isset($me)) {
            $this->load->view('home', $data);
        } else {
            $this->load->view('login', $data);
        }
    }
    
    public function getUsers() {
        header('Content-type: application/json');

        $type = $_POST['type'];
        $bid = $_POST['bid'];
        $feed = "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getUsers?user_id=$bid";
        $keys = array();
        $newArray = array();

        $cred = sprintf('Authorization: Basic %s', base64_encode("rgeorge@snaplogic.com:bmh@123"));
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => $cred)
        );
        $ctx = stream_context_create($opts);
        $handle = fopen($feed, 'r', false, $ctx);

        $try = stream_get_contents($handle);
//        echo($try);exit;

        echo $try;
    }

    public function getDatacsv() {
        header('Content-type: application/json');


        $type = $_POST['type'];
        $bid = $_POST['bid'];
        $feed = "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getData?id=$bid";
        $keys = array();
        $newArray = array();

        $cred = sprintf('Authorization: Basic %s', base64_encode("rgeorge@snaplogic.com:bmh@123"));
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => $cred)
        );
        $ctx = stream_context_create($opts);
        $handle = fopen($feed, 'r', false, $ctx);

        $try = stream_get_contents($handle);
//        echo($try);exit;

        echo $try;

    }

    public function getDataEnvrmnt() {
        header('Content-type: application/json');

        $type = $_POST['type'];
        $bid = $_POST['bid'];
        $feed = "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getEnvironmentData?id=$bid";
        $keys = array();
        $newArray = array();

        $cred = sprintf('Authorization: Basic %s', base64_encode("rgeorge@snaplogic.com:bmh@123"));
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => $cred)
        );
        $ctx = stream_context_create($opts);
        $handle = fopen($feed, 'r', false, $ctx);

        $try = stream_get_contents($handle);
//        echo($try);exit;

        echo $try;
    }

    public function logout() {
        session_start();
        unset($_SESSION['access_token']);
        unset($_SESSION['gplusuer']);
        session_destroy();
//        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); // it will simply destroy the current seesion which you started before
        redirect('/welcome');
    }

    function csvToArray($file, $delimiter) {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
