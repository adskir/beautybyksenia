# beautybyksenia.com

Sito statico di Beauty by Ksenia (permanent makeup, Brooklyn NYC). Nessun build: i file della radice
vengono pubblicati così come sono.

- Copia esatta del sito live su Namecheap (`public_html`), presa il 25.10.2026 prima del trasloco su OVH.
  Esclusi: `ktip/` (vecchia copia di ktiphairextension.com, ora repo `adskir/ktip`), `cgi-bin/`, `nc_assets/` (file di Namecheap).
- Deploy: push su `main` → webhook GitHub → OVH (Hosting → Multisito, cartella `beautybyksenia`).
- Modulo: Formspree `xvkkkpdp` (AJAX), evento `generate_lead` nel dataLayer al successo.
- Tracciamento: GTM `GTM-MS58TGBL` su tutte le pagine + `/track.js` (`whatsapp_click`, `instagram_click`). I link WhatsApp hanno il testo precompilato «…found you on beautybyksenia.com…» per contare le richieste dal sito.
- SEO: canonical e og su tutte le pagine, `robots.txt`, `sitemap.xml`. Le pagine usano le immagini `.webp` (i `.jpg/.png` restano per og:image).

## Stato (23.11.2026, batch 1)
- Prezzi: $199 per zona, ritocco $149 (uguale per sopracciglia, labbra, eyeliner).
- 35 anni di esperienza, 20K+ clienti (confermati da Ksenia).
- Telefono nascosto dalle pagine: solo WhatsApp +1 646 951 6222 e modulo (resta in JSON-LD per Google).
- Recensioni: solo quelle reali dagli screenshot Instagram `recenz1–5`, testo letterale; rimossi rating 5.0/47 e markup AggregateRating.
