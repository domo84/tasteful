## API

Liten överblick över API:et

Källor:
 - inReach Explorer
 - Manuell GeoJSON (vägrutt, POIs)
 - Blogginlägg

### inReach Explorer

inReach Explorer laddar upp data till DeLorme. Den datan finns tillgänglig på deras webbsida.

```URL: https://explore.delorme.com/feed/Share/MarkusGamenius?d1=2016-10-01```

### Manuell GeoJSON

Redigera och underhålla en GeoJSON-fil med hjälp av geojson.io.

Filen innehåller:
 - Planerad rutt (Line)
 - Planerade stopp (Point(s))
 - Besökta POIs (Point(s))

### Blogginlägg

Hämta blogginlägg från Ghost API. Sök efter `<meta>`-taggen.

```<meta name="geo.position" content="51.08952;-1.216844" />```

## Freshness

Att hämta data från DeLorme och att söka igenom blogginlägg är tidskrävande och kan inte göras per gång. API bör returnera en cachad version. Ett cronjob eller liknande bör periodiskt hämta data från källorna och spara resultatet där API:et når det.
