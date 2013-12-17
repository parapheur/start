<?php

class Application_Model_DbTable_Processusout extends Zend_Db_Table_Abstract
{

    protected $_name = 'L_PROCESSUS_OUT';
	protected $_primary = 'ID_PROCESSUS'; //Le nom de la clé primaire (case sensitive)
	
	public function obtenirProcessusout($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}

