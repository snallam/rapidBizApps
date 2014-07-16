<?php

namespace Note\Model;

use Zend\Db\TableGateway\TableGateway;
use Note\Model\NoteModel;
use Zend\Db\Sql\Select;

class NoteTable {
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}

	public function getProjectNotesList($id) {
		$project_id = ( int ) $id;
		$resultSet = $this->tableGateway->select ( array (
				'project_id' => $project_id
		) );

		return $resultSet;
	}

	public function getUserNote($note_id) {

		$note_id=(int)$note_id;

		$rowSet = $this->tableGateway->select ( array (
				 'note_id'=>$note_id
		) );

		return $rowSet->current();
	}

	public function createNote(NoteModel $sec) {


		$data = array (
				'title' => $sec->title,
				'description' => $sec->description,
				'project_id' => $sec->project_id,
		);
		$this->tableGateway->insert ( $data );
		$id = $this->tableGateway->lastInsertValue;
			 	return $id;
	}

	public function updatNote($id,$data)
	{


		$this->tableGateway->update($data,array('note_id'=>(int)$id));
	}
	public function deleteNote($id)
	{
		$this->tableGateway->delete(array('note_id' => (int)$id));
	}


}