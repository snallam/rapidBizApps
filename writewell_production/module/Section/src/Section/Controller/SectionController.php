<?php
namespace Section\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Section\Model\SectionModel;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class SectionController extends AbstractActionController
{

    /**
     * The default action - show the home page
     */

	protected $SectionTable;

	protected function getSectionTable() {
		if (! $this->SectionTable) {
			$sm = $this->getServiceLocator ();
			$this->SectionTable = $sm->get ( 'Section\Model\SectionTable' );
		}
		return $this->SectionTable;
	}

    public function getList($projectid)
    {



		$sectiontable=$this->getSectionTable();
    	$result=$sectiontable->getProjectSectionsList($projectid);

    	$json_result=array();
    	foreach ($result as $i)
    	 array_push($json_result ,get_object_vars($i));
    	 return $json_result;

    }

    public function get($section_id)
    {
    	$sectiontable=$this->getSectionTable();
    	$temp=$sectiontable->getUserSection((int)$section_id);
    	if (is_int($temp) &&$temp==0)

    		return  array();

    	return get_object_vars($temp);
    }

    public function create($attrs)
    {


    	$object=json_decode($attrs,true);

    	$data['title']=$object['title'];
    	$data['order_id']=$object['orderId'];
    	$data['description']=$object['description'];
    	$data['project_id']=$object['project_id'];

    	$sectiontable=$this->getSectionTable();
		$section=new SectionModel();

    	$section->exchangeArray($data);
    	$id=$sectiontable->createSection($section);
    	$object["id"]=$id;

    	return $object;// array('id'=>1,'name'=>'ram') ;

    }

    public function update($section_id, $data)
    {

    	$object=json_decode($data,true);
    	$object=array_slice($object, 1);

    	$sectiontable=$this->getSectionTable();
    	$sectiontable->updatSection($section_id, $object);

    }

    public function delete($id)
    {
    	$sectiontable=$this->getSectionTable();
    	$sectiontable->deleteSection($id);
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