<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007 Harvey Kane <code@ragepank.com>
 * Copyright 2007 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

/*
require_once(_BASEDIR . '/includes/Global.php');
require_once(_BASEDIR . '/classes/JOJO/Field.php');
require_once(_BASEDIR . '/classes/JOJO/Table.php');
require_once(_BASEDIR . '/external/frajax/frajax.class.php');
*/





$frajax = new frajax();
$frajax->title = 'Admin Action - ' . _SITETITLE;
$frajax->sendHeader();
$frajax->scrollToTop();
$frajax->assign("h1", "innerHTML", 'Processing...');

$content = array();

$t = Util::getFormData('tablename');
$id = Util::getFormData('id', 0);

if (!$t) {
    $frajax->alert('Unable to open class');
    exit();
}

/* Create table object */
$table = &JOJO_Table::singleton($t);
if ($id > 0) {
    $table->getRecord($id);
}

/* for tables with varchar based primary keys */
$sqltype = JOJO::getMySQLType($table->getTableName(), $table->getOption('primarykey'));
if (strpos($sqltype,'varchar') !== false && $id != '') {
    $table->getRecord($id);
}

/* Save button pressed */
if (Util::getPost('save', false)) {
    $errors = '';

    /* Retrieve all values from form and set the field values */
    foreach ($table->getFieldNames() as $fieldname) {
        if (Util::getFormData('fm_' . $fieldname, false) !== false) {
            $table->setFieldValue($fieldname, Util::getFormData('fm_' . $fieldname));
        }
    }

    /* Check for errors */
    $errors = $table->fieldErrors();
    if (is_array($errors)) {
        /* Error with one of the values */
        $frajax->alert("Errors found:\n" . implode("\n", $errors));
        exit();
    } else {

        $res = $table->saveRecord();
        if ($res !== false) {
            /* Success message */
            $frajax->assign('h1', 'innerHTML', 'Saved.');
            /* Clear the content cache after saving */
            JOJO::deleteQuery("DELETE FROM contentcache");
        } else {
            /* Error saving */
            $frajax->alert("Error: Saving failed");
        }
    }

}

/* Delete button pressed */
if (Util::getPost('btn_delete', false)) {
    if ($table->deleteRecord() == true) {
        /*
        refreshMenu();
        $frajax->assign('message', 'innerHTML', '<h4>Jojo CMS</h4>Record deleted', 'Appear', 1);
        $frajax->effect('message', 'Fade', 1, 3);
        $frajax->assign('h1', 'innerHTML', 'New ' . $table->getOption('displayname'));
        $frajax->script("frajax('load', $t, '');");
        */
        $frajax->redirect(_SITEURL . '/' . Util::getFormData('prefix') . '/' . $t . '/');
    } else {
        /* Error deleteing */
        //$frajax->assign("error", "innerHTML", '<h4>Error</h4>Deleting failed', 'Appear', 1);
        $frajax->script('parent.$("#error").html("<h4>Error</h4>Deleting failed").fadeIn("slow");');
        $frajax->assign("h1", "innerHTML", 'Delete Error');
    }
    exit();
}


$frajax->sendFooter();