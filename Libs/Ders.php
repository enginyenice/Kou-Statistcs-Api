<?php
class Ders  
{
    public function DersListesi($DonemId,$UniversiteId,$FakulteId,$BolumId)
    {
        $url = "http://ogr.kocaeli.edu.tr/KOUBS/Istatistik/NotDUniversite_Bologna.cfm";
    
        $connect = curl_init($url);
        curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($connect, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
        curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connect, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($connect, CURLOPT_POSTFIELDS, array('Donem' => $DonemId, 'Universite' => $UniversiteId, 'fakulte' => $FakulteId, 'Bolum' => $BolumId));
        curl_setopt($connect, CURLOPT_FOLLOWLOCATION, 1);
    
        $response = curl_exec($connect);
        preg_match('@<select name="Ders" (.*?)>(.*?)</select>@si', $response, $cikti);
    
        //      print_r($cikti[0]);
    
        $options = $cikti[0];
        $UniversiteData = array();
        //print_r($options);
        preg_match_all('@<option(.*?)value="(.*?)">(.*?)</option>@si', $options, $matches, PREG_SET_ORDER);
        //        print_r($matches);
        foreach ($matches as $val) {
            if ($val[2] != "default") {
                $dersAdi = explode("-",$val[3]);
                $UniversiteData[$val[2]] = $dersAdi[1]." - ".$dersAdi[2];
            }
            //        print_r($val);
        }
    
        return $UniversiteData;
    }

    public function getDersAdi($DonemId,$UniversiteId,$FakulteId,$BolumId,$DersId){
        $dersList = $this->DersListesi($DonemId,$UniversiteId,$FakulteId,$BolumId,$DersId);
        $dersAdi = "";
        foreach ($dersList as $id => $value) {
            
            if($id == $DersId){
                $dersAdi = $value;
                break;
            }
        }
        return $dersAdi;
    }
}
