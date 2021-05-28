<?php
    $baglanti = mysqli_connect('localhost', 'root', '', 'yuntdaglitavukculuk');
    $json = array();

    if ($_POST["islem"] == "kullanici_giris") {
        $email = trim($_POST["email"]);
        $sifre = trim($_POST["sifre"]);
    
        $sorgu = mysqli_query($baglanti, "select id, isim from kullanicilar where e_mail = '$email' and sifre = '$sifre'");
        if (mysqli_num_rows($sorgu) > 0) {
            while ($sonuc = mysqli_fetch_assoc($sorgu)) {
                $json[] = $sonuc;
            }
        } else {
            $json[] = array(
                "sonuc" => "0"
            );
        }
    }

    if ($_POST["islem"] == "kullanici_kayit") {
        $isim = trim($_POST["isim"]);
        $e_posta = trim($_POST["email"]);
        $sifre = trim($_POST["sifre"]);

        try {
            $sorgu = mysqli_query($baglanti, "insert into kullanicilar(isim, e_mail, sifre)values('$isim', '$e_posta' , '$sifre')");
            if ($sorgu) {
                $json[] = array(
                    "sonuc" => "1"
                );
    
            } else {
                $json[] = array(
                    "sonuc" => "0"
                );
            }
        } catch (Exception $e) {
            $json[] = array(
                "sonuc" => "0"
            );
        }
    }

    echo json_encode($json);
    mysqli_close($baglanti);
?>
