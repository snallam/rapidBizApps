<?php

namespace Source\Model;

use Zend\Db\TableGateway\TableGateway;
use Source\Model\SourceModel;
use Zend\Db\Sql\Select;

class SourceTable {
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}

	public function getProjectSourcesList($id) {
		$project_id = ( int ) $id;
		$resultSet = $this->tableGateway->select ( array (
				'project_id' => $project_id
		) );

		return $resultSet;
	}

	public function getUserSource($source_id) {

		$source_id=(int)$source_id;

		$rowSet = $this->tableGateway->select ( array (
				 'source_id'=>$source_id
		) );

		return $rowSet->current();
	}

	public function createSource(SourceModel $sec) {


		$data = array (
				'title' => $sec->title,
				'project_id' => $sec->project_id,
				'sourcepath' => $sec->sourcepath,
				'type' => $sec->type,


		);
		$this->tableGateway->insert ( $data );
		$id = $this->tableGateway->lastInsertValue;
			 	return $id;
	}

	public function updatSource($id,$data)
	{


		$this->tableGateway->update($data,array('source_id'=>(int)$id));
	}
	public function deleteSource($id)
	{
		$this->tableGateway->delete(array('source_id' => (int)$id));
	}


}