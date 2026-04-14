### Hogyan értelmezted a 4. pont szűrés/rendezés követelményt, és miért pont úgy döntöttél
* dátum szerint rendezés jól jöhet ha például az első 20 felriatkozó kiváltságos valamiban
* név szerint ABC sorrendben jobban átlátható
* inaktív feliratkozókat kiszűrni hasznos
* név és email-re keresni a leggyakoribb eset valszeg: mindkettőre ad egyezést, ha közös stringet adunk meg.
* 
### Melyik AI modell-t használta és miért?
* Claude code-ot használtam, egyrészt mert hozzáfér a fájlokhoz, tud módosítani, gyorsítja a fejlesztést. A desktop változatot MCP-vel kellett volna bekötni, plusz lépés és voltak már fennakadások vele. 

### Hol segített az AI, hol kellett korrigálni vagy más modellt bevonni?
* A projekt felépítését én terveztem meg és a vázát kialakítottam, de a docker setupban, a router megírásában pl. 100%-ban használtam a Claude code-ot. Az alkalmazásban a  logikát én adtam utasításban de az elvégzését rá bíztam.
* Kellett korrigálni a filterezésnél, amin énis gondolkoztam még a tervezésnél hogy minden kérésnél API hívás legyen vagy a saját listát szűrjük, az AI API hívást indított volna de korrigáltam.
* Az API doksit átolvastam, de SAPI Robival is beszélgettem hogy 100% legyek a kérésekben.

### Mi az, amit ha újra csinálnád, másképp csinálnál?
 * A 60 percbe nem fért bele a tervezés és megvalósítás, még úgy sem hogy sok kódolást az AI végzett. Ha bele kéne férni, akkor bevonnám jobban a tervezésben, odaadnám az API doksit, elmondanám high levelbe hogy képzelem, bizonyos részeket hogyan oldanék meg és oda adnám egybe, hogy ezzel gyorsítsak. 
 * A promptolást is AI friendlybben csinálnám, angolul mert úgy okosabb. 
### Extra:
 * A listák létrehozási dátuma nem szerepelt az API válaszban. Az appon belül a mezőknél be lehetett pipálni egy első módosítás dátuma mezőt, de azt úgy értelmeztem hogy az a feliratkozókra vonatkozik.
 * A rendezés és szűrésre lehetett volna mindig API kérést indítani mert van rá lehetőség, de akkor más más adat jönne vissza mondjuk több 100 feliratkozónál, ezért ezt el akartma kerülni. 
 * Rate limit elérése (APIv2:
   párhuzamos kérés tiltott): Úgy láttam, hogy a használt régebbi API-ban nincs ilyen limit.
 * A 8.4-es PHP verziót azért választottam, mert modern és aktívan támogatott.


