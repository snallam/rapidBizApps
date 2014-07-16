<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Signup\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Signin\Model\UserloginModel;
use Signup\Model\UsersignupModel;
use Google_Client;
use Google_Service_Plus;





class SignupController extends AbstractActionController {


	protected $userloginTable;
	protected $usersignupTable;

	protected function getUserloginTable() {
		if (! $this->userloginTable) {
			$sm = $this->getServiceLocator ();
			$this->userloginTable = $sm->get ( 'Signin\Model\UserloginTable' );
		}
		return $this->userloginTable;
	}

	protected function getUsersignupTable() {
		if (! $this->usersignupTable) {
			$sm = $this->getServiceLocator ();
			$this->usersignupTable = $sm->get ( 'Signup\Model\UsersignupTable' );
		}
		return $this->usersignupTable;
	}



	public function indexAction() {


		$user_signup_details=json_decode($this->getRequest()->getContent());

		$data['email']=$user_signup_details->email;
		$data['password']=$user_signup_details->password;

		$user=$this->getUserloginTable();
		$userreg=new UserloginModel();
		$userreg->exchangeArray($data);
		$id=$user->savenewuser($userreg);

		if($id==0)
		{
			print_r( json_encode(array('status'=>0,'msg'=>'email already registered')) );
			die();
		}


		$signup_data['user_id']=$id;
		$signup_data['age']=$user_signup_details->age;
		$signup_data['name']=$user_signup_details->name;
		$signup_data['occupation']=$user_signup_details->occupation;
		$signup_data['gender']=$user_signup_details->gender;


		$user=$this->getUsersignupTable();

		$userreg=new UsersignupModel();
		$userreg->exchangeArray($signup_data);
		$user->savenewuserdetails($userreg);

		print_r( json_encode(array('status'=>1,'id'=>$id)) );


		die();


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
}
