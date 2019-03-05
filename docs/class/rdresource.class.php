<?php
// $Id: rdresource.class.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDResource extends RMObject
{
    /**
     * Stores the references existing for this Document
     *
     * @var array
     */
    private $references = [];
    /**
     * Stores the figures existing for this Document
     *
     * @var array
     */
    private $figures = [];

    public function __construct($id = null)
    {
        $this->db =  XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_docs_resources');
        $this->setNew();
        $this->initVarsFromTable();

        $this->setVarType('editors', XOBJ_DTYPE_ARRAY);
        $this->setVarType('groups', XOBJ_DTYPE_ARRAY);

        if (null === $id) {
            return;
        }

        if (is_numeric($id)) {
            if (!$this->loadValues($id)) {
                return;
            }
            $this->unsetNew();
        } else {
            $this->primary = 'nameid';
            if ($this->loadValues($id)) {
                $this->unsetNew();
            }
            $this->primary = 'id_res';
        }
    }

    public function id()
    {
        return $this->getVar('id_res');
    }

    /**
     * Get the references for this Document
     * @return array
     */
    public function get_references()
    {
        if (!empty($this->references)) {
            return $this->references;
        }

        $db = $this->db;

        $sql = 'SELECT * FROM ' . $db->prefix('pa_references') . " WHERE id_res='" . $this->id() . "'";
        $result = $db->query($sql);
        $refs = [];
        while (false !== ($row = $db->fetchArray($result))) {
            $refs[] = $row;
        }

        return $refs;
    }

    /**
     * Get the figures for this Document
     * @return array
     */
    public function get_figures()
    {
        if (!empty($this->figures)) {
            return $this->figures;
        }

        $db = $this->db;

        $sql = 'SELECT * FROM ' . $db->prefix('pa_figures') . " WHERE id_res='" . $this->id() . "'";
        $result = $db->query($sql);
        $figs = [];
        while (false !== ($row = $db->fetchArray($result))) {
            $this->figures[] = $row;
        }

        return $this->figures;
    }

    public function add_read()
    {
        if ($this->isNew()) {
            return;
        }

        return $this->db->queryF('UPDATE ' . $this->db->prefix('mod_docs_resources') . " SET `reads`=`reads`+1 WHERE id_res='" . $this->id() . "'");
    }

    public function addVote($rate)
    {
        if ($this->isNew()) {
            return;
        }

        return $this->db->queryF('UPDATE ' . $this->db->prefix('mod_docs_resources') . " SET `votes`=`votes`+1, `rating`='" . ($this->rating() + $rate) . "' WHERE id_res='" . $this->id() . "'");
        $this->setRating($this->rating() + $rate);
    }

    /**
     * @desc Determina si usuario tiene permiso para acceder a la publicación
     * @param int array $gid Id(s) de Grupo(s)
     *
     * @return bool
     */
    public function isAllowed($gid)
    {
        $groups = $this->getVar('groups');

        if (in_array(0, $groups, true)) {
            return true;
        }

        if (!is_array($gid)) {
            if (XOOPS_GROUP_ADMIN == $gid) {
                return true;
            }

            return in_array($gid, $groups, true);
        }

        if (in_array(XOOPS_GROUP_ADMIN, $gid, true)) {
            return true;
        }

        foreach ($gid as $k) {
            if (in_array($k, $groups, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @desc Determina si un usuario es editor de un recurso
     * @param int $uid Id de usuario
     *
     * @return bool
     */
    public function isEditor($uid)
    {
        $editors = $this->getVar('editors');

        return in_array($uid, $editors, true);
    }

    /**
     * Permalink for a Document
     *
     * Generates a permalink for Document according to configures parameters
     * in RapidDocs.
     *
     * @return string URL for this Document
     */
    public function permalink()
    {
        global $standalone;

        $config = RMSettings::module_settings('docs');
        if ($config->permalinks) {
            $perma = ('' != $config->subdomain ? $config->subdomain : XOOPS_URL) . $config->htpath . '/' . $this->owname . '/' . $this->getVar('nameid') . '/';
            $perma .= $standalone ? 'standalone/1' : '';
        } else {
            $perma = XOOPS_URL . '/modules/docs/?page=resource&amp;id=' . $this->id();
        }

        return $perma;
    }

    /**
     * Get a sections counter
     * @return int
     */
    public function sections_count()
    {
        $db = $this->db;

        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_sections') . ' WHERE id_res=' . $this->id();
        list($num) = $db->fetchRow($db->query($sql));

        return $num;
    }

    /**
     * Get the notes counter
     * @return int
     */
    public function notes_count()
    {
        $db = $this->db;

        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_references') . ' WHERE id_res=' . $this->id();
        list($num) = $db->fetchRow($db->query($sql));

        return $num;
    }

    /**
     * Get the figures counter
     * @return int
     */
    public function figures_count()
    {
        $db = $this->db;

        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_figures') . ' WHERE id_res=' . $this->id();
        list($num) = $db->fetchRow($db->query($sql));

        return $num;
    }

    /**
     * Save Document
     */
    public function save()
    {
        if ($this->isNew()) {
            return $this->saveToTable();
        }

        return $this->updateTable();
    }

    /**
     * Delete Document
     *
     * Delete Document and all its sections, notes and figures
     *
     * @return bool
     */
    public function delete()
    {
        $ret = false;

        //Elimina secciones pertenecientes a la publicación
        $sql = 'DELETE FROM ' . $this->db->prefix('mod_docs_sections') . " WHERE id_res='" . $this->id() . "'";
        $result = $this->db->queryF($sql);

        if (!$result) {
            return $ret;
        }

        //Elimina Referencias pertenecientes a la publicación
        $sql = 'DELETE FROM ' . $this->db->prefix('mod_docs_references') . " WHERE id_res='" . $this->id() . "'";
        $result = $this->db->queryF($sql);

        if (!$result) {
            return $ret;
        }

        //Elimina figuras pertenecientes a la publicación
        $sql = 'DELETE FROM ' . $this->db->prefix('mod_docs_figures') . " WHERE id_res='" . $this->id() . "'";
        $result = $this->db->queryF($sql);

        if (!$result) {
            return $ret;
        }

        $ret = $this->deleteFromTable();

        return $ret;
    }
}
