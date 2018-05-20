<?php
class MyControllerNavigation extends modRestController {
public $classKey = 'modResource';
public $defaultSortDirection = 'ASC';
public $defaultLimit = 100;
public $defaultSortField = 'menuindex';

public function getList() {
        $this->getProperties();
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->addSearchQuery($c);
        $c->where(array('hidemenu' => false));
        $c = $this->prepareListQueryBeforeCount($c);
        $total = $this->modx->getCount($this->classKey,$c);
        $alias = !empty($this->classAlias) ? $this->classAlias : $this->classKey;
        $c->select($this->modx->getSelectColumns($this->classKey,$alias));

        $c = $this->prepareListQueryAfterCount($c);

        $c->sortby($this->getProperty($this->getOption('propertySort','sort'),$this->defaultSortField),$this->getProperty($this->getOption('propertySortDir','dir'),$this->defaultSortDirection));
        $limit = $this->getProperty($this->getOption('propertyLimit','limit'),$this->defaultLimit);
        if (empty($limit)) $limit = $this->defaultLimit;
        $c->limit($limit,$this->getProperty($this->getOption('propertyOffset','start'),$this->defaultOffset));
        $objects = $this->modx->getCollection($this->classKey,$c);
        if (empty($objects)) $objects = array();
        $list = array();
        /** @var xPDOObject $object */
        foreach ($objects as $object) {
            $list[] = $this->prepareListObject($object);
        }
        return $this->collection($list,$total);
    }


}