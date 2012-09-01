<?php

class DefaultController extends Controller
{

  /////////////////////////////////////////////////////////////////////////////

  public function actionIndex()
  {
    $mm = $this->getMenuModule();

    $idMenu1 = $mm->createMenu(array(
      'name' => 'Menu 1',
    ));

    $idMenu2 = $mm->createMenu(array(
      'name' => 'Menu 2',
    ));

    $idItem1 = $mm->createMenuItem(array(
      'label' => 'Created MenuItem 1',
    ));

    $idItem2 = $mm->createMenuItem(array(
      'label' => 'Created MenuItem 2',
    ));

    if (is_int($idMenu1))
    {
      $exists1  = $mm->menuExists( $idMenu1 );

      $error1 = $mm->createMenu(array(
        'name' => 'Menu 1',
      ));

      $idStruct1 = $mm->addItemToMenu( $idItem1, $idMenu1 );
      $idStruct2 = $mm->addItemToMenu( $idItem2, $idMenu1 );

      $deleted1 = $mm->deleteMenu( $idMenu1 );
      $exists1  = $mm->menuExists( $idMenu1 );
    }

    if (is_int($idMenu2)) {
      $exists2  = $mm->menuExists( $idMenu2 );

      $error2 = $mm->createMenu(array(
        'name' => 'Menu 2',
      ));

      $idStruct1 = $mm->addItemToMenu( $idItem1, $idMenu2 );
      $idStruct2 = $mm->addItemToMenu( $idItem2, $idMenu2 );

      $deleted2 = $mm->deleteMenu( $idMenu2 );
      $exists2  = $mm->menuExists( $idMenu2 );
    }

    $this->render('index');
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @return MenuModule
   */
  private function getMenuModule()
  {
    return $this->module;
  }

  /////////////////////////////////////////////////////////////////////////////

}