# KR World

Base minima del progetto impostata per `Aruba Hosting + MySQL Aruba`.

## Accesso locale

Apri il progetto da LAN usando l'IP host:

- `http://192.168.1.58/kr-world-next/`

Se usi QR in locale, devono puntare allo stesso host LAN e non a `localhost`.

## Configurazione

1. Copia `.env.example` in `.env`
2. Imposta `LAN_HOST` con l'IP attuale del PC host
3. Imposta i dati DB locali in base a XAMPP

## Struttura base

- `public/` ingresso pubblico
- `app/rendering/` rendering pubblico
- `app/admin/` admin operativo
- `app/sandbox/` sandbox/design lab
- `themes/templates/` template
- `themes/skins/` skin
- `assets/modules/` asset modulari
- `services/` servizi trasversali
- `automation/` automazioni
- `live-hub/` area live
- `storage/` file runtime
- `database/` materiale DB e valutazione legacy

## Avvio rapido

Con Apache/XAMPP:

- `http://192.168.1.58/kr-world-next/`

Con server PHP built-in:

- `C:\xampp\php\php.exe -S 0.0.0.0:8080 index.php`
- poi apri `http://192.168.1.58:8080/`
