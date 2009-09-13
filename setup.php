<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007 Harvey Kane <code@ragepank.com>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

// Bulk edit
Jojo::updateQuery("UPDATE {page} SET pg_link='JOJO_Plugin_Admin_Bulkedit' WHERE pg_link='admin-bulkedit.php'");
Jojo::updateQuery("UPDATE {page} SET pg_link='JOJO_Plugin_Admin_Bulkedit' WHERE pg_link='JOJO_Plugin_Jojo_bulkedit'");
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_url = 'admin/bulk-edit'");
if (!count($data)) {
    echo "Adding <b>Edit Pages</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'Bulk Edit', pg_link = 'JOJO_Plugin_Admin_Bulkedit', pg_url='admin/bulk-edit', pg_parent =?, pg_order=100", array($_ADMIN_CONTENT_ID));
}