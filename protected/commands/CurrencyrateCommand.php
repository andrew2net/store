<?php

/**
 * Description of CurrencyrateCommand
 *
 */
class CurrencyrateCommand extends CConsoleCommand {

  public function run($args) {
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
    if (is_null($currencyRate)) {
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
