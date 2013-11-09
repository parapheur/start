<?php

class Application_Model_DbTable_Classification extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_CLASSIFICATION';
	protected $_primary = 'ID_CLASSIFICATION'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirClassification($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

}

