<?php
namespace Signup\Model;

 // Add these import statements
 use Zend\InputFilter\Factory as InputFactory;
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 class UsersignupModel implements InputFilterAwareInterface
 {
 	 public $name;
 	 public $user_id;
 	 public $gender;
 	 public $occupation;
 	 public $age;


	 protected $inputFilter; // <-- Add this variable

	 public function exchangeArray($data)
	 {
		 $this->name = (isset($data['name'])) ? $data['name'] : null;
		 $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
		 $this->gender = (isset($data['gender'])) ? $data['gender'] : null;
		 $this->occupation = (isset($data['occupation'])) ? $data['occupation'] : null;
		 $this->age = (isset($data['age'])) ? $data['age'] : null;

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
