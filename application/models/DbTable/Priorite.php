<?php

class Application_Model_DbTable_Priorite extends Zend_Db_Table_Abstract
{

    protected $_name = 'T_PRIORITE';
	protected $_primary = 'ID_PRIORITE'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirPriorite($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

