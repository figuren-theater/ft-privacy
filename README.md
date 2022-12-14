# figuren.theater | Privacy

Privacy first! And this is the code that does it. Curated for the WordPress Multisite [figuren.theater](https://figuren.theater).

---

## Plugins included

This package contains the following plugins. 
Thoose are completely managed by code and lack of their typical UI.

* [Embed Privacy](https://wordpress.org/plugins/embed-privacy/#developers)
* [compressed-emoji: 😬 👉 😑](https://github.com/mustafauysal/compressed-emoji/)
    Same emoji, but compressed. It helps to serve emoji via your server.
* [Koko Analytics](https://wordpress.org/plugins/koko-analytics/#developers)
* [Surbma | GDPR Multisite Privacy](https://wordpress.org/plugins/surbma-gdpr-multisite-privacy/#developers)


## What does this package do in addition?

Accompaniying the core functionality of the mentioned plugins, theese **best practices** are included with this package.

* Load 3rd-party fonts, especially 'Google fonts', locally.


Add the following to your composer project:

```
"extra": {
    "dropin-paths": {
        "content/": [
            "package:figuren-theater/ft-privacy:inc/koko-analytics/k.php"
        ]
    }
}
```
