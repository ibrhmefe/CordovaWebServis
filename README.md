![resim](https://raw.githubusercontent.com/ibrhmefe/CordovaWebServis/main/apache%20logo.png)

Uygulamamız içerisinde kullanıcı giriş ekranı tasarlıyoruz. Peki ya kullanıcılar nerede?

MySql’de bulunan kulanıcılar tablosunda sorgu yapmamız gerekiyor. Bunun için benim kullandığım yol “Ajax” fonksiyonu ile web servisimize bilgilerimizi yollayıp, kontroller sağlandıktan sonra geri dönüt almak.


Öncelikle tablomuzu tasarlayalım.

![resim](https://raw.githubusercontent.com/ibrhmefe/CordovaWebServis/main/resim1.jpg)

Gerekli alanlarımız yalnızca bunlar. Web servisler hakkında bilginiz yoksa, Web Servis Nedir? isimli blogumda paylaştığım notlara baktıktan sonra devam etmenizi öneririm.


## Web Servis ( baglan.php )

```
<?php
$baglanti = mysqli_connect('localhost', 'root', '', 'firsApp');
$json = array();
mysqli_query($baglanti, "set names utf8");

if ($_POST["islem"] == "kullanici_giris") {
    $email = trim($_POST["email"]);
    $sifre = trim($_POST["sifre"]);

    $sorgu = mysqli_query($baglanti, "select id, isim from kullanicilar where e_posta = '$email' and sifre = '$sifre'");
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

echo json_encode($json);
mysqli_close($baglanti);
?>

```

### Kodları açıklayalım


Temel olan kısımları anlatmadan geçiyorum. Sayfamıza post methoduyla gelen bir kullanici_giris işlemi varsa, gelen email ve şifre verilerimi “trim()” fonksiyonu ile sağında ve solundaki boşlukları silerek değişkenlerime atıyorum. Kullanicilar tablomun içerisinde hem şifresi hem de email adresi eşleşen kayıt varsa, bu kaydın id’sini ve isim değerini alarak json değişkenine aktarıp ekrana basıyorum.

## Cordova ( index.html )

Öncelikle ekrana email ve şifre yazabilmesi için form elemanlarımı ekliyorum. Button sayfayı yenilediği için kullanmadım. btnGiris isimli div’e tıklanma vereceğim.
```
<form id="kullanici_giris_form" method="post" action="#">
            E-mail adresin
            <input type="email" id="email" required>
            Şifren
            <input type="password" id="sifre" required>
            <div style="background: yellow; width: 65px;" id="btnGiris">Giriş Yap</div>
</form>
```

Ajax fonksiyonumuz ile gerekli verileri baglan.php sayfasına göndererek sorgulamamı yapıyorum. Dönen verim “0” ise bir hata oluşmuştur. Değilse zaten kullanıcı id ve ismi gelmiştir. Bu değerleri alert() fonksiyonuyla ekranda gösteriyorum.

```
<script>
    var url = "http://192.168.1.8/CordovaWebServis/baglan.php";
    /* Web servisimizin bulunduğu urlyi oluşturduk. */
    $(document).ready(function () {
        /* Jquery kodlarını çalıştırmamız için gerekli kod. Sayfanın hazır olduğunu kontrol ediyoruz. */
        $('#btnGiris').click(function(){
            /* btnGiris idli butona tıkladığımızda ajax fonksiyonumuzu çalıştırıyoruz. */
            var email = $('#email').val();
            var sifre = $('#sifre').val();
            $.ajax({
                type: "post",
                url: url,
                dataType: "json",
                data: {"islem": "kullanici_giris", "email": email, "sifre": sifre},
                success: function (cevap) {
                    if(cevap[0].sonuc == "0"){
                        // Hata oluştu
                        alert("Bir Hata Oluştu!");
                    } else {
                        /* İşlem başarılı */
                        alert(cevap[0].id + " / " + cevap[0].isim);
                    }
                }
            });
        });
    });
</script>
```

Cordova ile en basit halde web servise veri göndermeyi ve geriye veri almayı öğrenmiş olduk. Bundan sonra kodlarda ufak değişiklikler ile istediğiniz veriyi alabilirsiniz. Web servis ve Ajax daha önceden anlattığım bir konu olduğu için kodları yüzeysel olarak anlattım. Bir sonraki blogda kullanıcı eklemeyi, güncellemeyi ve silmeyi anlatacağım.