<?php
namespace Project\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Project\Model\ProjectModel;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class ProjectController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */

	protected $ProjectTable;

	protected function getProjectTable() {
		if (! $this->ProjectTable) {
			$sm = $this->getServiceLocator ();
			$this->ProjectTable = $sm->get ( 'Project\Model\ProjectTable' );
		}
		return $this->ProjectTable;
	}

    public function getList()
    {
    	$user_session = new Container('user');
    	$user_session->user_id=123;

		$projecttable=$this->getProjectTable();
    	$result=$projecttable->getUserProjectList($user_session->user_id);

    	$json_result=array();
    	foreach ($result as $i)
    	 array_push($json_result ,get_object_vars($i));
    	 return $json_result;

    }

    public function get($project_id)
    {
    	$projecttable=$this->getProjectTable();
    	$temp=$projecttable->getUserProject((int)$project_id);

    	if (is_int($temp) &&$temp==0)

    		return  array();

    	  return get_object_vars($temp);

    }

    public function create($attrs)
    {
    	$user_session = new Container('user');


    	$object=json_decode($attrs,true);

    	$data['title']=$object['title'];
    	$data['created']=$object['created'];
    	$data['user_id']=(int)$user_session->user_id;

    	$projecttable=$this->getProjectTable();
		$project=new ProjectModel();

    	$project->exchangeArray($data);
    	$id=$projecttable->createProject($project);
    	$object["id"]=$id;

    	return $object;// array('id'=>1,'name'=>'ram') ;

    }

    public function update($project_id, $data)
    {

    	$object=json_decode($data,true);
    	$object=array_slice($object, 1);

    	$projecttable=$this->getProjectTable();
    	$projecttable->updateProject($project_id,$object);
    }

    public function delete($id)
    {
    	$projecttable=$this->getProjectTable();
    	$projecttable->deleteProject($id);
    }

    public function indexAction()
    {

    	$objectid=0;

    	if(isset($_GET['id']))

    		$objectid = $_GET['id'];


        $user_session = new Container('user');
        $user_session->user_id='123';

        switch ($_SERVER['REQUEST_METHOD']) {

            case 'GET':


                if ($objectid == 0)
                {
                    $response = $this->getList();

                }
                else
                    $response = $this->get($objectid);

                break;

            case 'POST':

                $response = $this->create($this->getRequest()
                    ->getContent());


                break;

            case 'PUT':


                $response = $this->update($objectid, $this->getRequest()
                   ->getContent() );
                break;

            case 'DELETE':

                $response = $this->delete($objectid);
                break;


        }

         echo json_encode($response);
        exit();


    }
}