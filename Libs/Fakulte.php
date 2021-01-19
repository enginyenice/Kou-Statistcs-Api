<?php
class Fakulte  
{
    public function FakulteList($DonemId,$UniversiteId)
{
    $url = "http://ogr.kocaeli.edu.tr/KOUBS/Istatistik/NotDUniversite_Bologna.cfm";

    $connect = curl_init($url);
    curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($connect, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
    curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connect, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($connect, CURLOPT_POSTFIELDS, array('Donem' => $DonemId, 'Universite' => $UniversiteId));
    curl_setopt($connect, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($connect);
    preg_match('@<select name="fakulte" (.*?)>(.*?)</select>@si', $response, $cikti);

    // print_r($cikti[0]);

    $options = $cikti[0];
    $UniversiteData = array();
    //print_r($options);
    preg_match_all('@<option(.*?)value="(.*?)">(.*?)</option>@si', $options, $matches, PREG_SET_ORDER);
    //print_r($matches);
    foreach ($matches as $val) {
        if ($val[2] != "default") {
            $UniversiteData[$val[2]] = $val[3];
        }
    }

    return $UniversiteData;
}


public function getFakulteAdi($DonemId,$UniversiteId,$FakulteId){
    $fakulteList = $this->FakulteList($DonemId,$UniversiteId,$FakulteId);
    $fakulteAdi = "";
    foreach ($fakulteList as $id => $value) {
        if($id == $FakulteId){
            $fakulteAdi = $value;
            break;
        }
    }
    return $fakulteAdi;
}
}
