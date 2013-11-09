<?php

class Application_Model_DbTable_Typefichier extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_TYPEFICHIER';
	protected $_primary = 'ID_TYPEFICHIER'; //Le nom de la clé primaire (case sensitive)

	public function obtenirTypefichier($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

