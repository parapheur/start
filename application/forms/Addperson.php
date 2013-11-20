<?php

/*
 * File : Addperson.php
* Author : Mathilde de l'Hermuzière
* Created : 20/11/2013
* 1.1 :  Mathilde de l'Hermuzière - creation and modification
* Form that permits to add a person to the file workflow
* 
* Projet parapheur 2014
*/

class Application_Form_Addperson extends Zend_Form
{
	public function init()
	{
		// HTTP method of sending form : Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		// L'identifiant de la personne que l'on veut ajouter
		$this->addElement('text', 'id_person', array(
				'label'      => 'ID Nouveau destinataire',
				'required'   => true,
		));
		
		// Type of link
		$this->addElement('select', 'type', array(
				//'class'		 => 'on-right',
				'label'		=> 'Type de lien',
				'required'   => true,
				'attribs' =>   array(
						'id'=>'type_id',
				),
				'multioptions'   => array(
						'1' => 'Lien 1',
						'2' => 'Lien 2',
				),
		));
		
		// IR Auteur
		$this->addElement('textarea', 'IRauteur', array(
				'label'		=>	'IR Auteur',
				//'class'		 => 'on-right',
				'required'   => true
		));
		
		// Submit comment button
		$this->addElement('submit', 'ajouter', array(
				'ignore'   => true,
				'label'    => 'Ajouter',
				'class'		=> 'blue-button on-right'
   
		));
		 
	}
}