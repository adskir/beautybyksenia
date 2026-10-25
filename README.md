# beautybyksenia.com

Sito statico di Beauty by Ksenia (permanent makeup, Brooklyn NYC). Nessun build: i file della radice
vengono pubblicati così come sono.

- Copia esatta del sito live su Namecheap (`public_html`), presa il 25.10.2026 prima del trasloco su OVH.
  Esclusi: `ktip/` (vecchia copia di ktiphairextension.com, ora repo `adskir/ktip`), `cgi-bin/`, `nc_assets/` (file di Namecheap).
- Deploy: push su `main` → webhook GitHub → OVH (Hosting → Multisito, cartella `beautybyksenia`).
- Modulo: `send.php` (PHP `mail()` verso info@beautybyksenia.com).

## Problemi noti del sito live (da sistemare dopo il trasloco)
- Nessun Google Analytics / GTM sulle pagine attuali (dal 28.06.2026): i dati si fermano a luglio.
- Pagine interne (`/services/*`, `/gallery/`, `/reviews/`) caricano le foto da `../images/`, cartella che non esiste → foto rotte.
- Link nel footer `/privacy/`, `/do-not-sell/`, `/blog/` → 404 (i file reali sono `privacy-en.html`, `do-not-sell-or-share.html`, il blog non esiste).
- Recensioni e rating (5.0, 47 recensioni) da verificare con Ksenia: tenere solo quelle reali.
- Numeri incoerenti tra le pagine (20K+ / 1K+ clienti, sconto $100 / $300, due telefoni).
