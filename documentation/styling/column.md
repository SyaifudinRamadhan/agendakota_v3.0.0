# Column

## Wrapper

Wrapper di sini berarti margin, secara default sebesar 5% di masing-masing sisi. Dan jika ditambahkan class "super", maka marginnya sebesar 10%.

### Contoh
```
<div class="wrap">
    Margin 5%
</div>

<div class="wrap super">
    Margin 10%
</div>
```

## Bagi

|class|width|example|
|-----|-----|-------|
|bagi bagi-2|50%|`<div class="bagi bagi-2"></div><div class="bagi bagi-2"></div>`|
|bagi bagi-3|33.333%|`<div class="bagi bagi-3"></div><div class="bagi bagi-2"></div>`|
|bagi bagi-4|25%|`<div class="bagi bagi-4"></div><div class="bagi bagi-2"></div>`|
|bagi bagi-5|20%|`<div class="bagi bagi-5"></div><div class="bagi bagi-2"></div>`|


#### Membuat Card

```
<div class="bagi bagi-3">
    <div class="wrap">
        <div class="bg-putih rounded bayangan-5 smallPadding">
            <div class="wrap">
                Konten card
            </div>
        </div>
    </div>
</div>
```

*smallPadding ditambahkan agar margin bekerja semestinya. Kalau tanpa itu margin-top dan margin-bottom tetap 0%.

## Lebar

|class|width|example|
|-----|-----|-------|
|lebar-10|10%|`<div class="lebar-10">Lebar 10%</div>`|
|lebar-20|20%|`<div class="lebar-20">Lebar 20%</div>`|
|lebar-30|30%|`<div class="lebar-30">Lebar 30%</div>`|
|lebar-40|40%|`<div class="lebar-40">Lebar 40%</div>`|
|lebar-50|50%|`<div class="lebar-50">Lebar 50%</div>`|
|lebar-60|60%|`<div class="lebar-60">Lebar 60%</div>`|
|lebar-70|70%|`<div class="lebar-70">Lebar 70%</div>`|
|lebar-80|80%|`<div class="lebar-80">Lebar 80%</div>`|
|lebar-90|90%|`<div class="lebar-90">Lebar 90%</div>`|
|lebar-100|100%|`<div class="lebar-100">Lebar 100%</div>`|

*di mobile bagi dan lebar akan secara otomatis 100%

## Tinggi

|class|height|example|
|-----|-----|-------|
|tinggi-`{5-600}`|5 - 600px|`<div class="tinggi-250">Tinggi 250px</div>`|

Ketentuan :

- 5 - 100 => kelipatan 5 (misal: 5, 10, 15, 20, ... 100)
- 100 - 600 => kelipatan 50 (misal: 200, 250, 300, ... 600)