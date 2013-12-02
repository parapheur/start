<?php

/*
 * Fichier : Refuse.php
* Auteur : Hina Tufail
* Créé : 16/11/2013
* Modifié : 16/11/2013
* 1.1 :  Hina Tufail - création et modification
* Formulaire de refus d'un document
*
* Projet parapheur 2014
*/

class Application_Form_Refuse extends Zend_Form
{

    public function init()
    {
        // HTTP méthode utilisée : Post
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setMethod('post');
		
		
		// Contenu du commentaire
		$this->addElement('textarea', 'text_commentaire_refuse', array(
				'class'		 => 'on-right',
				'required'   => false,
				'attribs' =>   array(
						'id'=>'type_id',
				)
		));
		
		// Bouton - soumettre le formulaire
		$this->addElement('submit', 'Refuser', array(
				'ignore'   => true,
				'label'    => 'Refuser',
				'class'		=> 'red-button on-right'
   
		));
    }


}

