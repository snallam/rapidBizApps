<?php

namespace Createdoc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Google_Client;
use Google_Service_Drive;
use authdata;
use Google_Service_Drive_DriveFile;




class CreatedocController extends AbstractActionController {


	/** @var string File Name */
	private $file;

	/** @var string File Content/Data */
	private $content;

	/** @var string File Mime/Type */
	private $type;

	/** @var object File Stream */
	private $f_conn;

	/**
	 * @desc Object Constructor
	 * @param string File Name
	 * @param string File Content
	 * @param string File Mime/Type
	 */


	/**
	 * The default action - show the home page
	 */
	private function write_file() {
		$this->f_conn = @fopen($this->file,'w+');
		@fputs($this->f_conn,$this->content,strlen($this->content));
		@fclose($this->f_conn);
		return;
	}


	/** @desc prompt the user to download the $file */
	private function download_file() {
		header("Content-type: ".$this->type);
		header("Content-Disposition: attachment; filename=".$this->file);
		header("Content-Length: ".filesize($this->file));
		@readfile($this->file);
	}
	public function test() {
		$file['content'] =
			"<html>
    			<head>
        			<title>example word document generated by php</title>
    			</head>
    			<body>
        			<h2>Generating a Microsoft Word Document 'on-the-fly', by Sean O'Donnell</h2>
        			<p>This is a basic example of <i>how to generate a Microsoft Word Document <i>, on-the-fly!</p>
        			<p>Pretty simple eh? =p</p>
    			</body>
    		</html>";

		/* $this->file = "example.doc";
		$this->content = $file['content'];
		$this->type = "application/word";
		$this->write_file(); */

		$this->f_conn = fopen($_SERVER["DOCUMENT_ROOT"]."/writewell_production/public/docs/exam.docx",'w+');
		fputs($this->f_conn,$file['content'],strlen($file['content']));
		fclose($this->f_conn);




	}




	public function ExportAction() {


		$this->test();

		$client_id = '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com';
		$client_secret = 'Fquq4SA_uqnUtgk7OdBY8zjb';

		$client = new Google_Client ();

		$client->setClientId ( $client_id );
		$client->setClientSecret ( $client_secret );
		$scopes = array (
				"https://www.googleapis.com/auth/drive",
				"https://www.googleapis.com/auth/userinfo.email",
				"https://www.googleapis.com/auth/userinfo.profile"
		);
		$client->setScopes ( $scopes );

		$authdata_client = $this->getRequest ()->getContent ();

		if (! $authdata_client) {
			$writewellcred = '{"access_token":"ya29.QAAp17QEHqQLpBkAAADHPfjGp_jZ7F5qT4DU-CbEAbKQtes5CRe1vaPzeP-yFQ","token_type":"Bearer","expires_in":3600,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6IjIwNWJkZDJkYzAxZDM5MTE3MWFiZmZjZDZmOTE3ZmU2Njk3ODYxNmYifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiaWQiOiIxMTE5NzU5OTg4MzYxODg3MzYwODUiLCJzdWIiOiIxMTE5NzU5OTg4MzYxODg3MzYwODUiLCJhenAiOiIyMjAzMDEyOTcyNjItNmVjaHFzZWhiNWdwOG5uMG9pMmtlZjJhOWZmaHU1MnAuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJlbWFpbCI6InNuYWxsYW1AcmFwaWRiaXphcHBzLmNvbSIsImF0X2hhc2giOiI4d1lOemE4TzJxMkYtdzBwalBxWDZnIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF1ZCI6IjIyMDMwMTI5NzI2Mi02ZWNocXNlaGI1Z3A4bm4wb2kya2VmMmE5ZmZodTUycC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImhkIjoicmFwaWRiaXphcHBzLmNvbSIsInRva2VuX2hhc2giOiI4d1lOemE4TzJxMkYtdzBwalBxWDZnIiwidmVyaWZpZWRfZW1haWwiOnRydWUsImNpZCI6IjIyMDMwMTI5NzI2Mi02ZWNocXNlaGI1Z3A4bm4wb2kya2VmMmE5ZmZodTUycC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImlhdCI6MTQwNTEzNTYwOSwiZXhwIjoxNDA1MTM5NTA5fQ.XHVje2uPswJYw19qsf-2OPOl7s0GuZUzQas20fOePmwqosz5qp4rjj23BAIukjJvkB4LLpCGeMOmLPmNQeTn5FoVHsKRX3GGVfnaR1Jb1Wn5by6xnedttFx4NbRJKqX5qKpJra6dhcjtlpepL9YZ9oSXq6fwQAvENlRwgxy4lcY","refresh_token":"1\/nl0IPF0N3jmgR0xL9GMH9z-sdkOL9rYsLcAKyekhJkU","created":1405135909}';
			$client->setAccessToken ( $writewellcred );
			if ($client->isAccessTokenExpired ()) {
				$temp = json_decode ( $writewellcred );
				$client->refreshToken ( $temp->refresh_token );
			}
		} else
			$client->setAccessToken ( $authdata_client );

		$service = new \Google_Service_Drive ( $client );
		$id = $this->putcontent ( $service );

		print_r ( $id );
		die ();

		echo "1";
		exit ();
	}

	public function putcontenttodrive($service) {
		$file = new \Google_Service_Drive_DriveFile ();



		$result = $service->files->insert ( $file, array (
				'data' => file_get_contents ( $_SERVER ["DOCUMENT_ROOT"] . "/writewell_production/public/docs/example.docx" ),
				'mimeType' => 'text/html',
				'uploadType' => 'media',
				'convert' => 'true'
		) );

		return $result ['id'];
	}
}