<?php

/**
 * Sample Yandex.Market Yml generator
 */

class SYmlGenerator extends YmlGenerator {
    
    protected function shopInfo() {
        return array(
            'name'=>'',
            'company'=>'',
            'url'=>'',
            'platform'=>'',
            'version'=>'',
            'agency'=>'',
            'email'=>''
      );
    }
    
    protected function currencies() {
        $currencies = '...';
        foreach($currencies as $currecy) {
            $this->addCurrency($id,$rate);
        }
    }
    
    protected function categories() {
        $categories = '...';
        foreach($categories as $category) {
            $this->addCategory($name,$id,$parentId);
        }  
    }
    
    protected function offers() {
        $offers = '...';
        foreach($offers as $offer) {
            $this->addOffer($id,$data, $params, $available, $type, $bid, $cbid);
        }
    }
}
