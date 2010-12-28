<?php
        // permet d'afficher les messages d'erreur
        if(isset($_GET['debug'])) {
            ini_set('display_errors', 1);
            ini_set('log_errors', 1);
            error_reporting(E_ALL);
        }
        
        // ce script retourne un objet JSON
        @header('Content-Type: text/html; charset=UTF-8');
        require_once("./Line.class.php");

        // ---------------------------------------------------------------------
        // ICI COMMENCE LA CONFIGURATION DU GDOC
        // ---------------------------------------------------------------------
        
        // On défini la gueule de notre doc
        // en énumérant les attributs
        // qui ordonnés ainsi
        // corespondent aux colonnes de notre doc
         class MyLine extends Line{
            protected $id;
            protected $category;
            protected $titre;
            protected $description;
        }
        
        define("USER_MAIL", "pierre.romera@gmail.com");
        define("USER_PWD",  "1968revolution");
        define("DOC_NAME",  "table");
        // facultatif mais conseillée, la clé de l'article (pour l'atteindre)
         // si null, on utilise le doc name pour retrouver la clef
        define("DOC_KEY",   "thrwaG-fvzsCxN_a0fMSKMw");

        // nombre d'heure avant de re-généré le doc
        // passez sur 0 pour désactiver le cache
        define("UP_AFTER", 0.5); // ici, le doc json est généré toutes les 1/2 heure

        // ---------------------------------------------------------------------
        // À PARTIR D'ICI IL N'Y A PLUS RIEN À CONFIGURER (déjà ? Oui, je sais)
        // ---------------------------------------------------------------------
        
        // dossier où est stocké le doc généré au format JSON
        define("DIR_CACHE", "cache/");
        
        // le doc date de moins d'une heure
        $diff = time() - getlastmod(DIR_CACHE.DOC_NAME.".json");
        if(file_exists(DIR_CACHE.DOC_NAME.".json") && $diff <= (60*60)*UP_AFTER):
            // utilise le fichier mis en cache
            echo file_get_contents(DIR_CACHE.DOC_NAME.".json");
        else:
            
            chdir("../");
            require_once './Zend/Loader.php';

            /* Load the Zend Gdata classes. */
            Zend_Loader::loadClass('Zend_Gdata_AuthSub');
            Zend_Loader::loadClass('Zend_Gdata_Gbase');
            Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
            Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

            // on se connecte (authentification) au service google doc
            $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
            $client = Zend_Gdata_ClientLogin::getHttpClient(USER_MAIL, USER_PWD, $service);

            // on récupère la liste de mes documents
            $spreadsheetService = new Zend_Gdata_Spreadsheets($client);

            // on a la clef du document
            if(DOC_KEY != null) {

                $spreadsheetsKey = DOC_KEY;
                 
            } else { // on a pas la clef du document

                // se plug au flux qui contient la liste des doc disponibles sur ce compte
                $feed = $spreadsheetService->getSpreadsheetFeed();
                
                // récupère la clef du document dans la liste de mes documents
                foreach($feed->entries as $entry)
                    if ($entry->title->text == DOC_NAME)
                        $spreadsheetsKey = basename($entry->id);
            }
            
            // Création du document à partir de cette clef
            // on reçoit toute les cellules du tableau dans un flux
            $query = new Zend_Gdata_Spreadsheets_CellQuery();
            $query->setSpreadsheetKey($spreadsheetsKey);
            $cellFeed = $spreadsheetService->getCellFeed($query);

            // on interprête ce flux pour le classer dans un Array d'objets Scandale
            $table = Array();            
            foreach($cellFeed as $cellEntery){

                // à chaque fois qu'on arrive à la 1ere Colonne, on a tous les attributs, on créait le scandale
                // on saute la première ligne
                if($cellEntery->cell->getColumn() == 1 && $cellEntery->cell->getRow() > 2) {
                        $table[] = new MyLine( $line_buffer );
                        $line_buffer = Array(); // vide le buffer
                        
                } elseif ($cellEntery->cell->getColumn() == 1) {
                        $line_buffer = Array(); // vide le buffer                    
                }

                // stocke le contenu de la cellule dans le buffer
                $line_buffer[] = $cellEntery->cell->getText();
                 
            }

            // stocke quelques infos sur le doc
            $file  = '{"generated" : '.time(). ', '."\n";
            $file .= '"doc_name" : "'.DOC_NAME. '", '."\n";
            $file .= '"doc_key" : "'.DOC_KEY. '", '."\n";
            $file .= '"data" : [';
            // parcours du tableau contenant tous les scandales
            /* @var $line_buffer MyLine */
            $i = 0; foreach($table as $line_buffer) {

                if($i++>0)
                    $file .= ','."\n";
                // encode chaque Scandale au format json
                $file .= $line_buffer->json();
            }
            // affiche le fichier 
            echo $file .= ']}';
            // enregistre le fichier
            file_put_contents("xhr/".DIR_CACHE.DOC_NAME.".json", $file);
        endif;

?>