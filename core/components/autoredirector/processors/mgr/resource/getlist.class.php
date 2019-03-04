<?php
/**
 * Get list of resources
 */
include_once MODX_CORE_PATH.'model/modx/processors/resource/getlist.class.php';

class autoRedirectorResourceGetListProcessor extends modResourceGetListProcessor
{

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'pagetitle:LIKE' => "$query%"
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $objectArray = parent::prepareRow($object);
        if ($this->getProperty('combo', false)) {
            $objectArray = array(
                'id' => $objectArray['id'],
                'pagetitle' => $objectArray['pagetitle'],
            );
        }

        return $objectArray;
    }

}

return 'autoRedirectorResourceGetListProcessor';
