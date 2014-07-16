<?php
namespace Source\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Source\Model\SourceModel;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class SourceController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */

	protected $SourceTable;

	public function getsourcepath($path)
	{
		$uploads_dir = $_SERVER["DOCUMENT_ROOT"].'/writewell_production/public/metadata/sources/';



        print_r("result");
		print_r(move_uploaded_file($path['tmp_name'], $uploads_dir));
		die();
	}



	protected function getSourceTable() {
		if (! $this->SourceTable) {
			$sm = $this->getServiceLocator ();
			$this->SourceTable = $sm->get ( 'Source\Model\SourceTable' );
		}
		return $this->SourceTable;
	}

    public function getList($projectid)
    {



		$sourcetable=$this->getSourceTable();
    	$result=$sourcetable->getProjectSourcesList($projectid);


    	$json_result=array();
    	foreach ($result as $i)
    	 array_push($json_result ,get_object_vars($i));
    	 return $json_result;

    }

    public function get($source_id)
    {
    	$sourcetable=$this->getSourceTable();
    	$temp=$sourcetable->getUserSource((int)$source_id);
    	if (is_int($temp) &&$temp==0)

    		return  array();


    	return get_object_vars($temp);
    }

    public function create($object)
    {


    	//$object=json_decode($attrs,true);

    	$data['title']=$object['title'];
    	$data['project_id']=$object['project_id'];
    	$data['type']=$object['type'];
    	$data["sourcepath"]=$object['sourcepath'];

    	$sourcetable=$this->getSourceTable();
		$section=new SourceModel();
		$section->exchangeArray($data);

    	$id=$sourcetable->createSource($section);
    	$object["id"]=$id;

    	return $object;// array('id'=>1,'name'=>'ram') ;

    }

    public function update($source_id, $data)
    {

    	$object=json_decode($data,true);
    	$object=array_slice($object, 1);

    	$sourcetable=$this->getSourceTable();
    	$sourcetable->updatSource($source_id, $object);

    }

    public function delete($id)
    {
    	$sourcetable=$this->getSourceTable();
    	$sourcetable->deleteSource($id);
    }

    public function indexAction()
    {
        $objectid=0;

    	if(isset($_GET['id']))

    		$objectid = $_GET['id'];




        switch ($_SERVER['REQUEST_METHOD']) {

            case 'GET':


                if ($objectid == 0)
                {
                    $response = $this->getList($_GET['project_id']);

                }
                else
                    $response = $this->get($objectid);

                break;

            case 'POST':


            	$uploads_dir = $_SERVER["DOCUMENT_ROOT"].'/writewell_production/public/metadata/sources/';

            	$path=$uploads_dir.$_FILES['filesource']['name'];
			    move_uploaded_file($_FILES['filesource']['tmp_name'],$path);

            	$object['type']=$_POST['type'];
            	$object['project_id']=$_POST['project_id'];
            	$object['title']=$_FILES['filesource']['name'];


            	if($object['type']=='link')
            	{
            		$path=$_POST['sourcepath'];
            	}

            	$object['sourcepath']=$path;





                $response = $this->create($object);


                break;

            case 'PUT':

                $response = $this->update($objectid, $this->getRequest()
                   ->getContent());
                break;

            case 'DELETE':

                $response = $this->delete($objectid);
                break;


        }

         echo json_encode($response);
        exit();


    }
}