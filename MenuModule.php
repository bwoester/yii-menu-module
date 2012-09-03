<?php

class MenuModule extends CWebModule
{

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $nestedSetBehavior = 'ext.behaviors.nested-set.NestedSetBehavior';

  /////////////////////////////////////////////////////////////////////////////

  public function init()
  {
    // import the module-level models and components
    $this->setImport(array(
      'menu.components.*',
      'menu.models.*',
      'menu.models.generated.*',
    ));

    MenuStructure::$nestedSetBehavior = $this->nestedSetBehavior;
  }

  /////////////////////////////////////////////////////////////////////////////

  public function beforeControllerAction($controller, $action)
  {
    if(parent::beforeControllerAction($controller, $action))
    {
      // this method is called before any module controller action is performed
      // you may place customized code here
      return true;
    }
    else
      return false;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $aAttributes
   * @return int the menu items id or an array of error messages.
   */
  public function createMenuItem( $aAttributes )
  {
    $model = new MenuItem();
    $model->attributes = $aAttributes;
    return $model->save()
      ? (int)$model->id
      : $model->errors;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $aAttributes
   * @return int a structure id or an array of error messages.
   */
  public function createMenu( $aAttributes )
  {
    $model = new Menu();
    $model->attributes = $aAttributes ;
    return $model->save()
      ? (int)$model->node->id
      : $model->errors;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param int $structureId
   * @return bool
   */
  public function deleteMenu( $structureId )
  {
    // DB takes care of deleting the menu
    return MenuStructure::model()->findByPk($structureId)->deleteNode();
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param int $structureId
   * @return bool
   */
  public function menuExists( $structureId )
  {
    return MenuStructure::model()->exists( 'id=?', array($structureId) );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Inserts the menu item before the menu item represented by the $structureId.
   *
   * @param int $structureId
   * @param int $itemId
   * @return int a structure id or false in case of an error
   */
  public function insertBefore( $structureId, $itemId )
  {
    $item = MenuItem::model()->findByPk( $itemId );
    $node = MenuStructure::model()->findByPk( $structureId );
    return $node->insertBefore( $item );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function replaceChild( $structureId, $newItemId, $oldStructureId )
  {
    $this->insertBefore( $structureId, $newItemId );
    $this->removeChild( $structureId, $oldStructureId );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function removeChild( $structureId, $structureIdToRemove )
  {
    $node = MenuStructure::model()->findByPk( $structureId );
    $nodeToRemove = MenuStructure::model()->findByPk( $structureIdToRemove );
    return $node->removeChild( $nodeToRemove );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function appendChild( $structureId, $itemId )
  {
    $item = MenuItem::model()->findByPk( $itemId );
    $node = MenuStructure::model()->findByPk( $structureId );
    return $node->appendChild( $item );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function getMenuConfig( $id )
  {
    $aConfig        = array();
    $menu           = Menu::model()->with('structure')->findByPk( $id );
    $menuStructure  = $menu instanceof Menu ? $menu->structure : null;
    $structureItems = $menuStructure instanceof MenuStructure
      ? $menuStructure->getItems()
      : array();

    // TODO convert to CMenu::items array

    /* @var $structureItem MenuStructure */
    foreach ($structureItems as $structureItem)
    {
      CVarDumper::dump( CJSON::encode($structureItem->item), 3, true );
    }

    return $aConfig;
  }

  /////////////////////////////////////////////////////////////////////////////

}
