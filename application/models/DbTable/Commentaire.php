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
    
    public function ajouterCommentaire($id_document, $user_ID, $text_commentaire, $date)
    {
    	$data = array(
    			//'ID_COURRIER' => '2',
    			'ID_COURRIER' => $id_document,
    			'ID_COURRIERENTITE' => $user_ID,
    			'CONTENU' => $text_commentaire,
    			'DATECREATION' => new Zend_Db_Expr("TO_DATE('$date', 'DD/MM/YYYY')"),
    			'ID_GROUPEAUTEUR' => '1',
    			'IRAUTEUR' => 'tes'
    	);
    	$this->insert($data);
    }

}

