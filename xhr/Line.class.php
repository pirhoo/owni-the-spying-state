<?php

    // cette classe doit permettre simplement de spécifier le nom des colonnes

    // EXEMPLE :
    // Mon Gdoc contient trois colonnes: id, nom, prénom
    // J'implémente donc une nouvelle classe, hérité de Line

    /*  
    * class MyLine extends Line {
    *     protected  $id;
    *     protected  $nom;
    *     protected  $prenom;
    *  }
    */

    

    abstract class Line {
        
        public function Line($param) {
            
            $i = 0;
            // pour chaque attribut de la class
            // on cherche la valeur qui correspond dans le tableau
            // (à la ième position) passé en paramêtre au constructeur
            foreach($this as $cle => $element) :
                @$this->$cle = $param[$i++];
            endforeach;
        }

        public function json() {

            $json = "{";
            $i = 0;
            foreach($this as $cle => $element) {
                $json .= $i++ > 0 ? "," : "";
                $json .= "'$cle' : '". htmlspecialchars($element, ENT_QUOTES) ."' \n";
            }
            $json .= "}";

            return $json;
        }
    }
?>
