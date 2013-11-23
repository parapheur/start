<?php

class Application_Form_Refuse extends Zend_Form
{

    public function init()
    {
        // HTTP method of sending form : Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		
		// Content of comment
		$this->addElement('textarea', 'text_commentaire_refuse', array(
				'class'		 => 'on-right',
				'required'   => false,
				'attribs' =>   array(
						'id'=>'type_id',
				)
		));
		
		// Submit comment button
		$this->addElement('submit', 'Refuser', array(
				'ignore'   => true,
				'label'    => 'Refuser',
				'class'		=> 'red-button on-right'
   
		));
    }


}

