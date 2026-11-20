# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- **Primary users:** The people of Capiz — patients, families, and citizens across Roxas City and all municipalities of the Province of Capiz visiting the portal for hospital rates, emergency/clinical schedules, advisories, and contact details.
- **Secondary users:** Job applicants browsing hospital vacancies (`/careers`) and internal hospital staff (`Super Admin (ICT)`, `Pricelist Encoder`, `Public Info Officer`) managing content and Citizen's Charter rates in the IP-restricted `/admin` panel.

## Product Purpose

Capiz Medical Center's official web portal exists to deliver ARTA Citizen's Charter fee transparency, cut down routine front-desk and phone inquiries, and build public trust through clear, authoritative, and accessible public hospital information.

## Positioning

The official digital front door of Capiz's 147-bed Level II Government Hospital (*"Pag-atipan nga may gugma"*), pairing a searchable 8-section Citizen's Charter pricelist and downloadable/printable PDF fee sheets directly with 24/7 emergency trunklines, medical services directories, official advisories, and an RA 10173-compliant inquiry desk.

## Operating Context

- Public visitors often access the portal on mobile devices or varying network conditions across Roxas City and Capiz municipalities to check diagnostic/room fees, clinic hours, or emergency numbers before traveling to the campus at Sitio Nasagud, Brgy. Lanot, Roxas City.
- Visitors and front-desk workflows rely on printable views (`@media print`) and downloadable PDF fee schedules (`/pricelist/pdf`, `/pricelist/{slug}/pdf`) for Citizen's Charter transparency.
- Hospital staff update site settings, services, advisories, job postings, inquiries, and bulk Excel pricelist imports through the Filament 3 `/admin` panel behind `RestrictAdminIp`.

## Capabilities and Constraints

- **Application stack (existing):** Laravel 13, Blade templates, Tailwind CSS v4, Alpine.js, locally bundled `@fontsource/inter` fonts, Filament 3 (`/admin`), DomPDF (`barryvdh/laravel-dompdf`), and Laravel Excel (`maatwebsite/excel`).
- **Public surface routes:** Home (`/`), Medical Services (`/services`), Interactive Citizen's Charter Pricelist (`/pricelist`) + PDF exports, About CMC (`/about`), Hospital Advisories (`/advisories`, `/advisories/{slug}`), Careers (`/careers`), Contact & Inquiry Desk (`/contact`), and RA 10173 Data Privacy Notice (`/privacy-notice`).
- **Regulatory & privacy constraints:** Licensed and regulated by DOH and accredited by PhilHealth; ARTA Citizen's Charter schedule-of-fees transparency; RA 10173 (Philippine Data Privacy Act of 2012) compliance requiring explicit consent (`privacy_consented`) on inquiries and strict isolation from clinical patient records.
- **Technical constraints:** Self-hosted bundled fonts and assets with no external CDN dependency; preserve print-friendly layout behavior (`.no-print` rules) and PDF export parity.
- **Explicitly undecided:** Whether `/admin` (Filament) is in scope for future UI design work; whether Hiligaynon/Filipino translations will be added alongside English; whether the remaining placeholder vector campus map (`cmc-campus-map.svg`) will be replaced with an updated campus map graphic.

## Brand Commitments

- **Official Institution Name:** Capiz Medical Center.
- **Official Tagline:** *"Pag-atipan nga may gugma"*
- **Classification & Capacity:** Level II Government Hospital · 147 Authorized Beds.
- **Address & Official Contacts:** Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz 5800, Philippines; 24/7 trunklines `(036) 621-0030` and `(036) 621-0823`; general email `info@capizmedicalcenter.gov.ph`; DPO email `dpo@capizmedicalcenter.gov.ph`.
- **Official Seal/Logo, Building Photo & NPC Seal:** Circular Capiz Medical Center seal (`public/images/cmc-logo-official.jpg`), official CMC hospital building photo (`public/images/cmc-building.jpg`), and official NPC DPO/DPS Registered seal (`public/images/npc-seal-official.jpg`) provided by the user.
- **Institutional Vision, Mission & 6 Goals:** Stored in `app/Models/SiteSetting.php` and rendered verbatim on `/about` (and goals on `/`); preserve institutional wording unless explicitly updated.

## Evidence on Hand

- Official Capiz Medical Center logo saved at `public/images/cmc-logo-official.jpg`.
- Official Capiz Medical Center building photo saved at `public/images/cmc-building.jpg`.
- Official National Privacy Commission (NPC) DPO/DPS Registered seal saved at `public/images/npc-seal-official.jpg`.
- Updated Services Offered (24/7 divisions, Physician Residency Training, Out-Patient Department Mon–Fri 8:00 AM–4:00 PM, and Gugma para sa Capiz Dialysis Center Mon–Sat 7:00 AM–5:00 PM) in `database/seeders/ServiceSeeder.php`.
- Institutional defaults, vision, mission, 6 goals, and contact metadata in `app/Models/SiteSetting.php` and `database/seeders/`.
- Existing incumbent SVGs in `public/images/`: `cmc-logo.svg`, `cmc-facade.svg`, `cmc-campus-map.svg`, and `npc-seal.svg`.
- **Verified absences (do not fabricate):** No verified patient testimonials or Hiligaynon/Filipino full-page copy exist in the repository.

## Product Principles

1. **Citizen's Charter Clarity First:** Make hospital rates, service codes, and 24/7 or OPD schedules effortless to search, read, print, and download so citizens do not have to call or line up for basic fee answers.
2. **Institutional Accuracy Builds Public Trust:** Preserve DOH/PhilHealth status, RA 10173 privacy boundaries, official hotlines, and authentic Capiz Medical Center identity without invented claims or placeholder puffery.
3. **Resilient, Print-Ready Delivery:** Keep fonts and assets bundled locally without external CDN reliance, and ensure pricelist views print and export cleanly.
4. **Strict Clinical & Admin Isolation:** Keep public portal inquiries strictly separated from clinical medical records and restrict internal management to authenticated, IP-filtered staff roles.

## Accessibility & Inclusion

- Plain-language RA 10173 Data Privacy Notice (`/privacy-notice`) and explicit inquiry consent check.
- Print-ready Citizen's Charter fee tables and downloadable PDFs for citizens without continuous internet access.
- Specific WCAG conformance target and Hiligaynon/Filipino language support remain open decisions.
