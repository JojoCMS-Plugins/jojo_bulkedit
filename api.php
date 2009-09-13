<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2008 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2008 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 * @package jojo_article
 */

$_provides['pluginClasses'] = array('JOJO_Plugin_Admin_Bulkedit' => 'Bulkedit - bulk edit screen');

/* Register URI patterns */
Jojo::registerURI("admin/bulk-edit/[tablename:string]/p[pagenum:integer]", 'JOJO_Plugin_Admin_Bulkedit');
Jojo::registerURI("admin/bulk-edit/[tablename:string]",                    'JOJO_Plugin_Admin_Bulkedit');
