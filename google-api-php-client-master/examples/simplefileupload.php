<script src="http://code.jquery.com/jquery-1.9.1.js"
        type="text/javascript"></script>
<script type="text/javascript">

    function postContactToGoogle(){

            $j.ajax({
                url: "https://docs.google.com/yourFormURL/formResponse",
                data: {"fileId" :'1xogePr0lWoI4fmzQsNswu__OANw_8-WklPiM71EVqMY' , "data" : "sdfadsf"},
                type: "POST",
                dataType: "json"

            });

    }
</script>


<?php


/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
include_once "templates/base.php";
session_start();
set_include_path("../src/" . PATH_SEPARATOR . get_include_path());
require_once 'Google/Client.php';
require_once 'Google/Http/MediaFileUpload.php';
require_once 'Google/Service/Drive.php';





/************************************************
  We'll setup an empty 1MB file to upload.
/* ************************************************/
/*DEFINE("TESTFILE", 'Accolite_Resume.docx');
if (true || !file_exists(TESTFILE)) {
  $fh = fopen(TESTFILE, 'w');
  $word = new COM("word.application");

 fwrite($fh,$_POST['data']);


  fclose($fh);
}*/

/*print_r(file_get_contents("Accolite_Resume.docx"));
die();*/
/************************************************
  ATTENTION: Fill in these values! Make sure
  the redirect URI is to this page, e.g:
  http://localhost:8080/fileupload.php
 ************************************************/
$client_id = '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com';
$client_secret = 'Fquq4SA_uqnUtgk7OdBY8zjb';
$redirect_uri = 'http://localhost/google-api-php-client-master/examples/simplefileupload.php';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$client->setAccessType('offline');
$service = new Google_Service_Drive($client);

if (isset($_REQUEST['logout'])) {
 // unset($_SESSION['upload_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['upload_token'] = $client->getAccessToken();

  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['upload_token']) && $_SESSION['upload_token']) {
  $client->setAccessToken($_SESSION['upload_token']);

  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
  }
} else {
  $authUrl = $client->createAuthUrl();
}

/************************************************
  If we're signed in then lets try to upload our
  file. For larger files, see fileupload.php.
 ************************************************/
if (!$client->getAccessToken()) {
  // This is uploading a file directly, with no metadata associated.

    if(isset($_SESSION['file']))
    {
        $file_up = $service->files->get($_SESSION['file']);



        // File's new content.
        $data = $_POST['data'];

        $additionalParams = array(

            'data' =>  $data,
            'mimeType' => 'text/html',
            'uploadType' => 'media',
            'convert'=>'true'

        );

        // Send the request to the API.
        $updatedFile = $service->files->update($_SESSION['file'], $file_up,$additionalParams);


    }

  $file = new Google_Service_Drive_DriveFile();


    $temp=$_POST["data"];


  $result = $service->files->insert(
      $file,
      array(
       //'data' => file_get_contents('Accolite_Resume.docx'),
        //  'mimeType' => 'application/msword',
          'data' =>  'asdfasnflkaf',
          'mimeType' => 'text/html',
          'uploadType' => 'media',
          'convert'=>'true'
      )
  );
    $_SESSION['file']=$result['id'];





  /*// Now lets try and send the metadata as well using multipart!
  $file = new Google_Service_Drive_DriveFile();
  $file->setTitle("test gdocs");
  $result2 = $service->files->insert(
      $file,
      array(
        'data' => file_get_contents(TESTFILE),
        'mimeType' => 'application/octet-stream',
        'uploadType' => 'multipart'
      )
  );*/
}

echo pageHeader("File Upload - Uploading a small file");
if (
    $client_id == '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com'
    || $client_secret == 'Fquq4SA_uqnUtgk7OdBY8zjb'
    || $redirect_uri == 'http://localhost/google-api-php-client-master/examples/simplefileupload.php') {
  echo missingClientSecretsWarning();
}
?>
<div class="box">
  <div class="request">
    <?php if (isset($authUrl)): ?>
      <a class='login' href='<?php echo $authUrl; ?>'>Connect Me!</a>
    <?php endif; ?>
  </div>

  <?php if (isset($result) && $result): ?>
    <div class="shortened">
      <?php var_dump($result->title); ?>

    </div>
  <?php endif ?>

    <input type="button" onclick="postContactToGoogle()">
</div>
<?php
echo pageFooter(__FILE__);
