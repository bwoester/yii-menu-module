<?php

/**
 * @property MenuStructure $node
 * @author Benjamin
 */
class Menu extends MenuBase
{

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Menu the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /////////////////////////////////////////////////////////////////////////////

	public function rules()
	{
    return array_merge( parent::rules(), array(
      array( 'name', 'unique' ),
    ));
  }

  /////////////////////////////////////////////////////////////////////////////

  public function relations()
  {
    return array_merge( parent::relations(), array(
      'node' => array( self::BELONGS_TO, 'MenuStructure', 'rootid' ),
    ));
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Before validating the Menu instance, create an associated MenuStructure
   * for it if it is a new record.
   *
   * @return boolean
   */
  protected function beforeValidate()
  {
    $retVal = parent::beforeValidate();

    if ($retVal && $this->isNewRecord)
    {
      $node = new MenuStructure();

      if ($node->saveNode())
      {
        $rootId = $node->id;

        $this->rootid = $rootId;
        $this->node   = $node;
      }
      else {
        $retVal = false;
      }
    }

    return $retVal;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Cleanup if validation failed
   */
  protected function afterValidate()
  {
    parent::afterValidate();

    if ($this->isNewRecord && $this->hasErrors() && $this->node instanceof MenuStructure)
    {
      $this->node->deleteNode();
      $this->node   = null;
      $this->rootid = null;
    }
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Cleanup if saving failed
   */
  public function save($runValidation=true,$attributes=null)
  {
    $newRecord  = $this->isNewRecord;
    $saved      = parent::save( $runValidation, $attributes );

    if ($newRecord && !$saved && $this->node instanceof MenuStructure)
    {
      $this->node->deleteNode();
      $this->node   = null;
      $this->rootid = null;
    }

    return $saved;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Cleanup structure on delete
   */
  public function delete()
  {
    $deleted = parent::delete();

    if ($deleted)
    {
      $this->node->deleteNode();
      $this->node   = null;
      $this->rootid = null;
    }

    return $deleted;
  }

  /////////////////////////////////////////////////////////////////////////////

}
