<?php
/**
 * Get list of resources
 */
use MODX\Revolution\Processors\Resource\GetList;

class autoRedirectorResourceGetListProcessor extends GetList
{

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'id:LIKE' => "$query%",
                'OR:pagetitle:LIKE' => "$query%"
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
