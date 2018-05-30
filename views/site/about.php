<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Testinės užduoties aprašymas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-4">
            <h2>Užduotis</h2>
            <p>Kas turėjo būti atlikta</p>
            <hr />
            <ol>
                <li>Įkelti p5d json failą arba įvesti web nuorodą į tą failą</li>
                <li>Įvestą failą išsaugotų į duombazę (sakykime SQLite)</li>
                <li>Visus įkeltus failus išvestų sąraše ir prie kiekvieno failo būtų po mygtuką "Preview"</li>
                <li>Paspaudus "Preview" mygtuką atsidarytu atskiras langas kuriame nupieštu visų p5d faile esančių kambarių sienas ir grindis<br />
                    (2D, grindys gali būti vienspalvės, galima su Canvas)</li>
            </ol>
            <p>Nuorodų į failus galima rasti čia: <a href="https://planner5d.com/gallery/floorplans/" target="_blank">https://planner5d.com/gallery/floorplans/</a></p>
        </div>
        <div class="col-lg-4">
            <h2>Kas padaryta</h2>
            <p>Kaip turi veikti ir kokiu būdu realizuota</p>
            <hr />
            <p>Projektas sukeltas į Github'ą ir dėl patogumo paleistas testiniame serveryje.</p>
            <p>Panaudotas php Yii2 freimworkas, bazinė instaliacija su standartine komplektacija.</p>
            <p>Projekto priklausiniai surenkami su Composer.</p>
            <p>Projektas online paleistas debug režimu.</p>
            <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            <p>Duomenų bazė - MySQL (kad būtų iš karto veikianti versija online ir nekiltų problemų su paleidimu nežinomoje lokalioje aplinkoje).</p>
            <p>Pagal nutylėjimą frontende veikia standartiniai Yii2 pluginai su Bootstrap ir jQuery bibliotekomis.</p>
            <hr />
            <p>Duomenų bazės struktūra failų saugojimui - minimali:</p>
            <pre><code>CREATE TABLE `files` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(45) DEFAULT NULL,
    `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `content` longtext,
    `plan` longtext,
    `description` varchar(512) DEFAULT NULL,
    PRIMARY KEY (`id`)
)</code></pre>
            <p>Būtų racionaliau JSON duomenis saugoti ne longtext, o JSON formatu, bet tam reikia atnaujinti MySQL servisą serveryje.</p>
            <hr />
            <p>Įkėlimo formoje galima pasirinkti 3 įkėlimo būdus:<br />
                Failą iš kompiuterio;
                Failą pasiekiamą per URL'ą;
                Įvedus JSON formato tekstą textarea lauke.</p>
            <p>Papildomi laukai:<br />
                Failo pavadinimas (jeigu tuščias - reikšmė bus išparsinama iš JSON failo turinio);<br />
                Trumpas aprašymas (neprivalomas).
            </p>
            <p>Pridėta Yii2 tipinė CAPTCHA apsauga.</p>
            <hr />
            <p>Formos duomenų validacija realizuota dalinai.</p>
            <p>Įkeliant tikrinama:<br />
             iš PC - failo plėtinys;<br />
            iš nuotolinio failo - nuorodos formatas<br />
                įvedus teksto lauke - vykdomas konvertavimas į JSON objektą.</p>
            <hr />
            <p>Naudojama standartinė Yii2 autorizacija. </p>
            <p>Administratoriaus teisėmis prisijungęs vartotojas gali ištrinti projektus.</p>
            <p><a class="btn btn-default" href="login">Login &raquo;</a></p>
            <hr />
            <p>Canvas dydis yra parenkamas pagal plane atvaizduojamų sienų užimamą plotą, perskaičiuojant sienų koordinates
                ir pridedant atitraukimą pagrindui.</p>
            <p>Plane sienos atvaizduojamos dinamiškai keičiant kiekvieno kambario sienų spalvą.</p>
            <p>Kambario grindų spalva pagal duomenis projekte. Klaidos atveju, jeigu tokis nebūtų - naudojamas paternas su paveiksliuku.</p>
            <p>Planui naudojami du Canvas elementai - vienas kambarių grindims kitas sienoms (ir užrašams) -
                taip paprasčiau susitvarkyti su grindų spalvos "užlipimu" ant sienų.</p>
            <p>Kiekvieno aukšto planas atvaizduojamas skirtinguose tabuose. Optimizuojant apkrovą galima būtų išpiešti
                tik aktyvaus aukšto planą, o kitus aukštus renderinti tik perjungiant kitą tabą.</p>
            <hr />
            <p>JavaScript'as su funkcijomis plano atvaizdavimui turėtų būti atskirame .js faile;
                view'o faile paliktas dėl greitesnio skaitymo.</p>
            <p>Baigus debuginti - reikia įjungti kešavimą. Kokį Yii2 kešo komponentą naudoti, priklausytų nuo serverio
                ir galimų užkešuoti duomenų parametrų bei kiekio.</p>
        </div>
        <div class="col-lg-4">
            <h2>TODO sąrašas</h2>
            <p>Ko trūksta iki pilnos laimės</p>
            <hr />
            <p><b>Failo įkėlimo formoje</b></p>
            <p>Exeption'ai, priimant formos duomenis.</p>
            <p>Būtinų duomenų struktūros patikrinimas (Floor, Room, Plan, Wall objektai)</p>
            <p>Apsauga nuo SQL injection'ų.</p>
            <p>URL'o normalizavimas, įkeliant pagal nuorodą - standartinis nesaugių simbolių enkodinimas neuniversalus.</p>
            <p>Reikėtų iškelti formą į atskirą subview'są, kad būtų aiškesnė kodo stuktūra.</p>
            <hr />
            <p><b>Failų įrašymui</b></p>
            <p>Optimizuojant modelį, į DB reikėtų įrašyti pilną JSON objekto turinį (kaip dabar), plius optimizuotą,
                kuriame būtų saugomi tik tie duomenys, kurie reikalingi renderinimui.</p>
            <hr />
            <p><b>Renderinimas frontende</b></p>
            <p>Ką daryti su 'Ground' objektais, kurie irgi turi 'Wall' items'us? Dabar į plano atvaizdavimą jie netraukiami.</p>
            <p>Renderinant sutvarkyti kambarius su "neuždarytomis" sienomis.</p>
            <p>Pridėti Canvas mastelį, priklausomai nuo naršyklės lango pločio.</p>
            <p>Tobulinant renderinimo eigą, būtų patogiau naudoti pasirašytą JavaScripto klasę.</p>
            <hr />
            <p><b>Modeliai</b></p>
            <p>Iškelti dalį duomenų tvarkymo ir pakeitimo logijos į modelių klases iš kontrolerio.</p>
            <p>Klasių ir laukų aprašymai</p>
            <hr />
            <p>GIT'o repositorijai sutvarkyti README.md aprašymo failą.</p>
        </div>
    </div>
</div>
