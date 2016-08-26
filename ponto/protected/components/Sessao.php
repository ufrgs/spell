<?php
class Sessao extends CFilter {
  public $modelos = array("id_pessoa" => "Pessoa");

  public function preFilter($filterChain) {
    if(is_null(Yii::app()->user->getId())) {
      $id = new Identidade();
      $id->defineModelos($this->modelos);
      if($id->authenticate()) {
        Yii::app()->user->login($id);
      } else {
        throw new CHttpException(403, "Sessão expirada.");
      }
    }
    return true;
  }
}
