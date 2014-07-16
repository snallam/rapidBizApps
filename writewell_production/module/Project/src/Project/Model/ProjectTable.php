<?php

namespace Project\Model;

use Zend\Db\TableGateway\TableGateway;
use Project\Model\ProjectModel;
use Zend\Db\Sql\Select;

class ProjectTable {
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}

	public function getUserProjectList($id) {
		$user_id = ( int ) $id;
		$resultSet = $this->tableGateway->select ( array (
				'user_id' => $id
		) );

		return $resultSet;
	}

	public function getUserProject($project_id) {

		$project_id=(int)$project_id;

		$rowSet = $this->tableGateway->select ( array (
				 'project_id'=>$project_id
		) );

		return $rowSet->current();
	}

	public function createProject(ProjectModel $proj) {
		$data = array (
				'title' => $proj->title,
				'created' => $proj->created,
				'user_id' => $proj->user_id
		);
		$this->tableGateway->insert ( $data );
		$id = $this->tableGateway->lastInsertValue;
			 	return $id;
	}

	public function updateProject($id,$data)
	{

	  $this->tableGateway->update($data,array('project_id'=>(int)$id));
	}

	public function deleteProject($id)
	{
		$this->tableGateway->delete(array('project_id' => (int)$id));
	}


}