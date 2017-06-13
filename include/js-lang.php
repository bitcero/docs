<?php
/**
 * Documentor for XOOPS
 *
 * Copyright © 2015 Red Mexico http://www.redmexico.com.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Red Mexico http://www.redmexico.com.mx
 * @license      GNU GPL 2
 * @package      docs
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.redmexico.com.mx
 * @url          http://www.eduardocortes.mx
 */

/**
 * This files include all language strings to use in Javascript files
 */

ob_start();
?>

var docsLang = {
    errorData:      '<?php _e("I\\'ve found an error in section data. Please perform a new books search to correct this error.", 'docs'); ?>',
    errorUser:      '<?php _e('You must provide the username to search book!', 'docs'); ?>',
    noEditor:       '<?php _e('An error occurs while trying to perform this operation: No editor has been specified.', 'docs'); ?>',
    errorMD:        '<?php _e('Sorry, an error occurs while trying to insert the link to this section!', 'docs'); ?>',
    invalidNote:    '<?php _e('This note is not valid!', 'docs'); ?>',
    missingNote:    '<?php _e('Note is empty!', 'docs'); ?>'
};

<?php
$lang = ob_get_clean();
return $lang;