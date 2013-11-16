<?php

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

