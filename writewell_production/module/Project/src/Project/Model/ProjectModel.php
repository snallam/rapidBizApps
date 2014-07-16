<?php
namespace Project\Model;

 // Add these import statements
 use Zend\InputFilter\Factory as InputFactory;
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 class ProjectModel implements InputFilterAwareInterface
 {
 	 public $title;
 	 public $created;
 	 public $project_id;
 	 public $user_id;




	 public function exchangeArray($data)
	 {
	 	$this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
	 	$this->project_id = (isset($data['project_id'])) ? $data['project_id'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->created = (isset($data['created'])) ? $data['created'] : null;


	 }

	 public function getArrayCopy()
	 {
	 	return get_object_vars($this);
	 }

		 // Add content to these methods:
		 public function setInputFilter(InputFilterInterface $inputFilter)
		 {
			 throw new \Exception("Not used");
			 }

			 public function getInputFilter() {

			 }
	}
