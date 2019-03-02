<?php
// $Id: rmeditoraddons.class.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// CopyRight  2007 - 2008. Red México
// http://www.redmexico.com.mx
// http://www.exmsystem.com
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright:  2007 - 2008. Red México

class RMEditorAddons extends RMFormElement
{
    private $editor;
    private $name;
    private $caption;
    private $type;
    private $id;
    private $section;
    
    
    /**
    * @param int $editor Id del editor
    * @param string $name Nombre del campo
    * @param string $caption Texto para mostrar en el campo
    * @param string $type Tipo de editor
    * @param int $id_res Publicación
    * @param int $id_sec Contenido
    **/
    public function __construct($caption, $name, $editor, $type, $id_res, $id_sec)
    {
        $this->editor=$editor;
        $this->setName($name);
        $this->setCaption($caption);
        $this->type = $type;
        $this->id=$id_res;
        $this->section=$id_sec;
    }
    
    public function jsFunctions()
    {
        if ($this->type=='tiny') {
            $ret = "<script type='text/javascript'>
					function insertReference(id_ref){
						var html;
						html ='[ref:'+id_ref+']';
						tinyMCE.execInstanceCommand('$this->editor','mceInsertContent',true,html);
					}
				</script>";
            $ret .= "<script type='text/javascript'>
					function insertFigure(id_fig){
						var html;
						html ='[fig:'+id_fig+']';
						tinyMCE.execInstanceCommand('$this->editor','mceInsertContent',true,html);
					}
				</script>";
        } else {
            $ret = "<script type='text/javascript'>
					function insertReference(id_ref){
						var html;
						html ='[ref:'+id_ref+']';
						edit=$('$this->editor');
						xoopsInsertText(edit,html);		
					}
				</script>";
            $ret .= "<script type='text/javascript'>
					function insertFigure(id_fig){
						var html;
						html ='[fig:'+id_fig+']';
						edit=$('$this->editor');
						xoopsInsertText(edit,html);		
					}
				</script>";
        }
        
        return $ret;
    }

    public function render()
    {
        $ret = '<img src="'.XOOPS_URL.'/modules/ahelp/images/refs16.png" align="absmiddle" border="0" alt="" /> ';
        $ret .= "<a href='javascript:;' onclick=\"centerWindow(openWithSelfMain('".XOOPS_URL."/modules/ahelp/references.php?id=$this->id&amp;section=$this->section&amp;editor=$this->editor','references',500,600,true),500,600);\">"._AS_AH_REFERENCES."</a> &nbsp;";
        $ret .= '<img src="'.XOOPS_URL.'/modules/ahelp/images/figs16.png" align="absmiddle" border="0" alt="" /> ';
        $ret .= "<a href='javascript:;'  onclick=\"centerWindow(openWithSelfMain('".XOOPS_URL."/modules/ahelp/figures.php?id=$this->id&amp;section=$this->section&amp;editor=$this->editor','figures',710,600,true),710,600);\">"._AS_AH_FIGURES."</a>";

        return $ret;
    }
}
