# Database

## Base attuale

Il database ora nasce da zero sul testo master del progetto.

Schema iniziale:

- [001_kr_world_initial.sql](/abs/c:/xampp/htdocs/kr-world-next/database/migrations/001_kr_world_initial.sql)

## Aree dati coperte

- utenti e ruoli admin
- impostazioni di sistema
- sorgenti dati bindabili
- tipi contenuto e famiglie
- template e skin
- pagine/eventi/chart/recap/live hub come entita' amministrabili
- blocchi/moduli di pagina
- media con crop/focus/usage
- tassonomie e tag controllati
- relazioni tra contenuti
- menu e navigazioni
- sandbox/design models e versioni
- binding sandbox verso dati reali
- sessioni e richieste live hub

## Principi

- template = struttura
- skin = veste
- contenuti e blocchi separati
- binding dati trattato come dato strutturato
- live hub separato dal pubblico
- schema volutamente estensibile, non monolitico
