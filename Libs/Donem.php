<?php

class Donem
{
    public function DonemList()
{
    $url = "http://ogr.kocaeli.edu.tr/KOUBS/Istatistik/NotDUniversite_Bologna.cfm";

    $connect = curl_init($url);
    curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($connect, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
    curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connect, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($connect);
    preg_match('@<select name="Donem" (.*?)>(.*?)</select>@si', $response, $cikti);


    $options = $cikti[0];
    //print_r($options);
    $DonemData = array();


    preg_match_all('@<option(.*?)value="(.*?)">(.*?)</option>@si', $options, $matches, PREG_SET_ORDER);
    //print_r($matches);
    foreach ($matches as $val) {
        $DonemData[$val[2]] = $val[3];
    }


    return $DonemData;
}
public function getDonemAdi($DonemId)
{
    $donemList = $this->DonemList();
    $donemAdi = "";
    foreach ($donemList as $id => $value) {
        if($id == $DonemId){
            $donemAdi = $value;
            break;
        }
    }
    return $donemAdi;
}
}
