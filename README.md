# Kandidato testinė užduotis

Sveiki! Dėkojame už susidomėjimą. Šios užduoties tikslas – įvertinti jūsų techninius gebėjimus, problemų sprendimo eigą ir bendravimo įgūdžius dirbant su realistišku scenarijumi.

---

## Būtinos programos

Prieš pradedant, įsitikinkite, kad jūsų kompiuteryje yra įdiegta:
- **Docker** ir **Docker Compose**. Instrukcijas, kaip juos įdiegti, rasite [oficialioje Docker svetainėje](https://docs.docker.com/get-docker/).

---

## Projekto paleidimas

1. Padarykite repozitorijos fork pas save
2. Paklonuokite šią repozitoriją į savo kompiuterį.
3. Atsidarykite terminalą (komandinę eilutę) repozitorijos direktorijoje.
4. Paleiskite komandą:
    ```bash
    docker-compose up -d --build
    ```
5. Pirmas paleidimas gali užtrukti kelias minutes, nes bus parsiunčiami ir konfigūruojami Docker atvaizdai.
6. **Automatinis duomenų bazės nustatymas**: Docker paleidimo metu automatiškai:
   - Sukuriama SQLite duomenų bazė
   - Paleidžiamos visos migracijos (sukuriamos lentelės)
   - Bandoma paleisti duomenų užpildymas (seeds) - jei nepavyksta, aplikacija vis tiek paleidžiama
7. Kai procesas bus baigtas, atsidarykite naršyklę ir eikite adresu [http://localhost:8765](http://localhost:8765). Turėtumėte pamatyti CakePHP pasisveikinimo puslapį.

### Duomenų bazės valdymas

Duomenų bazė dabar konfigūruojama automatiškai! Jums nebereikia rankiniu būdu paleisti migracijų ar duomenų užpildymo komandų.

**Jei reikia rankiniu būdu paleisti duomenų užpildymą:**
```bash
docker compose exec app bash -c "bin/cake migrations migrate && bin/cake migrations seed"
```

**Jei reikia peržiūrėti Docker konteinerio žurnalus:**
```bash
docker-compose logs app
```

Projektas veikia! Visi kodo failai yra `boilerplate` direktorijoje. Galite juos redaguoti savo mėgstama IDE, o pakeitimai iškart atsispindės naršyklėje.

---

## Užduotys

Jūsų laukia trys pagrindinės užduotys. Kiekvienai užduočiai, kuriai reikia kodo pakeitimų, sukurkite atskirą *Pull Request* (PR).

### 1. Kodo dokumentavimas

**Tikslas:** Parodyti, kad gebate greitai perprasti esamą kodą ir jį suprantamai aprašyti.

**Ką daryti?**
- Išanalizuokite esamo kodo bazę (`boilerplate` direktorijoje).
- Dokumentuokite pagrindinę projekto logiką, struktūrą ir duomenų srautus.
- Dokumentaciją galite rašyti `.md` formato failuose repozitorijoje arba naudodami GitHub Wiki funkciją.

### 2. Problemos sprendimas (Bug Fix)

**Tikslas:** Įvertinti jūsų gebėjimą analizuoti problemas, rasti jų priežastis ir pasiūlyti kokybišką sprendimą.

**Ką daryti?**
- Repozitorijoje rasite sukurtą *issue*, kuris imituoja kliento pranešimą apie klaidą.
- Yra trys klaidos - išanalizuokite problemas ir ištaisykite bent vieną ar visas tris klaidas.
- Sukurkite *Pull Request* su pataisymu savo repozitorijos fork'e.
- PR aprašyme **būtinai** nurodykite:
    - Kas tiksliai buvo problema?
    - Kaip ir kodėl ją išsprendėte (kokį sprendimo būdą pasirinkote ir kodėl)?
    - Kaip galima rankiniu būdu patikrinti (testuoti), kad problema išspręsta?

### 3. Naujos funkcijos kūrimas (Feature Request)

**Tikslas:** Įvertinti jūsų gebėjimą kurti naują funkcionalumą pagal pateiktus reikalavimus.

**Ką daryti?**
- Sukurkite paskolų valdymo sistemą: egzistuoja admin valdymo skyde paskolų sukurimas ir forma, kur naudotojai gali prašyti paskolos. Integruokite metodiką paskolos gavėjo tinkamumo patikrinimui. Patvirtinti paskolų gavėjai atsiranda sąraše su nurodytomis procentinėmis palūkanomis, kurios gaunamos nuo investuotos sumos ir pervedamos į investuotojo piniginės adresą. Investuoti pinigiai pervedami į paskolos gavėjo/projekto piniginės adresą.
- Sukurkite atskirą *Pull Request* šiai funkcijai savo repozitorijos fork'e.
- PR aprašyme **būtinai** nurodykite:
    - Ką sukūrėte ir kaip veikia nauja funkcija?
    - Kodėl pasirinkote būtent tokius techninius sprendimus?
    - Kaip galima rankiniu būdu patikrinti (testuoti) naują funkcionalumą?

### 4. Bonusinė užduotis (Investment Criteria)

**Tikslas:** Papildoma užduotis investavimo kriterijų funkcionalumui.

**Ką daryti?**
- Sukurkite galimybę investuotojui nurodyti kriterijus pagal kuriuos galėtų investuoti. 
- Siūlomi kriterijai: kredito įvertinimas ir nemokumo tikimybė, paskolos suma ir terminas, palūkanų normos lūkesčiai, rizikos tolerancijos lygis, skolininko pajamų patvirtinimas, paskolos paskirtis ir užstatas, geografinės preferencijos, diversifikacija tarp kelių paskolų. 
- Pagalvokite ir pasiūlykite papildomus kriterijus.

### Kai bus visos užduotys padarytos

- Nusiųskite laišką HR, kad baigėte su nuoroda į savo repozitorija

---

### Vertinimo kriterijai:
- Techninis užduočių išpildymas ir kodo kokybė.
- Idėjos ir sprendimų kūrybiškumas.
- Įgyvendinimo principai ir architektūros sprendimai.
- Kodo kokybės ir struktūros įgyvendinimo principai.
- Gebėjimas sekti karkaso (framework) konvencijas ir siūlymus.
- Sprendimų gyvendinimo atlikimo metodikos.
- Gebėjimas aiškiai ir struktūrizuotai komunikuoti (per dokumentaciją ir PR aprašymus).
- Git naudojimo praktikos (aiškūs *commit* pranešimai, tvarkingi PR).
- Problemos analizės ir sprendimo logika.

Sėkmės!
