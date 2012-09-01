<?php

/**
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
      'item' => array( self::BELONGS_TO, 'MenuItem', 'menuitemid' ),
    ));
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
