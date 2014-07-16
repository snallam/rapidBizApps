<?php
namespace Note\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Note\Model\NoteModel;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class NoteController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */

	protected $NoteTable;

	protected function getNoteTable() {
		if (! $this->NoteTable) {
			$sm = $this->getServiceLocator ();
			$this->NoteTable = $sm->get ( 'Note\Model\NoteTable' );
		}
		return $this->NoteTable;
	}

    public function getList($projectid)
    {



		$notetable=$this->getNoteTable();
    	$result=$notetable->getProjectNotesList($projectid);


    	$json_result=array();
    	foreach ($result as $i)
    	 array_push($json_result ,get_object_vars($i));
    	 return $json_result;

    }

    public function get($note_id)
    {
    	$notetable=$this->getNoteTable();
    	$temp=$notetable->getUserNote((int)$note_id);
    	if (is_int($temp) &&$temp==0)

    		return  array();


    	return get_object_vars($temp);
    }

    public function create($attrs)
    {


    	$object=json_decode($attrs,true);

    	$data['title']=$object['title'];

    	$data['description']=$object['description'];
    	$data['project_id']=$object['project_id'];
    	print_r($data);

    	$notetable=$this->getNoteTable();
		$section=new NoteModel();

    	$section->exchangeArray($object);

    	$id=$notetable->createNote($section);
    	$object["id"]=$id;

    	return $object;// array('id'=>1,'name'=>'ram') ;

    }

    public function update($note_id, $data)
    {

    	$object=json_decode($data,true);
    	$object=array_slice($object, 1);

    	$notetable=$this->getNoteTable();
    	$notetable->updatNote($note_id, $object);

    }

    public function delete($id)
    {
    	$notetable=$this->getNoteTable();
    	$notetable->deleteNote($id);
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

                $response = $this->create($this->getRequest()
                    ->getContent());


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