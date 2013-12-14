<?php

/*
 * Fichier : Addperson.php
* Auteur : Mathilde de l'Hermuzière
* Créé : 20/11/2013
* 1.1 :  Mathilde de l'Hermuzière - creation and modification
* Formulaire d'ajout de destinataire dans le workflow
* 
* Projet parapheur 2014
*/

class Application_Form_Addperson extends Zend_Form
{
	public function init()
	{
		// HTTP méthode : Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		// L'identifiant de la personne que l'on veut ajouter
		$this->addElement('text', 'id_person', array(
				'label'      => 'ID du destinataire :',
				'required'   => true,
		));
		
		// Type du lien
		$this->addElement('select', 'type', array(
				'class'		 => 'write_comment',
				'label'		=> 'Type de lien :',
				'required'   => false,
				'attribs' =>   array(
						'id'=>'type_id',
				),
				'multioptions'   => array(
						'1' => 'Lien 1',
						'2' => 'Lien 2',
				),
		));
		
		// IR Auteur
		$this->addElement('text', 'IRauteur', array(
				'label'		=>	'IR Auteur :',
				'class'		 => 'required',
				'required'   => false
		));
		
		// Soumettre le commentaire - bouton
		$this->addElement('submit', 'ajouter', array(
				'ignore'   => true,
				'label'    => 'Ajouter',
				'class'		=> 'blue-button on-right'
   
		));
		 
	}
}