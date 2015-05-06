<?php
echo $this->Html->script('formulaire.js');
?>
<div class="well">
    <?php
    if ( file_exists(IMAGES . DS . 'logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo')) ) {
        echo $this->Html->image('logos/' . $this->Session->read('Organisation.id') . '.' . $this->Session->read('Organisation.logo'), array('class' => 'pull-right logo-well'));
    }
    ?>
    <h1>Créer une fiche</h1>
</div>

<div id="vosInfos">

    <?php
    echo $this->Form->create('Fiche', array(
        'action' => 'add',
        'type' => 'file'
    ));
    echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">';
    echo "<fieldset>";
    echo "<legend class='panel-title'><a data-toggle='collapse' data-parent='#accordion' href='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>Déclarant</a></legend>";
    echo "</div>";
    echo '<div class="panel-body">';
    echo "<div class='inputsFormLeft75'>";
    echo $this->Form->input('declarantraisonsociale', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Raison Sociale <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'raisonsociale' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declarantservice', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Service</span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('declarantadresse', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'type' => 'textarea',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'adresse' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declarantemail', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'email' ],
        'readonly' => 'readonly'
    ));
    echo "</div>";
    echo "<div class='inputsFormRight25'>";
    echo $this->Form->input('declarantsigle', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Sigle</span>',
        'class' => 'form-control',
        'value' => $organisation[ 'Organisation' ][ 'sigle' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declarantsiret', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">N° SIRET <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'siret' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declarantape', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Code APE <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'ape' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declaranttelephone', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Téléphone <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required',
        'value' => $organisation[ 'Organisation' ][ 'telephone' ],
        'readonly' => 'readonly'
    ));
    echo $this->Form->input('declarantfax', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Fax</span>',
        'class' => 'form-control',
        'value' => $organisation[ 'Organisation' ][ 'fax' ],
        'readonly' => 'readonly'
    ));
    echo "</div>";

    echo "<div class='precision'>";
    echo "<span class='labelFormulaire'>Personne à contacter au sein de l'organisme déclarant si un complément doit être demandé et destinataire du récipissé:</span>";
    echo $this->Form->input('declarantpersonnenom', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Nom et prénom <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required'
    ));
    echo $this->Form->input('declarantpersonneemail', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required'
    ));
    echo "</div>";
    echo "</fieldset>";
    ?>
</div>
<div id="outil">
    <?php
    echo "<fieldset>";
    echo "<legend>Outil</legend>";
    echo $this->Form->input('outilnom', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Nom de l\'outil <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'required' => 'required'
    ));

    echo "<fieldset>";
    echo "<legend>Service chargé de la mise en oeuvre du traitement</legend>";
    echo "<div class='precision'>";
    echo "<span class='labelFormulaire'>Veuillez préciser quel est le service ou l'organisme qui effectue, en pratique, le traitement:</span>";
    echo $this->Form->input('miseenoeuvreinterne', array(
        'type' => 'checkbox',
        'label' => 'Il s\'agit du déclarant lui-même',
        'class' => 'check'
    ));
    echo $this->Form->input('miseenoeuvreexterne', array(
        'type' => 'checkbox',
        'label' => 'Le traitement est assuré par un tiers <span class="small">(prestataires, sous traitants)</span> ou un service différent du déclarant.',
        'class' => 'check'
    ));
    echo "</div>";
    echo "<div id='miseenoeuvreexternecoordonneesdiv'>";
    echo "<div class='inputsFormLeft75'>";
    echo $this->Form->input('miseenoeuvreexterneraisonsociale', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Nom et prénom ou raison sociale <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('miseenoeuvreexterneservice', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Service</span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('miseenoeuvreexterneadresse', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'type' => 'textarea'
    ));
    echo $this->Form->input('miseenoeuvreexterneemail', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo "</div>";
    echo "<div class='inputsFormRight25'>";
    echo $this->Form->input('miseenoeuvreexternesigle', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Sigle</span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('miseenoeuvreexternesiret', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">N° SIRET <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('miseenoeuvreexterneape', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Code APE <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('miseenoeuvreexternetelephone', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Téléphone <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('miseenoeuvreexternefax', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Fax</span>',
        'class' => 'form-control'
    ));
    echo "</div>";
    echo "</div>";

    echo "</fieldset>";

    echo "<fieldset>";
    echo "<legend>Finalité du traitement</legend>";
    echo $this->Form->input('finalitedescriptif', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Quelle est la finalité ou l\'objectif de votre traitement <span class="small">(ex: gestion du recrutement)</span> ?</span>',
        'class' => 'form-control',
        'type' => 'textarea'
    ));
    echo '<span class="labelFormulaire">Quelles sont les personnes concernées par le traitement? <span class="obligatoire">*</span></span>';
    echo '<div class="inputsFormRight">';
    echo $this->Form->input('finaliteciblesalaries', array(
        'type' => 'checkbox',
        'label' => 'Salariés'
    ));
    echo $this->Form->input('finalitecibleusagers', array(
        'type' => 'checkbox',
        'label' => 'Usagers'
    ));
    echo $this->Form->input('finalitecibleautres', array(
        'type' => 'checkbox',
        'label' => 'Autres'
    ));
    echo '</div>';

    echo '<div class="inputsFormLeft">';
    echo $this->Form->input('finalitecibleclients', array(
        'type' => 'checkbox',
        'label' => 'Clients <span class="small">(actuels ou potentiels)</span>'
    ));
    echo $this->Form->input('finaliteciblevisiteurs', array(
        'type' => 'checkbox',
        'label' => 'Visiteurs'
    ));
    echo $this->Form->input('finalitecibleadherents', array(
        'type' => 'checkbox',
        'label' => 'Adhérents'
    ));
    echo '</div>';
    echo '<div id="finalitecibleautresdiv" class="divs-autres">';
    echo $this->Form->input('finalitecibleautresdetails', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Précisez</span>',
        'class' => 'form-control'
    ));
    echo '</div>';

    echo '<span class="labelFormulaire">Si vous utilisez une technologie particulière, merci de préciser laquelle</span>';
    echo '<div class="inputsFormRight">';
    echo $this->Form->input('technologiesparticulieressanscontact', array(
        'type' => 'checkbox',
        'label' => 'Dispositif sans contact <span class="small">(ex: RFID, NFC)</span>'
    ));
    echo $this->Form->input('technologiesparticulierescartepuce', array(
        'type' => 'checkbox',
        'label' => 'Carte à puce'
    ));
    echo $this->Form->input('technologiesparticulieresvideoprotection', array(
        'type' => 'checkbox',
        'label' => 'Vidéoprotection'
    ));

    echo '</div>';
    echo '<div class="inputsFormLeft">';
    echo $this->Form->input('technologiesparticulieresanonymisation', array(
        'type' => 'checkbox',
        'label' => 'Mécanisme d\'anonymisation'
    ));
    echo $this->Form->input('technologiesparticulieresgeolocalisation', array(
        'type' => 'checkbox',
        'label' => 'Géolocalisation'
    ));
    echo $this->Form->input('technologiesparticulieresnanotechnologies', array(
        'type' => 'checkbox',
        'label' => 'Nanotechnologie'
    ));
    echo $this->Form->input('technologiesparticulieresautres', array(
        'type' => 'checkbox',
        'label' => 'Autres'
    ));
    echo '</div>';
    echo '<div id="technologiesparticulieresautresdiv" class="divs-autres">';
    echo $this->Form->input('TechnologiesParticulieresAutresdetails', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Précisez</span>',
        'class' => 'form-control'
    ));
    echo '</div>';
    echo "</fieldset>";
    echo "<fieldset>";
    echo "<legend>Données traitées</legend>";
    echo '
    <table class="table table-hover table-bordered tabledonnees">
    <thead>
    <tr>
    <th class="thcent">Catégorie de données</th>
    <th class="thcent">Origine</th>
    <th class="thcent">Durée de conservation</th>
    <th class="thcent">Destinataires</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie1general', array(
            'type' => 'checkbox',
            'label' => 'Etat civil, identité, données d\'identification'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categorie1originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie1origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie1originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie1duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie1duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie1duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie1dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie1dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie1dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie1destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie2general', array(
            'type' => 'checkbox',
            'label' => 'Vie personnelle <br/><span class="small">(habitudes de vie, situation familiale, etc.)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categorie2originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie2origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie2originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie2duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie2duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie2duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie2dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie2dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie2dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie2destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie3general', array(
            'type' => 'checkbox',
            'label' => 'Vie professionnelle <br/><span class="small">(CV, scolarité, formation professionnelle, distinctions ...)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categorie3originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie3origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie3originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie3duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie3duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie3duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie3dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie3dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie3dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie3destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie4general', array(
            'type' => 'checkbox',
            'label' => 'Informations d\'ordre économique et financier <br/> <span class="small">(revenus, situation financière, situation fiscale, etc.)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categorie4originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie4origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie4originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie4duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie4duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie4duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie4dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie4dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie4dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie4destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie5general', array(
            'type' => 'checkbox',
            'label' => 'Données de connexion <br/> <span class="small">(Adresse IP, logs, etc.)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categorie5originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie5origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie5originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie5duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie5duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie5duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie5dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie5dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie5dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie5destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categorie6general', array(
            'type' => 'checkbox',
            'label' => 'Données de localisation <br/><span class="small">(Déplaceents, données GPS, GSM, etc.)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->form->input('categorie6originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categorie6origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categorie6originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categorie6duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categorie6duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categorie6duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categorie6dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categorie6dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categorie6dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categorie6destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    </tbody>
    </table>';
    echo "</fieldset>";
    echo "<fieldset>";
    echo "<legend>Données sensibles</legend>";
    echo '
    <div class="precision"> Le traitement des données sensibles est particulièrement encadré par la loi: ces données ne peuvent être enregistrées dans un traitement que si elles sont absolument nécéssaires à sa réalisation.</div>
    <table class="table table-hover table-bordered tabledonnees">
    <thead>
    <tr>
    <th class="thcent">Catégorie de données</th>
    <th class="thcent">Origine</th>
    <th class="thcent">Durée de conservation</th>
    <th class="thcent">Destinataires</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td class="tdleft">' . $this->Form->input('categoriesensible1general', array(
            'type' => 'checkbox',
            'label' => 'N° de sécurité sociale'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categoriesensible1originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categoriesensible1origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categoriesensible1originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categoriesensible1duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categoriesensible1duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categoriesensible1duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categoriesensible1dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categoriesensible1dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categoriesensible1dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categoriesensible1destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categoriesensible2general', array(
            'type' => 'checkbox',
            'label' => 'Infractions, condamnations, mesures de sûreté <br/><span class="small">(réservé aux auxiliaires de justice)</span>'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categoriesensible2originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categoriesensible2origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categoriesensible2originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categoriesensible2duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categoriesensible2duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categoriesensible2duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categoriesensible2dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categoriesensible2dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categoriesensible2dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categoriesensible2destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    <tr>
    <td class="tdleft">' . $this->Form->input('categoriesensible3general', array(
            'type' => 'checkbox',
            'label' => 'Opinions philosophiques, politiques, religieuses, syndicales, vie sexuelle, données de santé, origine raciale ou ethnique'
        )) . '</td>
    <td class="tdleft">' . $this->Form->input('categoriesensible3originedirecte', array(
            'type' => 'checkbox',
            'label' => 'Directement auprés de la personne concernée'
        )) . '
    ' . $this->Form->input('categoriesensible3origineindirecte', array(
            'type' => 'checkbox',
            'label' => 'De manière indirecte'
        )) . $this->Form->input('categoriesensible3originedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">
    ' . $this->Form->input('categoriesensible3duree1mois', array(
            'type' => 'checkbox',
            'label' => '1 mois'
        )) . $this->Form->input('categoriesensible3duree3mois', array(
            'type' => 'checkbox',
            'label' => '3 mois'
        )) . $this->Form->input('categoriesensible3duree1an', array(
            'type' => 'checkbox',
            'label' => '1 an'
        )) . $this->Form->input('categoriesensible3dureecontractuelle', array(
            'type' => 'checkbox',
            'label' => 'Pendant la durée de la relation contractuelle'
        )) . $this->Form->input('categoriesensible3dureeautre', array(
            'type' => 'checkbox',
            'label' => 'Autre'
        )) . $this->Form->input('categoriesensible3dureedetails', array(
            'label' => false,
            'placeholder' => 'Précisez',
            'class' => 'inputsForm'
        )) . '
    </td>
    <td class="tdleft">Destinataires:
    ' . $this->Form->input('categoriesensible3destinataires', array(
            'div' => 'input-group inputsForm',
            'label' => false,
            'class' => 'form-control',
            'type' => 'textarea'
        )) . '
    </td>
    </tr>
    </tbody>
    </table>';
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<legend>Echanges de données / interconnexions <span class='obligatoire'>*</span></legend>";

    echo '<span class="labelFormulaire">Procédez vous à des échanges de données?</span>';
    echo $this->Form->input('echangenon', array(
        'type' => 'checkbox',
        'label' => 'Non'
    ));
    echo $this->Form->input('echangeinterne', array(
        'type' => 'checkbox',
        'label' => 'Oui, avec d\'autres services au sein de l\'organisme déclarant'
    ));
    echo $this->Form->input('echangeexterne', array(
        'type' => 'checkbox',
        'label' => 'Oui, avec des organismes extérieurs au déclarant'
    ));
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<legend>Sécurité / Confidentialité <span class='obligatoire'>*</span></legend>";

    echo '<span class="labelFormulaire">Veuillez cocher les cases correspondant aux mesures de sécurité que vous prenez:</span>';
    echo $this->Form->input('securitephysique', array(
        'type' => 'checkbox',
        'label' => 'L\'accès physique au traitement est protégé <span class="small">(bâtiment ou local sécurisé)</span>'
    ));
    echo $this->Form->input('securiteauthentification', array(
        'type' => 'checkbox',
        'label' => 'Un procédé d\'authentification des utilisateurs est mis en oeuvre <span class="small">(mot de passe individuel, puce, certificat, signature ...)</span>'
    ));
    echo $this->Form->input('securitejournalisation', array(
        'type' => 'checkbox',
        'label' => 'Une journalisation des connexions est effectuée'
    ));
    echo $this->Form->input('securiteinterne', array(
        'type' => 'checkbox',
        'label' => 'Le traitement est réalisé sur un réseau interne dédié <span class="small">(non relié à internet)</span>'
    ));
    echo $this->Form->input('securitecrypte', array(
        'type' => 'checkbox',
        'label' => 'Si les données sont échangées en réseau, le canal de transport ou les données sont chiffrés'
    ));
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<legend>Transferts de données hors de l'Union européenne <span class='obligatoire'>*</span></legend>";
    echo $this->Form->input('transferthorsuenon', array(
        'type' => 'checkbox',
        'label' => 'Vous ne transmettez pas les données vers un pays situé hors de l\'Union européenne'
    ));
    echo $this->Form->input('transferthorsuesuffisant', array(
        'type' => 'checkbox',
        'label' => 'Vous transmettez tout ou partie des données traitées vers un pays assurant un niveau de protection suffisant <span class="small">(' . $this->Html->link('cf. liste à jour des pays sur le site de la CNIL', 'http://www.cnil.fr/linstitution/international/les-autorites-de-controle-dans-le-monde/', array('target' => '_blank')) . ')</span>, ou vers une société américaine adhérant au safe harbor.'
    ));
    echo $this->Form->input('transferthorsueinsuffisant', array(
        'type' => 'checkbox',
        'label' => 'Vous transmettez tout ou partie des données traitées vers un pays n\'assurant pas un niveau de protection suffisant'
    ));

    echo '<div id="transfertdiv">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><h3 class="panel-title">Organisme destinataire des données transférées</h3></div>';
    echo '<div class="panel-body">';

    echo $this->Form->input('transfertciblepays', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Pays <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));

    echo "<div class='inputsFormLeft75'>";
    echo $this->Form->input('transfertcibleraisonsociale', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Raison Sociale <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('transfertcibleservice', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Service</span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('transfertcibleadresse', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'type' => 'textarea'
    ));
    echo "</div>";
    echo "<div class='inputsFormRight25'>";
    echo $this->Form->input('transfertcibletelephone', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Téléphone <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('transfertciblefax', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Fax</span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('transfertcibleemail', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo '<span class="labelFormulaire">Quelle est la finalité du transfert <span class="small">(centrale d\'appel, assistance clientèle, saisie des données ...)</span>? <span class="obligatoire">*</span></span>';
    echo $this->Form->input('transfertfinalite', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'class' => 'form-control',
        'type' => 'textarea'
    ));
    echo '<span class="labelFormulaire">Quelles sont les catégories des personnes concernées par le transfert? <span class="obligatoire">*</span></span>';
    echo '<div class="inputsFormRight">';
    echo $this->Form->input('transfertconcernesalarie', array(
        'type' => 'checkbox',
        'label' => 'Salariés'
    ));
    echo $this->Form->input('transfertconcerneusagers', array(
        'type' => 'checkbox',
        'label' => 'Usagers'
    ));
    echo $this->Form->input('transfertconcernepatients', array(
        'type' => 'checkbox',
        'label' => 'Patients'
    ));
    echo $this->Form->input('transfertconcerneautres', array(
        'type' => 'checkbox',
        'label' => 'Autres'
    ));
    echo '</div>';
    echo '<div class="inputsFormLeft">';
    echo $this->Form->input('transfertconcerneclients', array(
        'type' => 'checkbox',
        'label' => 'Clients (actuels ou potentiels)'
    ));
    echo $this->Form->input('transfertconcernevisiteurs', array(
        'type' => 'checkbox',
        'label' => 'Visiteurs'
    ));
    echo $this->Form->input('transfertconcerneadherents', array(
        'type' => 'checkbox',
        'label' => 'Adhérents'
    ));
    echo $this->Form->input('transfertconcerneetudiants', array(
        'type' => 'checkbox',
        'label' => 'Etudiants / Elèves'
    ));
    echo '</div>';
    echo '<div id="transfertconcerneautresdiv" class="divs-autres">';
    echo $this->Form->input('transfertconcerneautresdetails', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Précisez</span>',
        'class' => 'form-control'
    ));
    echo '</div>';

    echo '<span class="labelFormulaire">Quelle est la nature des traitements opérés par les destinataires des données <span class="small">(lecture seule, saisie, ...)</span>? <span class="obligatoire">*</span></span>';
    echo $this->Form->input('transfertnaturetraitement', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'class' => 'form-control',
        'type' => 'textarea'
    ));

    echo '<span class="labelFormulaire">Quelles sont les catégories des données transférées? <span class="obligatoire">*</span></span>';
    echo '<div class="inputsFormRight">';
    echo $this->Form->input('transfertcategoriesensible1', array(
        'type' => 'checkbox',
        'label' => 'N° de sécurité sociale'
    ));
    echo $this->Form->input('transfertcategoriesensible2', array(
        'type' => 'checkbox',
        'label' => 'Infractions, condamnations, mesures de sûreté'
    ));
    echo $this->Form->input('transfertcategoriesensible3', array(
        'type' => 'checkbox',
        'label' => 'Origines raciales ou ethniques, opinions politiques, philosophiques, religieuses, appartenance syndicale, vie sexuelle'
    ));
    echo '</div>';
    echo '<div class="inputsFormLeft">';
    echo $this->Form->input('transfertcategorie1', array(
        'type' => 'checkbox',
        'label' => 'Etat civil / identité / données d\'idenfication'
    ));
    echo $this->Form->input('transfertcategorie2', array(
        'type' => 'checkbox',
        'label' => 'Vie personelle'
    ));
    echo $this->Form->input('transfertcategorie3', array(
        'type' => 'checkbox',
        'label' => 'Vie professionnelle'
    ));
    echo $this->Form->input('transfertcategorie4', array(
        'type' => 'checkbox',
        'label' => 'Information d\'ordre économique et financier'
    ));
    echo $this->Form->input('transfertcategorie5', array(
        'type' => 'checkbox',
        'label' => 'Données de connexion'
    ));
    echo $this->Form->input('transfertcategorie6', array(
        'type' => 'checkbox',
        'label' => 'Données de localisation'
    ));
    echo '</div>';
    echo '</div>';
    echo '<div id="transfert6div">';
    echo '<span class="labelFormulaire">Si le transfert s\'effectue vers un pays n\'assurant pas un niveau de protection suffisant, sélectionnez les garanties mises en oeuvre pour permettre le transfert  <span class="small">(' . $this->Html->link('cf. liste à jour des pays sur le site de la CNIL', 'http://www.cnil.fr/linstitution/international/les-autorites-de-controle-dans-le-monde/', array('target' => '_blank')) . ')</span></span>';

    echo $this->Form->input('transfertgarantiesresponsabletraitement', array(
        'type' => 'checkbox',
        'label' => 'Contrat de responsable de traitement à responsable de traitement <span class="small">(clauses contractuelles types de la commission européenne)</span>'
    ));
    echo $this->Form->input('transfertgarantiessoustraitant', array(
        'type' => 'checkbox',
        'label' => 'Contrat de responsable de traitement à sous-traitant <span class="small">(clauses contractuelles types de la commission européenne)</span>'
    ));
    echo $this->Form->input('transfertgarantiessafeharbor', array(
        'type' => 'checkbox',
        'label' => 'Certification "safe harbor" (concerne uniquement les Etats-Unis)'
    ));
    echo $this->Form->input('transfertgarantiesbcr', array(
        'type' => 'checkbox',
        'label' => 'Règles internes <span class="small">(ou "BCR - Binding Corporate Rules")</span>'
    ));
    echo $this->Form->input('transfertgarantiesliste', array(
        'type' => 'checkbox',
        'label' => 'Un des cas suivants, prévus par l\'article 69 de la loi du 6 janvier 1978 modifiée:'
    ));
    echo $this->Form->input('transfertgarantiesviepersonne', array(
        'type' => 'checkbox',
        'label' => 'La sauvegarde de la vie de la personne',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiesinteretpublic', array(
        'type' => 'checkbox',
        'label' => 'La sauvegarde de l\'intérêt public',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiesrespectobligation', array(
        'type' => 'checkbox',
        'label' => 'Le respect d\'obligations permettant d\assurer la constatation, l\'exercice ou la défense d\un droit en justice',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiesregistrepublic', array(
        'type' => 'checkbox',
        'label' => 'La consultation d\'un registre public',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiescontratinteresse', array(
        'type' => 'checkbox',
        'label' => 'L\'exécution d\'un contrat entre le responsable du traitement et l\'intérressé',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiescontrattiers', array(
        'type' => 'checkbox',
        'label' => 'La conclusion ou l\'exécution d\'un contrat conclu dans l\'intérêt de la personne concernée entre le responsable du traitment et un tiers',
        'div' => 'input checkbox listCheckBox'
    ));
    echo $this->Form->input('transfertgarantiesconsentement', array(
        'type' => 'checkbox',
        'label' => 'Le consentement de la personne',
        'div' => 'input checkbox listCheckBox'
    ));
    echo '</div>';
    echo "</fieldset>";
    echo "<fieldset>";
    echo "<legend>Le droit d'accès des personnes fichées <span class='obligatoire'>*</span></legend>";
    echo '<div class="precision">Le droit d\'accès est le droit reconnu à toute personne d\'interroger le responsable d\'un traitement pour savoir s\'il détient des informations sur elle, et le cas échéant d\'en obtenir communication. (Cf. article 32 de la loi + modûles de mentions dans la notice)</div>';
    echo '<span class="labelFormulaire">Comment informez-vous les personnes concernées par votre traitement de leur droit d\'accès? <span class="obligatoire">*</span></span>';
    echo $this->Form->input('accesinformationformulaire', array(
        'type' => 'checkbox',
        'label' => 'Mentions légales sur formulaire'
    ));
    echo $this->Form->input('accesinformationcourier', array(
        'type' => 'checkbox',
        'label' => 'Envoi d\'un courrier personnalisé'
    ));
    echo $this->Form->input('accesinformationaffichage', array(
        'type' => 'checkbox',
        'label' => 'Affichage'
    ));
    echo $this->Form->input('accesinformationsite', array(
        'type' => 'checkbox',
        'label' => 'Mentions sur site internet'
    ));
    echo $this->Form->input('accesinformationautres', array(
        'type' => 'checkbox',
        'label' => 'Autres mesures'
    ));
    echo '<div id="accesinformationautresdiv"  class="divs-autres">';
    echo $this->Form->input('accesinformationdetails', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Précisez</span>',
        'class' => 'form-control'
    ));
    echo '</div>';
    echo '<span class="labelFormulaire">Veuillez indiquer les coordonnées du service chargé de répondre aux demandes de droits d\'accès: <span class="obligatoire">*</span></span>';
    echo $this->Form->input('accesreponseinterne', array(
        'type' => 'checkbox',
        'label' => 'Il s\'agit du déclarant lui-même'
    ));
    echo $this->Form->input('accesreponseexterne', array(
        'type' => 'checkbox',
        'label' => 'Le traitement est assuré par un tiers <span class="small">(prestataire, sous-traitant)</span> ou un service différent du déclarant'
    ));
    echo '<div id="coordoneesreponse">';
    echo "<div class='inputsFormLeft75'>";
    echo $this->Form->input('accesreponseraisonsociale', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Raison Sociale <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('accesreponseservice', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Service</span>',
        'class' => 'form-control'
    ));

    echo $this->Form->input('accesreponseadresse', array(
        'div' => 'input-group inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse <span class="obligatoire">*</span></span>',
        'class' => 'form-control',
        'type' => 'textarea'
    ));
    echo $this->Form->input('accesreponseemail', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo "</div>";
    echo "<div class='inputsFormRight25'>";
    echo $this->Form->input('accesreponsesigle', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Sigle</span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('accesreponsesiret', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">N° SIRET <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('accesreponseape', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Code APE <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('accesreponsetelephone', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Téléphone <span class="obligatoire">*</span></span>',
        'class' => 'form-control'
    ));
    echo $this->Form->input('accesreponsefax', array(
        'div' => 'input-group input-group-sm inputsForm',
        'label' => false,
        'before' => '<span class="labelFormulaire">Fax</span>',
        'class' => 'form-control'
    ));
    echo "</div>";
    echo "</div>";
    echo "</fieldset>";

    echo "<fieldset><legend>Joindre des fichiers</legend>";
    echo "<span class='btn btn-default btn-file'>";

    echo $this->Form->input('fichiers.', array(
        'label' => false,
        'type' => 'file',
        'multiple',
        'class' => 'filestyle',
        'data-buttonText' => 'Parcourir',
        'data-buttonName' => "btn-primary",
        'data-buttonBefore' => "true"
    ));
    echo "</span>";
    echo "</fieldset>";

    echo $this->Form->input('user_id', array(
        'type' => 'hidden',
        'value' => $userId
    ));
    echo $this->Form->input('organisation_id', array(
        'type' => 'hidden',
        'value' => $this->Session->read('Organisation.id')
    ));
    echo $this->Form->submit('Enregistrer', array('class' => 'btn btn-primary pull-right sender'));
    echo $this->Form->end();

    ?>

</div>