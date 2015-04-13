<?php
foreach($parcours as $value){
    switch ($value['EtatFiche']['etat_id']) {
        case 1:
        echo"
        <div class='bg-info tuilesStatuts'>
            <div class='text-center'>
                <h3>Rédaction</h3>
            </div>
            <div class='tuilesStatutsNom'>
                Créée par <b>".$value['User']['prenom']." ".$value['User']['nom']."</b> le <b>".$this->Time->format($value['Fiche']['created'], '%e-%m-%Y')."</b>

            </div>";
            if(!empty($value['Commentaire'])){

                echo "<div>
                <br/>
                <div class='text-center'>
                    <h4>Commentaires</h4>
                </div>
                ";
                foreach($value['Commentaire'] as $val){
                    
                    echo "<blockquote>
                    <p>".$val['content']."</p>
                    <footer><cite>".$val['User']['prenom']." ".$val['User']['nom']."</cite></footer>
                </blockquote>";
            }

                echo "
            </div>";
        }
        echo "</div>
        ";
        break;

        case 2:
        echo "
        <div class='bg-warning tuilesStatuts'>
            <div class='text-center'>
                <h3>En attente de validation</h3>
            </div>
            <div class='tuilesStatutsNom'>
                Reçue par <b>".$value['User']['prenom']." ".$value['User']['nom']."</b> le <b>".$this->Time->format($value['EtatFiche']['created'], '%e-%m-%Y')."</b>
            </div>";
            if(!empty($value['Commentaire'])){

                echo "<div>
                <br/>
                <div class='text-center'>
                    <h4>Commentaires</h4>
                </div>
                ";
                foreach($value['Commentaire'] as $val){
                    
                    echo "<blockquote>
                    <p>".$val['content']."</p>
                    <footer><cite>".$val['User']['prenom']." ".$val['User']['nom']."</cite></footer>
                </blockquote>";
            }

                echo "
            </div>";
        }
        echo "</div>
        ";
        break;

        case 3:
        echo "
        <div class='bg-success tuilesStatuts'>
            <div class='text-center'>
                <h3>Validée</h3>
            </div>
            <div class='tuilesStatutsNom'>
                Validée par <b>".$value['User']['prenom']." ".$value['User']['nom']."</b> le <b>".$this->Time->format($value['EtatFiche']['modified'], '%e-%m-%Y')."</b>
            </div>";
            if(!empty($value['Commentaire'])){

                echo "<div>
                <br/>
                <div class='text-center'>
                    <h4>Commentaires</h4>
                </div>
                ";
                foreach($value['Commentaire'] as $val){
                    
                    echo "<blockquote>
                    <p>".$val['content']."</p>
                    <footer><cite>".$val['User']['prenom']." ".$val['User']['nom']."</cite></footer>
                </blockquote>";
            }

                echo "
            </div>";
        }
        echo "</div>
        ";
        break;

        case 4:
        echo "
        <div class='bg-danger tuilesStatuts'>
            <div class='text-center'>
                <h3>Refusée</h3>
            </div>
            <div class='tuilesStatutsNom'>
                Refusée par <b>".$value['User']['prenom']." ".$value['User']['nom']."</b> le <b>".$this->Time->format($value['Fiche']['created'], '%e-%m-%Y')."</b>
            </div>";
            if(!empty($value['Commentaire'])){

                echo "<div>
                <br/>
                <div class='text-center'>
                    <h4>Commentaires</h4>
                </div>
                <hr class='hrComms'/>
                ";
                foreach($value['Commentaire'] as $val){
                    
                    echo "<blockquote>
                    <p>".$val['content']."</p>
                    <footer><cite>".$val['User']['prenom']." ".$val['User']['nom']."</cite></footer>
                </blockquote>";
            }
            echo "
        </div>";
    }
    echo "</div>
    ";
    break;
     case 5:
        echo "
        <div class='bg-success tuilesStatuts'>
            <div class='text-center'>
                <h3>Validée et insérée au registre</h3>
            </div>
            <div class='tuilesStatutsNom'>
                Validée par <b>".$value['User']['prenom']." ".$value['User']['nom']."</b> le <b>".$this->Time->format($value['EtatFiche']['modified'], '%e-%m-%Y')."</b>
            </div>";
            if(!empty($value['Commentaire'])){

                echo "<div>
                <br/>
                <div class='text-center'>
                    <h4>Commentaires</h4>
                </div>
                ";
                foreach($value['Commentaire'] as $val){
                    
                    echo "<blockquote>
                    <p>".$val['content']."</p>
                    <footer><cite>".$val['User']['prenom']." ".$val['User']['nom']."</cite></footer>
                </blockquote>";
            }
                echo "
            </div>";
        }
        echo "</div>
        ";
        break;


    default:
    break;
}
}

