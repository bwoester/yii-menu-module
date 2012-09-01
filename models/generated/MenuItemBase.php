<?php

/**
 * This is the model class for table "menuitem".
 *
 * The followings are the available columns in table 'menuitem':
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $visible
 * @property integer $active
 * @property string $template
 * @property string $linkOptions
 * @property string $itemOptions
 * @property string $submenuOptions
 */
class MenuItemBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuItemBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menuitem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('visible, active', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>255),
			array('url, template, linkOptions, itemOptions, submenuOptions', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, url, visible, active, template, linkOptions, itemOptions, submenuOptions', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'url' => 'Url',
			'visible' => 'Visible',
			'active' => 'Active',
			'template' => 'Template',
			'linkOptions' => 'Link Options',
			'itemOptions' => 'Item Options',
			'submenuOptions' => 'Submenu Options',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('active',$this->active);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('linkOptions',$this->linkOptions,true);
		$criteria->compare('itemOptions',$this->itemOptions,true);
		$criteria->compare('submenuOptions',$this->submenuOptions,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}