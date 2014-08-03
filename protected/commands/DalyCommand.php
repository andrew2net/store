<?php

/**
 * Description of DalyCommand
 *
 */
class DalyCommand extends CConsoleCommand {

  public function run($args) {
    list($action, $options, $args) = $this->resolveRequest($args);
    if (isset($options['connectionID']))
      $db = Yii::app()->$options['connectionID'];
    else
      $db = Yii::app()->db;
    $command = $db->createCommand();
    /* @var $command CDbCommand */

    //get Energy locations
    $nrj = $command->select('id')->from('store_delivery')->where('zone_type_id=2')->queryRow();
    if ($nrj) {
      $tr = $db->beginTransaction();
      echo 'tr\n';
      try {
        echo 'try\n';
        $nrj_ch = curl_init('http://api.nrg-tk.ru/api/rest/?method=nrg.get.locations');
        echo 'curl\n';
        curl_setopt($nrj_ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($nrj_ch, CURLOPT_HEADER, FALSE);
        $nrj_get = curl_exec($nrj_ch);
        echo 'nrj';
        echo $nrj_get;
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

}

?>
