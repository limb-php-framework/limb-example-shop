<?php
/**********************************************************************************
* Copyright 2004 BIT, Ltd. http://limb-project.com, mailto: support@limb-project.com
*
* Released under the LGPL license (http://www.gnu.org/copyleft/lesser.html)
***********************************************************************************
*
* $Id: lmbTreeNode.class.php 4239 2006-10-18 15:23:24Z pachanga $
*
***********************************************************************************/
lmb_require('limb/active_record/src/lmbActiveRecord.class.php');
lmb_require('limb/tree/src/tree/lmbMaterializedPathTree.class.php');
lmb_require('src/model/ClassName.class.php');
lmb_require('src/validation/lmbTreeIdentifierRule.class.php');

class Node extends lmbActiveRecord
{
  protected $_db_table_name = 'node';
  protected $_base_class = __CLASS__;
  protected $_default_sort_params = array('priority' => 'ASC');

  protected $object;
  protected $url_path;
  protected $tree;
  protected $controller_name;

  protected $_has_one = array('parent' => array('field' => 'parent_id',
                                                'class' => 'Node',
                                                'can_be_null' => true,
                                                'cascade_delete' => false));

  protected $_has_many = array('kids' => array('field' => 'parent_id',
                                               'class' => 'Node'));

  function __construct($magic_params = null)
  {
    $this->tree = lmbToolkit :: instance()->getTree();
    parent :: __construct($magic_params);
  }

  protected function _createValidator()
  {
    $validator = new lmbValidator();
    $validator->addRequiredRule('controller_name');
    $validator->addRequiredRule('title');
    $validator->addRequiredRule('identifier');
    $validator->addRule(new lmbTreeIdentifierRule('identifier', 'parent_id'));

    return $validator;
  }

  protected function _onAfterSave()
  {
    if(is_object($this->object))
    {
      $this->object->registerOnAfterSaveCallback($this, 'updateNodeToObjectLink');
      $this->object->save($this->_error_list);
    }
  }

  function updateNodeToObjectLink($object)
  {
    $this->_setRaw('object_id', $object_id = $object->getId());
    $this->_setRaw('object_class_id', $object_class_id = ClassName :: generateIdFor($object));
    $this->_updateDbRecord(array('object_id' => $object_id,
                                 'object_class_id' => $object_class_id));
  }

  protected function _insertDbRecord($values)
  {
    $parent = $this->getParent();
    if($parent && $parent_id = $parent->id)
      return $this->tree->createNode($values, $parent_id);
    else
      return $this->tree->createNode($values);
  }

  protected function _updateDbRecord($values)
  {
    return $this->tree->updateNode($this->id, $values);
  }

  protected function _onBeforeDestroy()
  {
    if($object = $this->getObject())
    {
      $object->node = $this;//???
      $object->destroy();
    }
  }

  protected function _deleteDbRecord()
  {
    $this->tree->deleteNode($this->id);
  }

  static function findByPath($class_name, $path)
  {
    $object = new $class_name();
    if($object->loadByPath($path))
      return lmbActiveRecord :: findById('Node', $object->id);
  }

  function loadByPath($path)
  {
    if(!$node = $this->tree->getNodeByPath($path))
      return false;

    $this->import($node);
    return true;
  }

  function getObject()
  {
    if(!$this->get('object_id'))
      return null;

    $class_name = lmbActiveRecord :: findById('ClassName', $this->getObjectClassId());
    return lmbActiveRecord :: findById($class_name->title, $this->get('object_id'));
  }

  function getControllerName()
  {
    if(!$this->controller_id)
      return '';

    if(!$this->controller_name)
    {
      $class_name = lmbActiveRecord :: findById('ClassName', $this->controller_id);
      $this->controller_name = $class_name->title;
    }

    return $this->controller_name;
  }

  function setControllerName($controller_name)
  {
    $this->controller_name = $controller_name;
    $this->_setRaw('controller_id', $controler_id = ClassName :: generateIdFor($this->controller_name));
  }

  function getUrlPath()
  {
    if(isset($this->url_path))
      return $this->url_path;

    if(!($parent_path = $this->tree->getPathToNode($this->parent_id)))
    {
      $this->url_path = '/' . $this->identifier;
      return $this->url_path;
    }

    $this->url_path = rtrim($parent_path, '/') . '/' . $this->identifier;
    return $this->url_path;
  }

  function getParents()
  {
    return $this->decorateRecordSet($this->tree->getParents($this->id));
  }

  function getRoots()
  {
    return lmbActiveRecord :: decorateRecordSet($this->tree->getRootNodes(), 'Node');
  }

  function getRootNodes()
  {
    return $this->getRoots();
  }

  function generateIdentifier($parent_id)
  {
    $identifier = lmbToolkit :: instance()->getTree()->getMaxChildIdentifier($parent_id);

    if($identifier === false)
      return 1;

    if(preg_match('/(.*?)(\d+)$/', $identifier, $matches))
      $new_identifier = $matches[1] . ($matches[2] + 1);
    else
      $new_identifier = $identifier . '1';

    return $new_identifier;
  }
}

?>
