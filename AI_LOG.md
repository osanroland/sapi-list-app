# AI_LOG.md – Fejlesztési napló

Nyers napló: mit terveztem meg én, hogyan utasítottam az AI-t, hol kellett visszakorrigálni, hogyan jutottunk el a végső megoldásokhoz.



## 1. PHP 8.4 Docker alapstruktúra

**Session:** `4067c8d1` → `b7cc6b4e`

### Mit kértem plan mode-ban

> "I need a new application which runs in docker and the app can be set up with docker compose up. I will use composer, so the docker compose up should run composer install as well."

Majd:
> "I forgot to mention, that the project stack is php 8.4"

Az AI visszakérdezett (AskUserQuestion), én válaszoltam:
- "What type of PHP application?" → **"Plain PHP"**
- "Do you need a database?" → **No**

**Az AI első terve elutasítva** (silent reject) – nem adtam magyarázatot.

Majd miután én magam kialakítottam a projekt struktúrát:

> "Kialakítottam a controllert, a domain objecteket, service-t és kliens szerkezetét."

Ezzel jeleztem hogy az én munkám, az AI-nak csak a bekötést kellett megcsinálnia.

---

## 2. Bootstrap + Router

**Session:** `b7cc6b4e` (terv, elutasítva) → `97e7f6dd` (megvalósítás)

### Mit kértem plan mode-ban

> "Ki alakítottam a projekt szerkezetét, és szeretném hogy az index php-ban bootrsrapeld nekem, készíts egy router osztályt ami alapból a controller index metódusát hívja, és a /list/{id}/subsribers route pedig a subscribers metódust."

**Az AI terve elutasítva** (silent reject).

---

## 3. EmailList domain object + Service

**Session:** `ca86ed91`

### Mit kértem (terv már megvolt, csak végrehajtás)

Az AI terve:
- `EmailList` kap konstruktort és gettereket
- `EmailListService::getLists()` `EmailList[]`-et adjon vissza
- `subscribersCount` a `getListCount()` hívással kerül be

Ezt elfogadtam, végrehajtás volt.

---

## 4. Controller + Twig template

**Session:** `34b38e18`

### Mit kértem

`ListController::index()` implementálása, Twig bebekötése, `list.html.twig` megírása.

Elfogadtam, végrehajtás.

---

## 5. Bootstrap UI – base template + lista oldal

**Session:** `e5e12c4a`

### Mit kértem

Bootstrap CDN bekötése base layout template-be, a `list.html.twig` refaktorálása erre, "Kiválaszt" gomb minden lista mellé.

Elfogadtam, végrehajtás.

---

## 6. Subscribers oldal

**Session:** `4e007e3b`

### Mit kértem

`subscribers()` controller metódus, `getSubscribers()` service, `Subscriber` getterek, `subscribers.html.twig` – mind egyszerre összedrótozva.

Elfogadtam, végrehajtás.

---

## 7. Subscriber domain object – valós API mezők

**Session:** `f3b31c5d`

### Mit kértem

Az előző lépésben a `Subscriber` osztály placeholder mezőkkel készült. Én adtam meg a valós API struktúrát:
- `id`, `email`, `subdate`, `active` ("1"/"0"), `mssys_firstname`, `mssys_lastname`
- `STATUS_ACTIVE` / `STATUS_INACTIVE` konstansok

Elfogadtam, végrehajtás.

---

## 8. Rendezés – `?sort=`

**Session:** `b3c2d6eb`

### Mit kértem

Rendezés dropdown: név A→Z, Z→A, dátum régi→új, új→régi. Ugyanazon a route-on `?sort=` paraméterrel.

Elfogadtam, végrehajtás.

---

## 9. Szűrés – `?isActive=`, `?search=`

**Session:** `1c63f064`

### Mit kértem

Két szűrő a rendezés mellé:
1. `isActive` checkbox – csak aktív feliratkozók
2. `search` szövegmező – névre és e-mailre szűr, case-insensitive

Mindkettő egy `<form method="get">` alatt él.

Elfogadtam, végrehajtás.

---

## 10. HTTP hibakezelés

**Session:** `a90e2762`

### Mit kértem (terv már megvolt)

- 401 → `UnauthorizedException` → "Érvénytelen / hibás API kulcs."
- Timeout → `TimeoutException` → "Nagy terheltség, próbálja később."
- Közös `error.html.twig` template

Elfogadtam, végrehajtás.

---

## 11. Üres lista jelzés

**Session:** `a90e2762` (terv, 2x elutasítva) → `3f2e4304` (megvalósítás)

### Mit kértem

> "Amikor a subscribereket rendereljük és a tömb üres, akkor a template-ben jelezzük hogy Nincs megjelenítendő elem."

**Az AI terve kétszer is elutasítva** (silent reject) – nem adtam magyarázatot.

A következő sessionben elfogadtam ugyanazt a tervet, végrehajtás egy Edit hívással.

---

## 12. Feliratkozók limitálása 20-ra

**Session:** `3f2e4304` (3x elutasított terv) → `9628102f` (végrehajtás)

Ez volt a leghosszabb, legtöbb oda-visszával járó feladat.

### Mit kértem

> "Azt kell még megoldani hogy csak az első 20 feliratkozót jelenítsük meg. Az API doksit itt van hozzá: [teljes API dokumentáció beillesztve]
> Tehát a subscribers lekérésnél postot kell használni, hogy limitet is meg tudjunk adni. Ha szükséged van még infóra, csak mondd."

Amit én adtam meg:
- a probléma pontos leírása
- a teljes SalesAutopilot API dokumentáció
- konkrét technikai megkötés (POST kell a limithez)

### 1. terv – PHP `array_slice` → elutasítva

Az AI az összes adatot lekérte volna, majd `array_slice($subscribers, 0, 20)` PHP-ban.

**Én:**
> "Szerintem ne kérjük le feleslegesen az összeset ha csak 20-ra van szükség. Nem baj, ha a szűrés után kevesebb marad. Tervezd újra az API kérést hogy tudjunk limitet megadni."

### 2. terv – Sort-alapú API hívások → elutasítva

Az AI minden `sort` értékhez más-más API endpointot hívott volna.

**Én:**
> "Nem kell minden sortnál api hívás, mert akkor különböző adataink lesznek, nem egyértelmű. Az első subscriber hívásnál adjunk meg limitet utána a saját tömbünket sortoljuk és filterezzük."

### 3. terv – Fix endpoint → elutasítva (silent)

Az AI `/order/subdate/desc/20` fix endpointot javasolt. Elutasítottam magyarázat nélkül.

### Végrehajtás a következő sessionben

A 3. tervvel megegyező tervet elfogadtam és végrehajtottam:

```php
return $this->request('POST', '/list/' . $listId . '/order/subdate/desc/20');
```

---

## 13. `sortAndFilter()` kiemelése

**Session:** `9628102f` (jelenlegi)

### Mit kértem

> "A controller subscribe metódusába a sortandfilter legyen már külön metódus, hogy szépen látszódjon mi történik."

Tisztán olvashatóságot kértem, nem funkcionalitást. Egymenetes végrehajtás.

---

## 14. Ez a fájl

**Session:** `9628102f`

### Mit kértem

> "Készíts a project rootba egy AI_LOG.md fájlt amibe összeszeded cenzúrázás nélkül hogy miket terveztem magamtól, miket kértem. Nem kell minden prompt, de a főbb döntési pontok legyenek benne: hogyan kérdeztem, hogyan javíttattam hibát, hogyan javítottalak ki, hogyan jutottál el egy-egy megoldásig."

Majd:
> "Akár egy linket is belerakhatsz a teljes beszélgetésről, de derüljön ki hogy mi az amit én terveztem meg, és hogyan utasítottalak mit csinálj. Minden kérésnél emeld ki hogyan határoztam meg a feladatot, milyen konkrét dolgokat kértem."

Majd:
> "A plan módban kért dolgokat is írd bele."

---

## Összefoglaló

| Döntés | Ki hozta |
|---|---|
| Projekt stack (PHP 8.4, plain PHP, no DB) | **Én** |
| `public/` helye (`src/` alá kerül) | **Én** |
| Projekt struktúra (controller, service, client, domain) | **Én** – az AI előtt kialakítottam |
| Valós API mezőnevek (`mssys_firstname` stb.) | **Én** |
| Limit helye: API-n, nem PHP-ban | **Én** |
| Limit értéke: fix 20, nem dinamikus | **Én** |
| Rendezés PHP-ban marad, nem kerül API-ra | **Én** |
| `sortAndFilter` kiemelése olvashatóság miatt | **Én** |
| Konkrét implementáció | AI |
| 3 rossz limitterv egymás után | AI |
