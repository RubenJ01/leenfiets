# Leenfiets

### Mysqli error
Het kan zijn dat je na het runnen van `database_connection.php` deze error krijgt: `Fatal error: Class 'mysqli' not found`.
Dit kan voorkomen als je zelf de interpreter moest instellen voor je editor (bijv in PhPStorm).
Om dit op te lossen moet je naar de folder waarin je PhP hebt geinstalleerd, het is makkelijk deze folder php te noemen.
Voor mij is deze locatie `D:/php`. 

In deze folder zie je onder andere 2 bestanden:  `php.ini-production` en `php.ini-development`.
Hernoem `php.ini-development` naar `php.ini`.

In het bestand `php.ini` kun je met Ctrl + F opzoek naar `;extension:mysqli`.
Verwijder de `;` zodat er komt te staan: `extension=mysqli`

Daarna zoek je nogmaals met Ctrl + F naar: `;extension_dir=` haal hier ook de ; voor weg en vul het path in naar de ext
folder die binnenin je php folder zit, bij mij ziet dat het er zo uit: `extension_dir = "D:/php/ext"`

Sla je wijzigingen op.




