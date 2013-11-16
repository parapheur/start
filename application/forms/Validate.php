<?php

/*
 * File : Validate.php
* Author : Hina Tufail
* Created : 16/11/2013
* Modified : 16/11/2013
* 1.1 :  Hina Tufail - creation and modification
* Form that permits to validate a file
*
* Projet parapheur 2014
*/

class Application_Form_Validate extends Zend_Form
{

    public function init()
    {
    	// HTTP method of sending form : Post
    	$this->setAttrib('enctype', 'multipart/form-data');
    	$this->setMethod('post');
    	
    	// Submit validation button
    	$this->addElement('submit', 'valider', array(
    			'ignore'   => true,
    			'label'    => 'Valider',
    			'class'		=> 'green-button on-right'
    	
    	));
    	
    }


}

