<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
require_once "Libs/Donem.php";
require_once "Libs/Ders.php";
require_once "Libs/Bolum.php";
require_once "Libs/Fakulte.php";
require_once "Libs/Universite.php";
require_once "Libs/Istatistik.php";

$donem          = new Donem();
$universite     = new Universite();
$fakulte        = new Fakulte();
$bolum          = new Bolum();
$ders           = new Ders();
$istatistik     = new Istatistik();



//echo json_encode($istatistik->IstatistikList("2021G","1","02","0201","891604"));
//echo json_encode($ders->DersListesi("2021G","1","02","0201"));
//echo json_encode($bolum->BolumList("2021G","1","02"));

//echo json_encode($fakulte->FakulteList("2021G","1"));
//echo json_encode($universite->UniversiteList("2021B"));
//echo json_encode($donem->DonemList());

if (isset($_GET["donem"]) && isset($_GET["universite"]) && isset($_GET["fakulte"])  && isset($_GET["bolum"]) && isset($_GET["ders"]) && isset($_GET["istatistik"])) {
    echo json_encode($istatistik->IstatistikList($_GET["donem"], $_GET["universite"], $_GET["fakulte"], $_GET["bolum"],$_GET["ders"]));
}
else if (isset($_GET["donem"]) && isset($_GET["universite"]) && isset($_GET["fakulte"])  && isset($_GET["bolum"]) && isset($_GET["ders"])) {
    echo json_encode(
        $ders->DersListesi(
            $_GET["donem"], 
            $_GET["universite"], 
            $_GET["fakulte"], 
            $_GET["bolum"])
        );
} else if (isset($_GET["donem"]) && isset($_GET["universite"]) && isset($_GET["fakulte"])  && isset($_GET["bolum"])) {
    echo json_encode($bolum->BolumList($_GET["donem"], $_GET["universite"], $_GET["fakulte"]));
} else if (isset($_GET["donem"]) && isset($_GET["universite"]) && isset($_GET["fakulte"])) {
    echo json_encode($fakulte->FakulteList($_GET["donem"], $_GET["universite"]));
} else if (isset($_GET["donem"]) && isset($_GET["universite"])) {
    echo json_encode($universite->UniversiteList($_GET["donem"]));
} else if (isset($_GET["donem"])) {
    echo json_encode($donem->DonemList());
}

