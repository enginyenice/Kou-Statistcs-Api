<?php
require_once "Libs/Donem.php";
require_once "Libs/Ders.php";
require_once "Libs/Bolum.php";
require_once "Libs/Fakulte.php";
require_once "Libs/Universite.php";
class Istatistik  
{
    public function IstatistikList($DonemId,$UniversiteId,$FakulteId,$BolumId,$DersId)
{
    $donemClass = new Donem();
    $Donem =  $donemClass->getDonemAdi($DonemId);

    $universiteClass = new Universite();
    $Universite =  $universiteClass->getUniversiteAdi($DonemId,$UniversiteId);

    $fakulteClass = new Fakulte();
    $Fakulte = $fakulteClass->getFakulteAdi($DonemId,$UniversiteId,$FakulteId);

    $bolumClass = new Bolum();
    $Bolum = $bolumClass->getBolumAdi($DonemId,$UniversiteId,$FakulteId,$BolumId);

    $dersClass = new Ders();
    $Ders = $dersClass->getDersAdi($DonemId,$UniversiteId,$FakulteId,$BolumId,$DersId);
    
    $DersDetay = array(
        "Donem"         => $Donem,
        "Universite"    => $Universite,
        "Fakulte"       => $Fakulte,
        "Bolum"         => $Bolum,
        "Ders"          => $Ders
    );
    $url = "http://ogr.kocaeli.edu.tr/KOUBS/Istatistik/NotDUniversite_Bologna.cfm";

    $connect = curl_init($url);
    curl_setopt($connect, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($connect, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; tr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12");
    curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connect, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($connect, CURLOPT_POSTFIELDS, array('Donem' => $DonemId, 'Universite' => $UniversiteId, 'fakulte' => $FakulteId, 'Bolum' => $BolumId, 'Ders' => $DersId, 'Ara' => 'Göster'));
    curl_setopt($connect, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($connect);
    //var_dump($response);


    //T-Not Aralik
    $TNotAralik = $this->TNotAralik($response);
    //Ana Tablo    
    $GenelIstatistik = $this->GenelIstatistik($response);


    $TumVeriler = array(
        "DersDetay"         => $DersDetay,
        "TNotAralik"        => $TNotAralik,
        "GenelIstatistik"   => $GenelIstatistik
    );
    return $TumVeriler;
    
}

public function TNotAralik($response) {
    $TNotAralik = Array();
    preg_match_all('@<div class="col-lg-1 bg-primary font-weight-bolder " (.*?)>(.*?)</div>@si', $response, $cikti);
    //<div class="col-lg-12 bg-primary" style="padding: 0px; margin-bottom: 0px; border-top: dotted 1px #CCC; display: inline-flex;">
    preg_match_all('@<div class="col-lg-1" align="center">(.*?)</div>@si', $response, $harfler, PREG_SET_ORDER);
    //print_r($harfler);
    foreach($cikti[0] as $key=>$aralik){
        $aralik = strip_tags($aralik);
        $harf = strip_tags($harfler[$key][0]);
        $TNotAralik[$harf] = $aralik;
    }
    return $TNotAralik;
}

public function GenelIstatistik($response)
{
    
    
    preg_match('@<table align="center" border="1">(.*?)</table>@si', $response, $cikti);

    //print_r($cikti);
    //print_r($cikti);
    $table = $cikti[0];
    preg_match_all('@<tr(.*?)>(.*?)<tr(.*?)>(.*?)</tr>(.*?)</tr>@si', $table, $data, PREG_SET_ORDER);
   // print_r($data);

    //Sınıf Ortalamasi ve T Notu
    $SinifOrt = $this->SinifOrt($data);
    $TNotu = $this->TNotu($data);
    //Harf Öğrenci Sayısı Dağılımı
    $OgrenciDagilimi = $this->NotOgrenciDagilimi($data);


    $GenelOrtalama = array(
        "SinifOrtalamasi"   => $SinifOrt,
        "TNotu"             => $TNotu,
        "OgrenciDagilimi"   => $OgrenciDagilimi,
    );

    return $GenelOrtalama;
    
}

public function SinifOrt($data)
{
    $SinifOrtalamasiFull = $data[2][4];
    return str_replace("Dersin/Derslerin Sınıf Ortalaması =&nbsp;", "", strip_tags($SinifOrtalamasiFull));
}

public function TNotu($data)
{
    $TNotuFull = $data[2][5];
    return str_replace("\r\n            Dersin/Derslerin Standart Sapması =&nbsp;", "", strip_tags($TNotuFull));
}

public function NotOgrenciDagilimi($data)
{



    //print_r($data[1][0]);
    $harfOgrenciSayisiDagilimi = $data[1][0];
    preg_match_all('@<td align="center"><b>(.*?)</b></td>@si', $harfOgrenciSayisiDagilimi, $harfler, PREG_SET_ORDER);
    //print_r($harfler);
    preg_match_all('@<td align="center">(.*?)</td>@si', $harfOgrenciSayisiDagilimi, $sayilar, PREG_SET_ORDER);
    //print_r($sayilar);



    $OgrenciDagilimi = array();
    foreach ($harfler as $key => $harf) {
        $OgrenciDagilimi[$harf[1]] = intval($sayilar[$key + 15][1]);
    }
    return $OgrenciDagilimi;
}
}
