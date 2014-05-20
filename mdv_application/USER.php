<?php
 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
     
class USER extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('loguser', '', TRUE);
    }
 
    public function index() {     
		//include_once "templates/base.php";

		//set_include_path("../src/" . PATH_SEPARATOR . get_include_path());
		
		/*
		require_once 'Google/Client.php';
		require_once 'Google/Service/Urlshortener.php';
		require_once 'Google/Service/Books.php';
		require_once 'Google/Service/Drive.php';
		*/

        require_once 'public/src/Google_Client.php';
        require_once 'public/src/contrib/Google_PlusService.php';
        require_once 'public/src/contrib/Google_Oauth2Service.php';
        require_once 'public/src/contrib/Google_DriveService.php';

		session_start();

        
		/************************************************
		  ATTENTION: Fill in these values! Make sure
		  the redirect URI is to this page, e.g:
		  http://localhost:8080/user-example.php
		 ************************************************/
		 $client_id = '273415013981-8oqv5e4gmi4jbrj4afhj7146s6fl15pu.apps.googleusercontent.com';
		 $client_secret = 'cOScijBSLVOJ1MXbbr6F46c8';
		 $redirect_uri = 'http://localhost/googleapiclient/examples/user-example.php';
		 $apiKey = "AIzaSyBGsoVQTcz6XOdwUxgKpQawbJV9_Q3sBQ4";

		/************************************************
		  Make an API request on behalf of a user. In
		  this case we need to have a valid OAuth 2.0
		  token for the user, so we need to send them
		  through a login flow. To do this we need some
		  information from our API console project.
		 ************************************************/
		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setScopes(array('https://www.googleapis.com/auth/drive.readonly','https://www.googleapis.com/auth/plus.me','https://www.googleapis.com/auth/userinfo.email')); // set scope during user login
		$client->setDeveloperKey($apiKey);

		/************************************************
		  When we create the service here, we pass the
		  client to it. The client then queries the service
		  for the required scopes, and uses that when
		  generating the authentication URL later.
		 ************************************************/
		$service = new Google_Service_Drive($client);

		/************************************************
		  If we're logging out we just need to clear our
		  local access token in this case
		 ************************************************/
		if (isset($_REQUEST['logout'])) {
		  unset($_SESSION['access_token']);
		}

		/************************************************
		  If we have a code back from the OAuth 2.0 flow,
		  we need to exchange that with the authenticate()
		  function. We store the resultant access token
		  bundle in the session, and redirect to ourself.
		 ************************************************/
		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}

		/************************************************
		  If we have an access token, we can make
		  requests, else we generate an authentication URL.
		 ************************************************/
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  $client->setAccessToken($_SESSION['access_token']);
		} else {
		  $authUrl = $client->createAuthUrl();
		}

		/************************************************
		  If we're signed in and have a request to shorten
		  a URL, then we create a new URL object, set the
		  unshortened URL, and call the 'insert' method on
		  the 'url' resource. Note that we re-store the
		  access_token bundle, just in case anything
		  changed during the request - the main thing that
		  might happen here is the access token itself is
		  refreshed if the application has offline access.
		 ************************************************/
		if ($client->getAccessToken()) {  		
			  $_SESSION['access_token'] = $client->getAccessToken();
		}
		echo pageHeader("User files");

		if (
			$client_id == '<YOUR_CLIENT_ID>'
			|| $client_secret == '<YOUR_CLIENT_SECRET>'
			|| $redirect_uri == '<YOUR_REDIRECT_URI>') {
		  echo missingClientSecretsWarning();
		}
	}

	public function getDatacsv() {
		  //header('Content-type: application/json');
		  $result = array();
		  $pageToken = NULL;

		  do {
			try {
			  $parameters = array();
			  if ($pageToken) {
				$parameters['pageToken'] = $pageToken;
			  }
			  $files = $service->files->listFiles($parameters);

			  $result = array_merge($result, $files->getItems());
			  $pageToken = $files->getNextPageToken();
			} catch (Exception $e) {
			  print "An error occurred: " . $e->getMessage();
			  $pageToken = NULL;
			}
		  } while ($pageToken);

		 echo 'hello';
		  //echo json_decode($result);        
	}	
	
}
		
?>
<div class="box">
  <div class="request">
    <?php if (isset($authUrl)): ?>
      <a class='login' href='<?php echo $authUrl; ?>'>Connect Me!</a>
    <?php else: ?>
      <a class='logout' href='?logout'>Logout</a>
    <?php endif ?>
  </div>


</div>
