<?php 

class Universite  
{
    function UniversiteList($DonemId)
{
    $url = "http://ogr.kocaeli.edu.tr/KOUBS/Istatistik/NotDUniversite_Bologna.cfm";

    $connect = curl_init($url);
    curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($connect, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
    curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connect, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($connect, CURLOPT_POSTFIELDS, array('Donem' => $DonemId));
    curl_setopt($connect, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($connect);
    preg_match('@<select name="Universite" (.*?)>(.*?)</select>@si', $response, $cikti);

    //    print_r($cikti[0]);

    $options = $cikti[0];
    $UniversiteData = array();
    //print_r($options);
    preg_match_all('@<option(.*?)value="(.*?)">(.*?)</option>@si', $options, $matches, PREG_SET_ORDER);
    //        print_r($matches);
    foreach ($matches as $val) {
        $UniversiteData[$val[2]] = $val[3];
        //        print_r($val);
    }

    return $UniversiteData;
}

function getUniversiteAdi($DonemId,$UniversiteId)
{
    $uniList = $this->UniversiteList($DonemId,$UniversiteId);
    $uniAdi = "";
    foreach ($uniList as $id => $value) {
        if($id == $UniversiteId){
            $uniAdi = $value;
            break;
        }
    }
    return $uniAdi;
}
}
