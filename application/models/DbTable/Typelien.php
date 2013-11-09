<?php

class Application_Model_DbTable_Typelien extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_TYPELIEN';
	protected $_primary = 'ID_TYPELIEN'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirTypelien($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

