<?php

/*
 * File : Comment.php
* Author : Mathilde de l'Hermuzière
* Created : 17/11/2013
* Modified : 17/11/2013
* 1.1 :  Mathilde de l'Hermuzière - creation and modification
* Form that permits to comment a file 
* 
* Projet parapheur 2014
*/

class Application_Form_Comment extends Zend_Form
{
	public function init()
	{
		// HTTP method of sending form : Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		// Type of comment
		$this->addElement('select', 'type_commentaire', array(
				'class'		 => 'write_comment',
				'required'   => false,
				'attribs' =>   array(
						'id'=>'type_id',
				),
				'multioptions'   => array(
						'1' => 'Projet',
						'2' => 'Document',
				),
		));
		
		// Content of comment
		$this->addElement('textarea', 'text_commentaire', array(
				'class'		 => 'on-right',
				'required'   => false,
				'attribs' =>   array(
						'id'=>'type_id',
				),
				'multioptions'   => array(
						'projet' => 'Projet',
						'document' => 'Document',
				),
		));
		
		// Submit comment button
		$this->addElement('submit', 'commenter', array(
				'ignore'   => true,
				'label'    => 'Commenter',
				'class'		=> 'blue-button on-right'
   
		));
		 
	}
}