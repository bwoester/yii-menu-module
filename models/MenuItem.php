<?php

/**
 * @author Benjamin
 */
class MenuItem extends MenuItemBase
{

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return MenuItem the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @TODO fix. maybe move method to MenuStructure...
   */
  public function addItem( MenuItem $item )
  {
    $menuStructure = new MenuStructure();
    $menuStructure->menuitemid = $item->id;
    return $menuStructure->appendTo( $this->structure );  // TATA menu items don't have a structure.
  }

  /////////////////////////////////////////////////////////////////////////////

}
