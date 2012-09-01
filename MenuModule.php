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
   * @return int the menu id or an array of error messages.
   */
  public function createMenu( $aAttributes )
  {
    $model = new Menu();
    $model->attributes = $aAttributes ;
    return $model->save()
      ? (int)$model->id
      : $model->errors;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param int $id
   * @return bool
   */
  public function deleteMenu( $id )
  {
    return Menu::model()->findByPk($id)->delete();
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @param int $id
   * @return bool
   */
  public function menuExists( $id )
  {
    return Menu::model()->exists( 'id=?', array($id) );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Appends the MenuItem to this Menu as last child.
   * @param MenuItem $item
   * @return int the structure id. False on error.
   */
  public function addItemToMenu( $itemId, $menuId )
  {
    $item = MenuItem::model()->findByPk( $itemId );
    $menu = Menu::model()->findByPk( $menuId );
    return $menu->addItem( $item );
  }

  /////////////////////////////////////////////////////////////////////////////

  // @todo refactor - maybe see DOM. add as child of structureId or before/after structureId
  public function addItemToItem( $itemId, $targetItemId )
  {
    $item = MenuItem::model()->findByPk( $itemId );
    $targetItem = MenuItem::model()->findByPk( $targetItemId );
    return $targetItem->addItem( $item );
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
