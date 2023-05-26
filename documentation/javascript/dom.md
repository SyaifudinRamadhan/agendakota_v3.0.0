# DOM

### querySelector

```js
let button = select("#idButton");
```

### querySelectorAll

```js
let buttons = selectAll(".buttons").forEach(button => {
    console.log(button);
});
```

### createElement

```js
createElement({
    el: 'div',
    attributes: [
        ['class', 'bg-merah'],
        ['onclick', 'removeUser()']
    ],
    createTo: 'body'
})
```

### Background Image

Agar tidak penyet, gambar diletakkan pada div melalui atribut bg-image.

```
<div class="cover" bg-image="tes.jpg">
```

Pada awal reload, `base.js` akan menjalankan function `bindDivWithImage()` untuk mengatur background pada setiap div yang memiliki atribut `bg-image` dan source nya diambil dari value atribut itu.

Kalau ada proses bikin div baru, yang terjadi setelah reload, yang terjadi pada user action, maka kamu harus menjalankan `bindDivWithImage()` manual setelah `createElement()`.