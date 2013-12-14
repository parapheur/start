<?php

class Application_Model_DbTable_Lieninterne extends Zend_Db_Table_Abstract
{

    protected $_name = 'LIENINTERNE';
	protected $_primary = 'ID_LIENINTERNE'; //Le nom de la clé primaire (case sensitive)
	protected $_sequence = 'CANALSEQ';
	
	public function obtenirLieninterne($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
    	if (!$row) {
    		throw new Exception("Impossible de trouver l'enregistrement $id");
    	}
    	return $row->toArray();
    }

    public function ajouterLieninterne($id_courrier, $id_exped, $id_dest, $id_etat, $date)
    {
    	$data = array(
    			'ID_COURRIER' => $id_courrier,
    			'ID_ENTITEEXPEDITEUR' => $id_exped,
    			'ID_ENTITEDESTINATAIRE' => $id_dest,
    			'ID_TYPELIENENTITE' => '1',
    			'ID_ETATDESTINATAIRE' => $id_etat,
    			'VISIBLE' => 'N',
    			'DATECREATION' => new Zend_Db_Expr("TO_DATE('$date', 'DD/MM/YYYY')"),//$date,
    			'IRAUTEUR' => 'test ajout'
    	);
    	$this->insert($data);
    }
}

