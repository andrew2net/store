<?php

/**
 * Description of DalyCommand
 *
 */
class DalyCommand extends CConsoleCommand {

  public function run($args) {
    $this->getNrjLocations();
  }

  private function getNrjLocations() {
    $command = Yii::app()->db->createCommand();
    /* @var $command CDbCommand */

    //get Energy locations
    $nrj = $command->select('id')->from('store_delivery')->where('zone_type_id=3')->queryRow();
    if ($nrj) {
      $tr = Yii::app()->db->beginTransaction();
      try {
        $nrj_ch = curl_init('http://api.nrg-tk.ru/api/rest/?method=nrg.get.locations');
        curl_setopt($nrj_ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($nrj_ch, CURLOPT_HEADER, FALSE);
        $nrj_get = curl_exec($nrj_ch);
        curl_close($nrj_ch);
        $locations = json_decode($nrj_get, TRUE);
        if (isset($locations['rsp']) && $locations['rsp']['stat'] == 'ok') {
          $command->reset();
          $command->delete('nrj_locations');
          foreach ($locations['rsp']['locations'] as $l) {
            $command->reset();
            $command->insert('nrj_locations', array('id' => $l['id'], 'name' => $l['name']));
          }
        }
        $tr->commit();
      } catch (Exception $exc) {
        $tr->rollback();
      }
    }
  }

  private function getCurrencyRate() {
    $wsdl = 'http://www.cbr.ru/dailyinfowebserv/dailyinfo.asmx?WSDL';
    $client = new SoapClient($wsdl);
    $params["On_date"] = date('Y-m-d');
    $result = $client->GetCursOnDate($params);
    
    $xml = new SimpleXMLElement($result->GetCursOnDateResult->any);
    $currencyData = $xml->xpath("//ValuteCursOnDate[VchCode='KZT']");
    if (!isset($currencyData[0]))
      return;
    
    $rate = (string) $currencyData[0]->Vcurs;
    $quantity = (string) $currencyData[0]->Vnom;
    
    Yii::import('application.modules.payments.models.CurrencyRate');
    $currencyRate = CurrencyRate::model()->findByAttributes(array(
      'date' => $params['On_date'],
      'from' => 'KZT',
      'to' => 'RUB',
      ));
    if (is_null($currencyRate)){
      $currencyRate = new CurrencyRate;
      $currencyRate->date = $params['On_date'];
      $currencyRate->from = 'KZT';
      $currencyRate->to = 'RUB';
    }
    $currencyRate->from_quantity = $quantity;
    $currencyRate->to_quantity = 1;
    $currencyRate->rate = $rate;
    $currencyRate->save();
  }

}

?>
