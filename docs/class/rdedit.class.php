<?php
// $Id: rdedit.class.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDEdit extends RMObject
{
    public function __construct($id = null, $sec = null)
    {
        $this->db =  XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_docs_edits');
        $this->setNew();
        $this->initVarsFromTable();

        if (null === $id && null === $sec) {
            return;
        }

        if (null != $id) {
            if ($this->loadValues($id)) {
                $this->unsetNew();
            }

            return;
        }

        if (null != $sec) {
            $this->primary = 'id_sec';
            if ($this->loadValues($sec)) {
                $this->unsetNew();
            }
            $this->primary = 'id_edit';

            return;
        }
    }

    public function id()
    {
        return $this->getVar('id_edit');
    }

    /**
     * Add metas to the current section
     * @param mixed $key
     * @param mixed $value
     */
    public function add_meta($key, $value)
    {
        if ('' == $key) {
            return;
        }
        $this->metas[$key] = $value;
    }

    /**
     * Clear metas array
     */
    public function clear_metas()
    {
        $this->metas = [];
    }

    public function save()
    {
        if ($this->isNew()) {
            // Comprobamos que no exista un registro para la misma sección
            $result = $this->db->query('SELECT id_edit FROM ' . $this->_dbtable . " WHERE id_sec='" . $this->getVar('id_sec') . "'");
            if ($this->db->getRowsNum($result) > 0) {
                list($id) = $this->db->fetchRow($result);
                $this->setVar('id_edit', $id);

                return $this->updateTable();
            }

            return $this->saveToTable();
        }

        return $this->updateTable();
    }

    private function save_metas()
    {
        $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_docs_meta') . " WHERE section='" . $this->id() . "'");
        if (empty($this->metas)) {
            return true;
        }
        $sql = 'INSERT INTO ' . $this->db->prefix('mod_docs_meta') . ' (`name`,`value`,`section`,`edit`) VALUES ';
        $values = '';
        $myts = MyTextSanitizer::getInstance();
        foreach ($this->metas as $name => $value) {
            if (is_array($value)) {
                $value = $value['value'];
            }
            $values .= ('' == $values ? '' : ',') . "('" . $myts->addSlashes($name) . "','" . $myts->addSlashes($value) . "','" . $this->getVar('id_sec') . "','1')";
        }

        if ($this->db->queryF($sql . $values)) {
            return true;
        }
        $this->addError($this->db->error());

        return false;
    }

    public function delete()
    {
        return $this->deleteFromTable();
    }
}
