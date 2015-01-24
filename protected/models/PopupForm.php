<?php

/**
 * Description of PopupForm
 *
 * @author Andrew
 * 
 */
class PopupForm extends CFormModel {

    /** @var string */
    public $email;
    
    /** @var boolean */
    public $accept;

    public function rules() {
        return array_merge(parent::rules(), array(
            array('email', 'required'),
            array('email', 'email'),
            array('accept', 'boolean'),
            array('accept', 'compare', 'compareValue' => 1, 'message' => 'Требуется согласие на получение новостей'),
        ));
    }

    public function attributeLabels() {
        return [
            'email' => 'Ваш email:',
            'accept' => 'Я согласен(на) получать новости.'
        ];
    }

    public function afterConstruct() {
        $this->accept = TRUE;
        parent::afterConstruct();
    }

}
