<?php
echo $this->Html->script('pannel.js');
?>
<div class="well">
    <h1>Créer une fiche</h1>
</div>

<div id="vosInfos">

    <?php
    echo $this->Form->create('Fiche', array('action'=>'add', 'type'=>'file'));

    echo "<fieldset>";
    echo "<legend>Déclarant</legend>";

    echo "<div class='inputsFormLeft75'>";
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Raison Sociale <span class="obligatoire">*</span></span>', 'class'=>'form-control'));

    echo $this->Form->input('nomcpu', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Service</span>', 'class'=>'form-control'));

    echo $this->Form->input('nomcpi', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Adresse <span class="obligatoire">*</span></span>', 'class'=>'form-control', 'type'=>'textarea'));
    echo $this->Form->input('nomcpu', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Adresse éléctronique <span class="obligatoire">*</span></span>', 'class'=>'form-control'));
echo "</div>";
    echo "<div class='inputsFormRight25'>";
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Sigle</span>', 'class'=>'form-control'));
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">N° SIRET <span class="obligatoire">*</span></span>', 'class'=>'form-control'));
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Code APE <span class="obligatoire">*</span></span>', 'class'=>'form-control'));
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Téléphone <span class="obligatoire">*</span></span>', 'class'=>'form-control'));
    echo $this->Form->input('dirserv', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Fax</span>', 'class'=>'form-control'));

    echo "</div>";

    echo "</fieldset>";
    ?>

</div>
<div class="page-header">
    <h2>Déclaration <small>de traitement automatisé</small></h2>
</div>
<div id="outil">
<?php
echo "<fieldset>";
echo "<legend>Informations sur l'outil</legend>";
echo $this->Form->input('datemeo', array('div'=>'input-group input-group-sm inputsFormRight', 'type'=>'text', 'label'=>false, 'before' => '<span class="labelFormulaire">Date de mise en oeuvre</span>', 'id'=>'datepicker', 'class'=>'form-control'));
echo $this->Form->input('nomoutil', array('div'=>'input-group input-group-sm inputsFormLeft', 'label'=>false, 'before' => '<span class="labelFormulaire">Nom de l\'outil <span class="obligatoire">*</span></span>', 'class'=>'form-control', 'required'=>'required'));
echo $this->Form->input('finalite', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Finalité principale</span>', 'class'=>'form-control', 'type'=>'textarea'));

echo "</fieldset>";

echo "<fieldset>";
echo "<legend>Fonctionnalités du traitement</legend>";
echo $this->Form->input('fonctionstraitement', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Décrire les fonctions du traitement</span>', 'class'=>'form-control', 'type'=>'textarea'));
echo '<span class="labelFormulaire">Public concerné par le traitement <span class="obligatoire">*</span></span>';
echo '<div class="inputsFormRight">';
echo $this->Form->input('publiccitoyens', array('type'=>'checkbox', 'label'=>'Citoyens'));
echo $this->Form->input('publicagents', array('type'=>'checkbox', 'label'=>'Agents'));
echo $this->Form->input('publicautres', array('type'=>'checkbox', 'label'=>'Autres'));
echo '</div>';
echo '<div class="inputsFormLeft">';
echo $this->Form->input('publicusagers', array('type'=>'checkbox', 'label'=>'Usagers'));
echo $this->Form->input('publicvisiteurs', array('type'=>'checkbox', 'label'=>'Visiteurs'));
echo $this->Form->input('publiccollegiens', array('type'=>'checkbox', 'label'=>'Collégiens'));
echo '</div>';
echo '<div id="publicAutresPrec">';
echo $this->Form->input('publicautresprec', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisez</span>', 'class'=>'form-control'));
echo '</div>';
echo "</fieldset>";
echo "<fieldset>";
echo "<legend>Informations, droit d'accès et sécurité</legend>";
echo '<span class="labelFormulaire">Mesures prises pour informer les personnes concernées</span>';
echo '<div class="inputsFormRight">';
echo $this->Form->input('informeraffichage', array('type'=>'checkbox', 'label'=>'Affichage dans les locaux recevant la personne'));
echo $this->Form->input('informerinternet', array('type'=>'checkbox', 'label'=>'Mentions sur le site internet'));
echo $this->Form->input('informerintranet', array('type'=>'checkbox', 'label'=>'Mentions sur le site intranet'));
echo '</div>';
echo '<div class="inputsFormLeft">';
echo $this->Form->input('informerquestionnaire', array('type'=>'checkbox', 'label'=>'Mentions légales sur le questionnaire de collecte'));
echo $this->Form->input('informerdocuments', array('type'=>'checkbox', 'label'=>'Documents remis à la personne'));
echo $this->Form->input('informercourrier', array('type'=>'checkbox', 'label'=>'Envoi d\'un courier personnalisé'));
echo $this->Form->input('informerautres', array('type'=>'checkbox', 'label'=>'Autre moyen'));
echo '</div>';
echo '<div id="informerAutresPrec">';
echo $this->Form->input('informerautresprec', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisez</span>', 'class'=>'form-control'));
echo '</div>';

echo '<span class="labelFormulaire">Moyen(s) prévu(s) pour que les personnes exercent leur droit d\'accès</span>';
echo '<div class="inputsFormRight">';
echo $this->Form->input('droitspostal', array('type'=>'checkbox', 'label'=>'Voie postale'));
echo $this->Form->input('droitsinternet', array('type'=>'checkbox', 'label'=>'Accès en ligne prévu sur le site'));
echo "</div>";
echo '<div class="inputsFormLeft">';
echo $this->Form->input('droitssurplace', array('type'=>'checkbox', 'label'=>'Sur place dans le service'));
echo $this->Form->input('droitsemail', array('type'=>'checkbox', 'label'=>'Par courrier éléctronique'));
echo "</div>";
echo '<div id="droitsQui">';
echo $this->Form->input('droitsqui', array('div'=>'input-group input-group-sm inputsFormLeft', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisez auprès de qui</span>', 'class'=>'form-control'));
echo '</div>';
echo '<div id="droitsAdresseMail">';
echo $this->Form->input('droitsadressemail', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisez l\'adresse e-mail</span>', 'class'=>'form-control'));
echo '</div>';
echo '<span class="labelFormulaire">Sécurité du traitement</span>';
echo "<div class='inputsForm'>";
echo $this->Form->input('securiteinterne', array('type'=>'checkbox', 'label'=>'Le traitement est réalisé uniquement sur un réseau interne dédié et les échanges de données sont protégés'));
echo $this->Form->input('securiteacces', array('type'=>'checkbox', 'label'=>'Un contrôle d’accès et d’authentification aux données (mot de passe, certificat, protection des intrusions sur le réseau, carte à puce, signature)'));
echo $this->Form->input('securitephysique', array('type'=>'checkbox', 'label'=>'L\'accès physique au traitement est protégé (local sécurisé, badge, gardien)'));
echo $this->Form->input('securitetransport', array('type'=>'checkbox', 'label'=>'Le canal de transport des données est protégé lors des échanges sur Internet'));
echo $this->Form->input('securiteprec', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisions complémentaires</span>', 'class'=>'form-control'));
echo '</div>';
echo "</fieldset>";
echo "<fieldset>";
echo "<legend>Echanges et transferts de données</legend>";
echo '<span class="labelFormulaire">Le traitement est-il externalisé</span>';
echo '<div class="inputsForm">';
$options = array('O' => 'Oui', 'N' => 'Non');
$attributes = array('legend' => false, 'separator'=> ' | ');
echo $this->Form->radio('externradio', $options, $attributes);
echo '</div>';
echo' <div class="panel panel-default inputsForm" id="donneesExterne">
        <div class="panel-heading">
            <h3 class="panel-title">Données sur l\'organisme externe</h3>
        </div>
        <div class="panel-body">';
echo $this->Form->input('externenom', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Nom de l\'entreprise ou organisation</span>', 'class'=>'form-control'));
echo $this->Form->input('externeadresse', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Adresse</span>', 'class'=>'form-control'));
echo $this->Form->input('externetelephone', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Numéro de téléphone</span>', 'class'=>'form-control'));
echo $this->Form->input('externeemail', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Adresse éléctronique</span>', 'class'=>'form-control'));
echo $this->Form->input('externesiret', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Numéro de SIRET</span>', 'class'=>'form-control'));
echo '</div></div>';

echo '<span class="labelFormulaire">Existe-t-il des interconnexions</span>';
echo '<div class="inputsForm">';
echo $this->Form->input('interconnexionnon', array('type'=>'checkbox', 'label'=>'Non'));
echo $this->Form->input('interconnexionouiinterne', array('type'=>'checkbox', 'label'=>'Oui, avec d\'autres services / traitements du département'));
echo $this->Form->input('interconnexionouiexterne', array('type'=>'checkbox', 'label'=>'Oui, avec des organismes extérieurs'));
echo '<div id="interconnexionPrec">';
echo $this->Form->input('interconnexionprec', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Précisez pour chacun des sous-traitants, les finalités, les modalités d\'échange et si l\'interconnexion est prévue par un texte réglementaire</span>', 'class'=>'form-control', 'type'=>'textarea'));
echo '</div></div>';
echo "</fieldset>";
echo "<fieldset>";
echo "<legend>Données du traitement</legend>";
echo '<span class="labelFormulaire">Pour chaque catégorie de données collectées, préciser le détail, l\'origine, le(s) destinataire(s), la durée légale de conservation</span>';
echo '<div class="inputsForm">';
?>
<table>
    <tr>
        <th class="thcent"><div id="popovera">A</div></th>
        <th class="thcent"><div id="popoverb">B</div></th>
        <th class="thcent"><div id="popoverc">C</div></th>
        <th class="thcent"><div id="popovere">E</div></th>
        <th class="thcent"><div id="popoverh">H</div></th>
        <th class="thcent"><div id="popoveri">I</div></th>
        <th class="thcent"><div id="popoverj">J</div></th>
        <th class="thcent"><div id="popoverk">K</div></th>
        <th class="thcent"><div id="popoverl">L</div></th>
        <th class="thcent"><div id="popoverm">M</div></th>
        <th class="thcent"><div id="popoverp">P</div></th>
    </tr>
    <tr>
        <td class="tdcent"><?php echo $this->Form->input('donneesa', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkA', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesb', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkB', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesc', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkC', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneese', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkE', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesh', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkH', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesi', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkI', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesj', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkJ', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesk', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkK', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesl', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkL', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesm', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkM', 'div'=>false)); ?></td>
        <td class="tdcent"><?php echo $this->Form->input('donneesp', array('type'=>'checkbox', 'label'=>false, 'id'=>'checkP', 'div'=>false)); ?></td>
    </tr>
</table>
<?php

echo '</div>';

?>

<div class="panel panel-default inputsForm donneesCat" id="donneesCatA">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie A</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('adetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('aoriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('aorigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('adestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('adureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('adureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('adureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('adureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatB">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie B</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('bdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('boriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('borigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('bdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('bdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('bdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('bdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('bdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatC">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie C</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('cdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('coriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('corigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('cdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('cdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('cdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('cdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('cdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatE">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie E</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('edetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('eoriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('eorigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('edestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('edureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('edureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('edureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('edureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatH">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie H</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('hdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('horiginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('horigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('hdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('hdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('hdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('hdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('hdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatI">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie I</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('idetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('ioriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('iorigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('idestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('idureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('idureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('idureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('idureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatJ">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie J</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('jdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('joriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('jorigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('jdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('jdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('jdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('jdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('jdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatK">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie K</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('kdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('koriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('korigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('kdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('kdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('kdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('kdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('kdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatL">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie L</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('ldetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('loriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('lorigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('ldestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('ldureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('ldureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('ldureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('ldureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatM">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie M</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('mdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('moriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('morigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('mdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('mdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('mdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('mdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('mdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));

            ?>
        </div>
    </div>
</div>
<div class="panel panel-default inputsForm donneesCat" id="donneesCatP">
    <div class="panel-heading">
        <h3 class="panel-title">Données de catégorie P</h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->Form->input('pdetails', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Détails des données</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Origine</span>';
        echo $this->Form->input('poriginepersonne', array('type'=>'checkbox', 'label'=>'par la personne'));
        echo $this->Form->input('porigineindirecte', array('type'=>'checkbox', 'label'=>'indirecte'));
        echo $this->Form->input('pdestinataires', array('div'=>'input-group inputsForm', 'label'=>false, 'before' => '<span class="labelFormulaire">Destinataires</span>', 'class'=>'form-control', 'type'=>'textarea'));
        echo '<span class="labelFormulaire">Durée de conservation</span>';
        ?>
        <div class="input-group inputsFormRight">
            <span class="input-group-addon">Années</span>
            <?php
            echo $this->Form->input('pdureeannees', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div>
        <div class="input-group inputsFormLeft">
            <span class="input-group-addon">Mois</span>
            <?php
            echo $this->Form->input('pdureemois', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'class'=>'form-control'));
            ?>
        </div><!-- /input-group -->
        <div class="input-group">
            <?php
            echo $this->Form->input('pdureecontractuelle', array('type'=>'checkbox', 'label'=>'Durée relation contractuelle'));
            echo $this->Form->input('pdureeautre', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Autre', 'class'=>'form-control'));
            ?>
        </div>
    </div>
</div>
<?php
echo '<span class="labelFormulaire">Existe-t-il un système d\'archivage, après la durée légale de conservation</span>';
echo '<div class="inputsForm">';
$options = array('O' => 'Oui', 'N' => 'Non');
$attributes = array('legend' => false, 'separator'=> ' | ');
echo $this->Form->radio('archivage', $options, $attributes);
echo '</div>';
echo '<div  id="archivagePrec">';
echo $this->Form->input('archivageprec', array('div'=>'input-group input-group-sm inputsForm', 'label'=>false, 'before' => 'Lequel', 'class'=>'form-control'));
echo '</div>';

?>

<fieldset>
    <legend>Pieces jointes et justificatifs</legend>
    <?php
    echo $this->Form->input('upload', array('type' => 'file', 'multiple'=>'multiple', 'label'=>false));
    echo $this->Form->input('created_user_id', array('type'=>'hidden', 'value'=>$userId));
    echo $this->Form->input('modified_user_id', array('type'=>'hidden', 'value'=>$userId));
    ?>
</fieldset>
<?php
echo $this->Form->submit('Enregistrer', array('class'=>'btn btn-primary pull-right sender'));
echo $this->Form->end();

?>

</div>