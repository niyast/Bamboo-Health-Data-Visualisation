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
        $client->setDeveloperKey('AIzaSyDd_2BgihU9RfR2nZDm5DfOIlNB78nSN5U'); // Developer key
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

    public function getDatacsv() {
        header('Content-type: application/json');

// Set your CSV feed
//$feed = 'https://docs.google.com/a/ms101.me/spreadsheet/pub?key=0Ast_cj5gE1aLdHJYYzRZMlk4WExPUl91YnJyN1dzelE&single=true&gid=0&output=csv';
//$feed = 'https://docs.google.com/spreadsheet/pub?hl=en_US&hl=en_US&key=0Akse3y5kCOR8dEh6cWRYWDVlWmN0TEdfRkZ3dkkzdGc&single=true&gid=0&output=csv';
//$feed = 'https://docs.google.com/spreadsheets/d/1cn3xQJeX4kp6_aSo-H1IH18QZG5Y8ZV4OA6s-aEHf_8/pubhtml';
//  Arrays we'll use later
        
        $keys = array();
        $newArray = array();
        $feed = base_url() . 'public/BambooData.csv';
        
// Function to convert CSV into associative array
// Do it
        //$data = csvToArray($feed, ',');
        if (($handle = fopen($feed, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 8000, ',', '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        $data = $arr;
        
//        print_r($data);exit;
        
// Set number of elements (minus 1 because we shift off the first row)
        $count = count($data) - 1;

//Use first row for names  
        $labels = array_shift($data);

        foreach ($labels as $label) {
            $keys[] = $label;
        }

// Add Ids, just in case we want them later
        $keys[] = 'id';
//print_r($keys);exit;
        for ($i = 0; $i < $count; $i++) {
            $data[$i][] = $i;
        }
//echo count($keys) . '-'. count($data) .'-'. $count ;exit;
// Bring it all together
        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $data[$j]);
            $newArray[$j] = $d;
        }

// Print it out as JSON
        echo json_encode($newArray);
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
