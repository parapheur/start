<?php

class Application_Model_DbTable_Courrier extends Zend_Db_Table_Abstract
{

    protected $_name = 'COURRIER';
	protected $_primary = 'ID_COURRIER'; //Le nom de la clé primaire. Attention : sensible à la casse

    public function obtenirMetaInfo($id)
    {
    	$id = (int)$id;
    	$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }
}