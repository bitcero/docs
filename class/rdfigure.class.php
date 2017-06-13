<?php
// $Id: rdfigure.class.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDFigure extends RMObject{


	function __construct($id=null){

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("mod_docs_figures");
		$this->setNew();
		$this->initVarsFromTable();
        $this->setVarType('attrs', XOBJ_DTYPE_SOURCE);

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}	
	
	}


	public function id(){
		return $this->getVar('id_fig');
	}

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}else{
			return $this->updateTable();
		}
	}


	public function delete(){
		return $this->deleteFromTable();

	}
    
}
