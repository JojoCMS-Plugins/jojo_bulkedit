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



class JOJO_Plugin_Admin_Bulkedit extends JOJO_Plugin
{
    function _getContent()
    {

        global $smarty, $_USERGROUPS;
        $content = array();
        Jojo_Plugin_Admin::adminMenu();



        $tablename = Util::getFormData('tablename', false);
        $smarty->assign('tablename', $tablename);

        /*
        $activefields[] = 'pg_title';
        $activefields[] = 'pg_mainnav';
        */

        /* do not apply content variables to the edit pages. This was causing
        problems with the vars inside the textareas being replaced */
        Jojo::removeFilter('content', 'applyContentVars', 'jojo_core');

        if (!$tablename) {
            $content['content'] = $smarty->fetch('admin/bulkedit.tpl');
            return $content;
        }

        /* Create table object */
        $table = &JOJO_Table::singleton($tablename);

        $records     = array();
        $recordstemp = $table->createlist('array');
        $pagenum     = Util::getFormData('pagenum', 1);
        $perpage     = 20;
        $numresults  = count($recordstemp);
        $start       = ($pagenum -1) * $perpage;
        $end         = $start + $perpage;

        /* shorten the resultset based on pagination settings */
        for ($i=0;$i<$numresults;$i++) {
            if (($i >= $start) && ($i < $end)) $records[] = $recordstemp[$i];
        }

        foreach (Jojo::listPlugins('jojo_pagination.php') as $pluginfile) require_once($pluginfile);
        $pagination             = new jojo_pagination();
        $pagination->id         = 'pagination';
        $pagination->pagenum    = $pagenum;
        $pagination->perpage    = $perpage;
        $pagination->numresults = $numresults;
        $pagination->showpages  = 5;
        $pagination->urlformat  = 'admin/bulk-edit/'.$tablename.'/p*/';
        $pagination->urlformat1 = 'admin/bulk-edit/'.$tablename.'/';
        $smarty->assign('pagination',$pagination->getPagination());

        $allfields = array();
        $activefields = array();
        if (!empty($_POST['setfields'])) {
            unset($_SESSION['bulkedit_activefields']);
            foreach ($_POST as $k => $v) {
                if ($v == 'setfields') $activefields[] = str_replace('setfields_','',$k);
            }
            $_SESSION['bulkedit_activefields'] = serialize($activefields);
        } else {
            $activefields = isset($_SESSION['bulkedit_activefields']) ? unserialize($_SESSION['bulkedit_activefields']) : array();
        }
        $fields = $table->getFieldNames();
        foreach ($fields as $field) {
            $fieldhtml = $table->getFieldHTML($field);
            $fieldhtml['fieldname'] = $field;
            if (in_array($field, $activefields)) $fieldhtml['active'] = true;
            $allfields[] = $fieldhtml;
        }
        $smarty->assign('allfields',$allfields);

        $n = count($records);
        for ($i=0;$i<$n;$i++) {
            $table->getRecord($records[$i]['id']);
            $fieldsHTML = $table->getHTML('edit');
            $records[$i]['title']  = $table->getOption('displayvalue');
            $records[$i]['fields'] = array();
            foreach ($activefields as $fieldname) {
                $records[$i]['fields'][$fieldname] = $fieldsHTML[$fieldname]['html'];
            }
        }

        $smarty->assign('activefields', $activefields);
        $smarty->assign('records',      $records);

        $content['content'] = $smarty->fetch('admin/bulkedit.tpl');

        return $content;
    }


    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

}