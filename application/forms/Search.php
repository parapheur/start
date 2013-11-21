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

		 
	}
}