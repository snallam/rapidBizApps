<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Signin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json;
use Signin\Form\Userlogin;
use Signin\Model\UserloginModel;
use Zend\Session\Container;
use Google_Client;
use Google_Service_Plus;




class SigninController extends AbstractActionController {


	protected $userloginTable;

	protected function getUserloginTable() {
		if (! $this->userloginTable) {
			$sm = $this->getServiceLocator ();
			$this->userloginTable = $sm->get ( 'Signin\Model\UserloginTable' );
		}
		return $this->userloginTable;
	}

	public function checkloginAction()
	{
		$details=json_decode($this->getRequest()->getContent());

		$user=$this->getUserloginTable();
		$status=$user->signinUser($details);
		print_r($status);
		die();
	}




	public function indexAction() {
		// TODO: Auto-generated method stub
		$client_id = '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com';
		$client_secret = 'Fquq4SA_uqnUtgk7OdBY8zjb';

		$client = new Google_Client ();
		$client->setClientId ( $client_id );
		$client->setClientSecret ( $client_secret );
		$authdata_client=  $this->getRequest()->getContent();
		$client->setAccessToken ( $authdata_client);


		$scopes=array("https://www.googleapis.com/auth/drive" ,"https://www.googleapis.com/auth/userinfo.email","https://www.googleapis.com/auth/userinfo.profile");
		$client->setScopes($scopes);

		$plus = new \Google_Service_Plus($client);
		$person = $plus->people->get('me');
		print_r($person);
        die();

		echo "1";
        exit();

	}




	public function setauthdata_to_database($client, $token)
	{


		$plus = new \Google_Service_Plus($client);
		$person = $plus->people->get('me');

		$emaillist=$person->getEmails();
		$displayname=$person->getDisplayName();

		$data['user_email']=$emaillist[0]->value;
		$data['user_name']=$displayname;
		$data['user_authcode']=serialize($token);
		$data['access_token']=$token->access_token;
		$data['refresh_token']=$token->refresh_token;

		$user=$this->getUserloginTable();

		$userreg=new UserloginModel();
		$userreg->exchangeArray($data);
	    $user->savenewuser($userreg);

	}



	public function createauthurlAction() {

		$client_id = '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com';
		$client_secret = 'Fquq4SA_uqnUtgk7OdBY8zjb';
		$redirect_uri = 'http://localhost/writewell_production/public/signin/handle';

		$client = new Google_Client ();
		$client->setClientId ( $client_id );
		$client->setClientSecret ( $client_secret );
		$client->setRedirectUri ( $redirect_uri );
		$client->setAccessType ( 'offline' );
		$client->setApprovalPrompt("force");
		$scopes=array("https://www.googleapis.com/auth/drive" ,"https://www.googleapis.com/auth/userinfo.email","https://www.googleapis.com/auth/userinfo.profile");
		$client->setScopes($scopes);

		$data = array("authurl"=>$client->createAuthUrl());

		echo json_encode($data);

		exit();

	}

	public function handleAction() {
		$user_session = new Container('user');
		$user_session->accesstoken="";


		$client_id = '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com';
		$client_secret = 'Fquq4SA_uqnUtgk7OdBY8zjb';
		$redirect_uri = 'http://localhost/writewell_production/public/signin/handle';

		$client = new Google_Client ();
		$client->setClientId ( $client_id );
		$client->setClientSecret ( $client_secret );
		$client->setRedirectUri ( $redirect_uri );
		$client->setAccessType ( 'offline' );
		$client->setApprovalPrompt("force");
		$scopes=array("https://www.googleapis.com/auth/drive" ,"https://www.googleapis.com/auth/userinfo.email","https://www.googleapis.com/auth/userinfo.profile");
		$client->setScopes($scopes);
		/* $client->addScope ( "https://www.googleapis.com/auth/drive" );
		$client->addScope ("https://www.googleapis.com/auth/userinfo.email");
		$client->addScope ("https://www.googleapis.com/auth/userinfo.profile"); */
		$service = new \Google_Service ( $client );

		if (isset ( $_GET ['code'] )) {

			$client->authenticate ( $_GET ['code'] );
			$tokenobject=json_decode($client->getAccessToken ());

			$user_session->accesstoken = $client->getAccessToken ();
			$client->setAccessToken ( $user_session->accesstoken );

			$this->setauthdata_to_database($client,$tokenobject);
			$redirect = 'http://' . $_SERVER ['HTTP_HOST'] .'/writewell_production/public/createdoc/test';

           	header ( 'Location: ' . filter_var ( $redirect, FILTER_SANITIZE_URL ) );
           	exit();
		}


	}


}
