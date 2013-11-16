<?php

class AddafileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$id=2;
    	//Désactiver le layout
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
        //Here i will try to unclob a PDF file.
     	$table = new Application_Model_DbTable_Contenu();
     	$rows = $table->obtenirPdf($id);
    	
     	//$thepdf=new Zend_Pdf();
     	foreach($rows as $row){
     	//foreach ($row AS $field_name => $field_value)
        //{

            if (is_resource($row)  ) {
				print(fread($row, 1000000)."\n");

            } else {
            	print($row."\n");
            	
            }
        //}
     	}
     	$pdf = Zend_Pdf::parse($row);
		
     	
     	//Changement des header afin d'indiquer que la page est une application PDF
     	/*$this->getResponse()->setHeader('Content-type', 'application/pdf', true);
     	$this->getResponse()->setHeader('Content-disposition','inline;filename='.$module.'_'.$m_no.'.pdf', true);
     	
     	$this->getResponse()->setHeader('Cache-Control: no-cache, must-revalidate');
     	$this->getResponse()->setHeader('Content-Transfer-Encoding', 'binary', true);
     	$this->getResponse()->setHeader('Last-Modified', date('r'));
     	
     	//Efface ce qui est contenue dans la balise body
     	$this->getResponse()->clearBody();
     	//Envoie les headers modifi�s au pr�alable
     	$this->getResponse()->sendHeaders();*/
     	
     	//Renvoie la chaine de caract�re du PDF (donc le contenu) dans le Body
     	 
     	//$page1 = clone $pdf->pages[1];
     	$this->getResponse()->setBody($pdf->render());
     	//$pdf = Zend_Pdf::parse($row[1]);
     	
     	
     	//$clobcontent=$row['CONTENT']->load();
     	//echo $clobcontent;
    	//$this->_helper->layout->disableLayout();
    }
	
	public function signAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Addafile();
 
        if ($this->getRequest()->isPost()) {
             if ($form->isValid($request->getPost())) {
              	$upload = new Zend_File_Transfer_Adapter_Http();
              	$upload->setDestination(realpath(APPLICATION_PATH . '\data'));
              	//$upload->setDestination(realpath('c:'));
              	try { //be sure to call receive() before getValues()
              		$upload->receive();
              	} catch (Zend_File_Transfer_Exception $e) {
              		$e->getMessage();
              	}
                $formData = $form->getValues();
              	$filename = $upload->getFileName('upfile');
//               	$name=$formData->getValue('upfile');
              	$filesize = $upload->getFileSize('upfile');
              	$filemimeType = $upload->getMimeType('upfile');
              	
              	echo $filename;
              	echo $filesize;
              	echo $filemimeType;
              	 
//              	$dstFilePath = '/images/'.$filename;
             	
//              	$filterFileRename = new Zend_Filter_File_Rename(array('target' => $dstFilePath, 'overwrite' => true));
//              	$filterFileRename->filter($filename); //move uploade file to destination
             		
//              	$file = file_get_contents($fileName);
					//$url=APPLICATION_PATH.'\data\minicdcparapheur.pdf';
             		//$pdf=Zend_Pdf::parse(file_get_contents($filename));
//              		$path='/data'.$name;
//              		$file=file_get_contents('/data/minicdcparapheur.pdf');
//              		$pdf=Zend_Pdf::parse(file);
             		//$pdf = Zend_Pdf::load('C:\a.pdf');
             		$pdf = Zend_Pdf::load(realpath(APPLICATION_PATH . '/data/a.pdf'));
             		//$pdf = Zend_Pdf::load($filename);
					$title = $form->getValue('titre');
					
					$author = $form->getValue('id_author');
					$des1 = $form->getValue('id_dest1');
					$des2 = $form->getValue('id_dest2');
					$des3 = $form->getValue('id_dest3');
					
					$id_typecourrier=1;
					$id_typefichier=1; //is a PDF
					$taille=500;
					
					$courrier = new Application_Model_DbTable_Courrier();
					$fichier = new Application_Model_DbTable_Fichier();
					$contenu = new Application_Model_DbTable_Contenu();
					
					$id_courrier=  $courrier->ajouterCourrier($id_typecourrier);
					echo $id_courrier;
					
					$id_fichier=$fichier->ajouterFichier($id_courrier, $id_typefichier, $taille, $title);
					echo $id_fichier;
					
					$pdfString = $pdf->render();
					$contenu->ajouterContenu($id_fichier, $pdfString);
                // return $this->_helper->redirector('index');
					$this->_helper->redirector('index', 'index');
             }
        }
 
        $this->view->form = $form;
    }
    
    public function imagickAction()
    {

    	$this->_helper->layout->disableLayout();
    }


}

