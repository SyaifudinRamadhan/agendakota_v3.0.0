# Fetch

### POST

```js
post(URI, bodyFields)
.then(callback);
```

example :

```js
post("{{ route('user.register') }}", {
    name: "Riyan Satria",
    email: "riyan@gmail.com",
    csrfToken: "123abc" // opsional
})
.then(response => {
    if (response.status == 200) {
        alert("Berhasil terdaftar");
    }
});
```

### GET

```js
get(URI)
.then(callback)
```

example :

```js
get("{{ route('user.profile', 1) }}")
.then(user => {
    console.log(user.name);
}).
```