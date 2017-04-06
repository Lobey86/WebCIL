<br/>
<h3>
    <?php echo ("Information propre au paramétrage de l'organisation"); ?>
</h3>
<hr/>
<?php
if (!empty($organisations)) {
    ?>
    <!-- Tableau de l'entité -->
    <table class="table">
        <h4><?php echo __d('modele', 'modele.sousTitreEntité'); ?></h4>
        <thead>
        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
        </th>
        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
        </th>

        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauValeur'); ?>
        </th>
    </thead>
    <tbody>
            <?php
            foreach ($valeurOrganisations as $key => $organisation) {
                foreach ($organisation as $orgKey => $val) {
                    ?>
        <tr>
            <td class="tdleft">
                            <?php echo $orgKey; ?>
            </td>

            <td class="tdleft">
                            <?php echo __d('modele','modele.textOrganisation') . $orgKey; ?>
            </td>

            <td class="tdleft">
                            <?php echo $val; ?>
            </td>
        </tr>
                    <?php
                }
            }
            ?>
    </tbody>
    </table>

    <br>
    <?php
} else {
    echo __d('modele', 'modele.textAucuneEntite');
}

if (!empty($responsableOrganisations)) {
    ?>
    <!-- Tableau du responsable l'organisation -->
    <table class="table">
        <h4>
            <?php echo __d('modele', 'modele.sousTitreResponsableEntité'); ?>
        </h4>
        
        <thead>
            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
            </th>
            
            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
            </th>

            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauValeur'); ?>
            </th>
        </thead>
        
        <tbody>
            <?php
            foreach ($responsableOrganisations as $key => $organisation) {
                foreach ($organisation as $orgKey => $val) {
                    ?>
                    <tr>
                        <td class="tdleft">
                           <?php echo $orgKey; ?>
                        </td>

                        <td class="tdleft">
                            <?php echo __d('modele','modele.textOrganisation') . $orgKey; ?>
                        </td>

                        <td class="tdleft">
                            <?php echo $val; ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>

    <br>
    <?php
} else {
    echo __d('modele', 'modele.textAucuneEntite');
}

//Logo CIL
if (file_exists(IMAGES . DS . 'logos' . DS . 'logo_cil.jpg')) {
    echo $this->Html->image('logos' . DS . 'logo_cil.jpg', [
        'class' => 'logo-well',
    ]);
} else {
   ?>
    <h4>
        <?php echo __d('modele', 'modele.sousTitreCIL'); ?>
    </h4>
    <?php
}

foreach ($userCIL as $cil){
?>

    <!-- Tableau du CIL -->
    <table class="table">
        <thead>
            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
            </th>

            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
            </th>

            <th class="thleft col-md-5">
                    <?php echo __d('modele', 'modele.titreTableauValeur'); ?>
            </th>
        </thead>

        <tbody>
            <tr>
                <td class="tdleft">
                    <?php echo __d('modele', 'modele.textTableauNomPrenomUserTraitement'); ?>
                </td>

                <td class="tdleft">
                    <?php echo __d('modele','modele.textOrganisation') . 'cil'; ?>
                </td>

                <td class="tdleft">
                    <?php echo $cil['civilite'] . $cil['prenom'] . ' ' . $cil['nom']?>
                </td>
            </tr>

            <tr>
                <td class="tdleft">
                    <?php echo __d('modele', 'modele.textTableauEmailUserTraitement'); ?>
                </td>

                <td class="tdleft">
                     <?php echo __d('modele','modele.textOrganisation') . 'emailcil'; ?>
                </td>

                <td class="tdleft">
                     <?php echo $cil['email']?>
                </td>
            </tr>
        </tbody>
    </table>
<?php
}
?>
    
<hr/>

<br/>
<h3>
    <?php echo ("Information propre au traitement"); ?>
</h3>
<hr/>
<!-- Texte section -->
<div class="alert alert-warning" role="alert">
    <?php echo __d('modele', 'modele.textInformationSectionTraitement'); ?>
</div> 
<hr/>

<!-- Tableau de personne a l'origine de l'utilisation du traitement -->
<table class="table">
    <h4><?php echo __d('modele', 'modele.sousTitreCreateurTraitement'); ?></h4>
    <thead>
    <th class="thleft col-md-10">
        <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
    </th>

    <th class="thleft col-md-10">
        <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
    </th>
</thead>
<tbody>
    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textTableauNomPrenomUserTraitement'); ?>
        </td>

        <td class="tdleft">
            valeur_declarantpersonnenom
        </td>
    </tr>

    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textTableauEmailUserTraitement'); ?>
        </td>

        <td class="tdleft">
            valeur_declarantpersonneemail
        </td>
    </tr>
</tbody>
</table>

<br>

<!-- Tableau pour le nom et le description du traitement -->
<table class="table">
    <h4><?php echo __d('modele', 'modele.sousTitreNomDescriptionTraitement'); ?></h4>
    <thead>
    <th class="thleft col-md-10">
        <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
    </th>

    <th class="thleft col-md-10">
        <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
    </th>
</thead>
<tbody>
    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textTableauNomTraitement'); ?>
        </td>

        <td class="tdleft">
            valeur_outilnom
        </td>
    </tr>

    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textTableauFinaliteTraitement'); ?>
        </td>

        <td class="tdleft">
            valeur_finaliteprincipale
        </td>
    </tr>

    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textNumeroEnregistrementRegistre'); ?>
        </td>

        <td class="tdleft">
            valeur_numenregistrement
        </td>
    </tr>
    
    <tr>
        <td class="tdleft">
            <?php echo __d('modele', 'modele.textTypeDeclaration'); ?>
        </td>

        <td class="tdleft">
            valeur_typedeclaration
        </td>
    </tr>
</tbody>
</table>

<br>

<?php
if (!empty($variables)) {
    ?>
<!-- Tableau des variables du formulaire -->
<table class="table">
    <h4><?php echo __d('modele', 'modele.sousTitreVariableFormulaire'); ?></h4>
    <thead>
        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
        </th>
        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
        </th>
        <th class="thleft col-md-5">
                <?php echo __d('modele', 'modele.titreTableauType'); ?>
        </th>
    </thead>
    <tbody>
        <?php
        foreach ($variables as $key => $variable) {
            $details = json_decode($variable['details'], true);

            if (!empty($details['name'])) {
            ?>
                <tr>
                    <td class="tdleft">
                                    <?php echo $details['label']; ?>
                    </td>

                    <td class="tdleft">
                                    <?php echo "valeur_" . $details['name']; ?>
                    </td>

                    <td class="tdleft">
                        <?php
                        switch ($variable['type']) {
                            case 'input':
                                echo __d('modele', 'modele.textPetitChamp');
                                break;

                            case 'textarea':
                                echo __d('modele', 'modele.textGrandChamp');
                                break;

                            case 'date':
                                echo __d('modele', 'modele.textDateChamp');
                                break;

                            case 'checkboxes':
                                echo __d('modele', 'modele.textCheckboxChamp');
                                break;

                            case 'radios':
                                echo __d('modele', 'modele.textRadioChamp');
                                break;

                            case 'deroulant':
                                echo __d('modele', 'modele.textDeroulantChamp');
                                break;

                            default:
                                break;
                        }
                        ?>    
                    </td>
                </tr>
            <?php
            }
        }
        ?>
    </tbody>
</table>

<br

<!-- Tableau annexe -->
<table class="table">
    <h4>
        <?php echo __d('modele', 'modele.sousTitreAnnexeTraitement'); ?>
    </h4>
    <!-- Texte section -->
    <div class="alert alert-warning" role="alert">
        <?php echo __d('modele', 'modele.textInformationSectionFichiers'); ?>
    </div> 
    <thead>
        <th class="thleft col-md-10">
            <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
        </th>

        <th class="thleft col-md-10">
            <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
        </th>
    </thead>

    <tbody>
        <tr>
            <td class="tdleft">
                <?php echo __d('modele', 'modele.textTableauAnnexe'); ?>
            </td>

            <td class="tdleft">
                <?php echo __d('modele','modele.textValeur') . 'annexe'; ?>
            </td>
        </tr>
    </tbody>
</table>

<br>

<hr/>

<!-- Tableau Historique -->
<table class="table">
    <h4>
        <?php echo __d('modele', 'modele.sousTitreHistoriqueTraitement'); ?>
    </h4>
    <!-- Texte section -->
    <div class="alert alert-warning" role="alert">
        <?php echo __d('modele', 'modele.textInformationSectionHistoriques'); ?>
    </div> 
    <thead>
        <th class="thleft col-md-10">
            <?php echo __d('modele', 'modele.titreTableauNomChamp'); ?>
        </th>

        <th class="thleft col-md-10">
            <?php echo __d('modele', 'modele.titreTableauNomVariable'); ?>
        </th>
    </thead>

    <tbody>
        <tr>
            <td class="tdleft">
                <?php echo __d('modele', 'modele.textTableauHistoriqueCommentaire'); ?>
            </td>

            <td class="tdleft">
                <?php echo ("content"); ?>
            </td>
        </tr>
        
        <tr>
            <td class="tdleft">
                <?php echo __d('modele', 'modele.textTableauHistoriqueDate'); ?>
            </td>

            <td class="tdleft">
                <?php echo ("created"); ?>
            </td>
        </tr>
    </tbody>
</table>

<br></br>

<br></br>

<?php
}
?>

<!-- Bouton revenir -->    
<div class="row">
    <div class="col-md-12 top17 text-center">
        <div class="btn-group">
            <?php
            echo $this->Html->link('<i class="fa fa-fw fa-arrow-left"></i>' . __d('fiche', 'fiche.btnRevenir'), array(
                'controller' => 'Modeles',
                'action' => 'index'
                    ), array(
                'class' => 'btn btn-default-default',
                'escape' => false
            ));
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>