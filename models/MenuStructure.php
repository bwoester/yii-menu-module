<?php

/**
 * @property Menu $menu
 * @property MenuItem $item
 * @author Benjamin
 */
class MenuStructure extends MenuStructureBase
{

  /////////////////////////////////////////////////////////////////////////////

  public static $nestedSetBehavior = 'ext.behaviors.nested-set.NestedSetBehavior';

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return MenuStructure the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /////////////////////////////////////////////////////////////////////////////

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array();
	}

  /////////////////////////////////////////////////////////////////////////////

  public function behaviors()
  {
    return array(
      'nestedSetBehavior' => array(
        'class'         => self::$nestedSetBehavior,
        'hasManyRoots'  => true,
      ),
    );
  }

  /////////////////////////////////////////////////////////////////////////////

  public function relations()
  {
    return array_merge( parent::relations(), array(
      'menu'  =>  array( self::HAS_ONE, 'Menu', 'root_id' ),
      'item'  =>  array( self::BELONGS_TO, 'MenuItem', 'menuitemid' ),
    ));
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Inserts the menu item before the menu item represented by this node.
   *
   * @param MenuItem $item
   * @return mixed. int, id of the newly created node
   *                boolean false if the new node couldn't be inserted
   */
  public function insertBefore( MenuItem $item )
  {
    // validate $this represents a menu item
    if ($this->item instanceof MenuItem)
    {
      // create new menu structure and insert it before this.
      $node = new MenuStructure();
      $node->menuitemid = $item->id;

      return $node->insertBefore( $this )
        ? (int)$node->id
        : false;
    }

    throw new CException( "Can't insert before. MenuStructure '{$this->id}' is not associated with a menu item." );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Removed the menu item represented by the given node from the child list
   * of menu items of the menu item represented by this node.
   *
   * @param MenuItem $item
   * @return boolean whether the deletion is successful.
   */
  public function removeChild( MenuStructure $node )
  {
    // validate that $node is a child of $this
    if ($this->children()->exists('id=?',array($node->id)))
    {
      // delete the validated child and return the result
      return $node->deleteNode();
    }

    throw new CException( "Can't remove child. MenuStructure '{$node->id}' is not a child of MenuStructure '{$this->id}'." );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Appends the menu item as last child to the list of menu items of the menu
   * item represented by this node.
   *
   * @param MenuItem $item
   * @return mixed. int, id of the newly created node
   *                boolean false if the new node couldn't be inserted
   */
  public function appendChild( MenuItem $item )
  {
    $node = new MenuStructure();
    $node->menuitemid = $item->id;

    return $node->appendTo( $this )
      ? (int)$node->id
      : false;
  }

  /////////////////////////////////////////////////////////////////////////////

  public function getItems()
  {
    return MenuStructure::model()->with('item')->findAll(
      array(
        'condition' => 'root_id=?',
        'order'     => 'lft',
      ),
      array( $this->id )
    );
  }

  /////////////////////////////////////////////////////////////////////////////

}
