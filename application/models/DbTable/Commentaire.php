<?php

class Application_Model_DbTable_Commentaire extends Zend_Db_Table_Abstract
{

    protected $_name = 'COMMENTAIRE';
	protected $_primary = 'ID_COMMENTAIRE'; //Le nom de la clé primaire (case sensitive)
	protected $_sequence = 'COMMENTAIRESEQ';
	
	public function obtenirCommentaire($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

}

