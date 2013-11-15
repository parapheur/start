<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	//As we do not have Active Directory, we decide that our user ID is 6
    	$user_ID=6;
    	$number_doc=0;
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$sql = 'SELECT ID_COURRIER FROM LIENINTERNE WHERE ID_ETATDESTINATAIRE = 1 AND ID_ENTITEDESTINATAIRE = '.$user_ID;
        // action body
        
    	$stmt = $db->query($sql);
//     	if ($this->_request->isPost()) {
//     		$data = $this->_request->getPost();
//     		Zend_Debug::dump($data);
//     	}
//echo 'test';
		//$ID_COURRIER=$stmt->fetchColumn(0);
		while ($row=$stmt->fetch()){
			$sql2='SELECT DATECREATION FROM COURRIER WHERE ID_COURRIER = '.$row[ID_COURRIER];
			$stmt2 = $db->query($sql2);
			$row2 = $stmt2->fetch();
			
			$sql3='SELECT NOMORIGINE FROM FICHIER WHERE ID_COURRIER = '.$row[ID_COURRIER];
			$stmt3 = $db->query($sql3);
			$row3 = $stmt3->fetch();
			$number_doc=$number_doc+1;
		}
    	$this->view->number_doc =$docCount;
    	//Fetch data from database to list PDF files
    	//$courrier = new Application_Model_DbTable_Courrier;
    	//$courrierRows = $courrier->fetchAll();
		//$this->view->courrier = $courrierRows;
    	
    	//Count how many documents we have to display in the index
//     	$rowCount = count($courrierRows);
//     	$this->view->courrierRows = $rowCount;
    	
    }
    
    public function addfileAction()
    {
    	// action body
       
    }

}

