<?php
// $Id: rdsection.class.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDSection extends RMObject
{
    /**
     * Meta values container
     */
    private $metas = [];

    public function __construct($id = null, $res = 0, $parent = null)
    {
        $this->db =  XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix('mod_docs_sections');
        $this->setNew();
        $this->initVarsFromTable();

        if (null === $id) {
            return;
        }

        if (is_numeric($id)) {
            if (!$this->loadValues($id)) {
                return;
            }
            $this->unsetNew();
        } else {
            $sql = 'SELECT * FROM ' . $this->_dbtable . " WHERE nameid='$id' AND id_res='$res'" . ($parent > 0 ? " AND parent=$parent" : '');
            $result = $this->db->query($sql);
            if ($this->db->getRowsNum($result) <= 0) {
                return;
            }

            $row = $this->db->fetchArray($result);
            $this->assignVars($row);
            $this->unsetNew();
        }
    }

    /**
     * Meta data
     */
    private function load_meta()
    {
        if (!empty($this->metas)) {
            return;
        }

        $result = $this->db->query('SELECT * FROM ' . $this->db->prefix('mod_docs_meta') . " WHERE section='" . $this->id() . "' AND edit='0'");
        while (false !== ($row = $this->db->fetchArray($result))) {
            $this->metas[$row['name']] = $row;
        }
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

    /**
     * Get a single meta from section
     * @param string Meta name
     * @param mixed $name
     * @return string|array
     */
    public function meta($name = '')
    {
        $this->load_meta();

        if ('' == trim($name)) {
            return false;
        }

        if (!isset($this->metas[$name])) {
            return false;
        }

        return $this->metas[$name]['value'];
    }

    /**
     * Return all metas existing for a section
     * @param mixed $values
     * @return array
     */
    public function metas($values = true)
    {
        $this->load_meta();
        $metas = [];

        if (!$values) {
            return $this->metas;
        }

        foreach ($this->metas as $data) {
            $metas[$data['name']] = $data['value'];
        }

        return $metas;
    }

    public function id()
    {
        return $this->getVar('id_sec');
    }

    /**
     * Get the permalink for this section
     * @param mixed $edit
     */
    public function permalink($edit = 0)
    {
        global $standalone;
        $config = RMSettings::module_settings('docs');

        $cache = ObjectsCache::get();
        $res = $cache->cached('docs', 'res-' . $this->id_res);
        if (!$res) {
            $res = new RDResource($this->getVar('id_res'));
            $cache->set_cache('docs', 'res-' . $this->id_res, $res);
        }

        if ($res->getVar('single') && defined('RD_LOCATION') && RD_LOCATION === 'resource_content') {
            return '#' . $this->getVar('nameid');
        }

        if ($config->permalinks) {
            $perma = ('' != $config->subdomain ? $config->subdomain : XOOPS_URL) . $config->htpath . '/' . $res->owname . '/' . $res->getVar('nameid') . '/' . ($edit ? '<span>' . $this->getVar('nameid') . '</span>' : $this->getVar('nameid')) . '/';
        /*
        if($this->getVar('parent')>0){

            $parent = RDFunctions::super_parent( $this->parent );
            $perma = $parent->permalink().'#'.($edit ? '<span>'.$this->getVar('nameid').'</span>' : $this->getVar('nameid'));

        } else {
            $perma = ($config->subdomain != '' ? $config->subdomain : XOOPS_URL).$config->htpath . '/'.$res->getVar('nameid').'/'.($edit ? '<span>'.$this->getVar('nameid').'</span>' : $this->getVar('nameid')).'/';
            $perma .= $standalone ? 'standalone/1/' : '';
        }
        */
        } else {
            $perma = XOOPS_URL . '/modules/docs/index.php?page=content&amp;id=' . $this->id();
            /*if($this->getVar('parent')>0){
                $perma = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$this->id();
                $sec = $cache->cached( 'docs', 'sec-' . $this->parent );
                if ( !$sec ){
                    $sec = new RDSection($this->getVar('parent'));
                    $cache->set_cache( 'docs', 'sec-' . $this->parent, $sec );
                }
                $perma = $sec->permalink().'#'.$this->getVar('nameid');
            } else {
                $perma = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$this->id();
                $perma .= $standalone ? '&amp;standalone=1' : '';
            }*/
        }

        return $perma;
    }

    public function editlink()
    {
        $config = RMSettings::module_settings('docs');
        if ($config->permalinks) {
            $link = RDFunctions::url() . '/edit/' . $this->id() . '/' . $this->getVar('id_res');
        } else {
            $link = RDFunctions::url() . '?page=edit&id=' . $this->id() . '&res=' . $this->getVar('id_res');
        }

        return $link;
    }

    public function save()
    {
        if ($this->isNew()) {
            $ret = $this->saveToTable();
        } else {
            $ret = $this->updateTable();
        }

        if (!$ret) {
            return false;
        }

        return $this->save_metas();
    }

    private function save_metas()
    {
        $this->db->queryF('DELETE FROM ' . $this->db->prefix('mod_docs_meta') . " WHERE section='" . $this->id() . "'");
        if (empty($this->metas)) {
            return true;
        }
        $sql = 'INSERT INTO ' . $this->db->prefix('mod_docs_meta') . ' (`name`,`value`,`section`,`edit`) VALUES ';
        $values = '';
        foreach ($this->metas as $name => $value) {
            if (is_array($value)) {
                $value = $value['value'];
            }
            $values .= ('' == $values ? '' : ',') . "('" . MyTextSanitizer::addSlashes($name) . "','" . MyTextSanitizer::addSlashes($value) . "','" . $this->id() . "','0')";
        }

        if ($this->db->queryF($sql . $values)) {
            return true;
        }
        $this->addError($this->db->error());

        return false;
    }

    public function delete()
    {
        $ret = false;

        // Change the parent on child sections
        $sql = 'UPDATE ' . $this->db->prefix('mod_docs_sections') . " SET parent=0 WHERE parent='" . $this->id() . "'";
        $result = $this->db->queryF($sql);

        if (!$result) {
            return $ret;
        }

        $ret = $this->deleteFromTable();

        return $ret;
    }
}
