<?php

/*
 * Fichier : Comment.php
* Auteur : Mathilde de l'Hermuzière
* Créé : 17/11/2013
* Modifié : 17/11/2013
* 1.1 :  Mathilde de l'Hermuzière - création et modification
* Formulaire de commentaire dans un document 
* 
* Projet parapheur 2014
*/

class Application_Form_Comment extends Zend_Form
{
	public function init()
	{
		// HTTP méthode: Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		// Type du commentaire
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

		// Contenu du commentaire
		$this->addElement('textarea', 'text_commentaire', array(
				'class'		 => 'on-right',
				'required'   => true,
				'attribs' =>   array(
						'id'=>'type_id',
				),
				'multioptions'   => array(
						'projet' => 'Projet',
						'document' => 'Document',
				),
		));
		
		// Soumettre le formulaire - bouton
		$this->addElement('submit', 'commenter', array(
				'ignore'   => true,
				'label'    => 'Commenter',
				'class'		=> 'blue-button on-right'
   
		));
		 
	}
}