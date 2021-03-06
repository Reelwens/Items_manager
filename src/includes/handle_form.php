<?php

// Set variables
$error_messages = array();
$error_login = array();

// Instructions when post request
if(!empty($_POST)) {

    // If the user use the 'add' post form
    if($_POST['type'] == 'add') {
        // Catch error
        $_POST['pseudo'] = '';

        // Create variables for each post values
        $title = ($_POST['title']);
        $mcId = (int)$_POST['mcId'];
        $textureImg = $_FILES['textureImg']['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];

        // Create variable for a better organisation
        $extension = substr(strrchr($_FILES['textureImg']['name'],'.'),1);



        if(empty($_SESSION['admin']))
            $error_messages['title'] = 'Vous n\'avez pas les droits.';

        // TITLE
        // Value empty
        else {
            if (empty($title))
            $error_messages['title'] = 'Donnée manquante';
            // Text too long
            else if (strlen($title) > 18)
                $error_messages['title'] = '18 lettres max.';
            // Text too short
            else if (strlen($title) < 3)
                $error_messages['title'] = '3 lettres min.';


            // MC ID
            $query = $pdo->query('SELECT * FROM `items` WHERE mcId = '. $mcId);
            // Value empty
            if (empty($mcId))
                $error_messages['mcId'] = 'Donnée manquante';
            // Is not a number
            else if (!is_int($mcId))
                $error_messages['mcId'] = 'Renseignez un nombre';
            // Number too long
            else if (strlen($mcId) > 3)
                $error_messages['mcId'] = '3 chiffres max.';
            // Negative number
            else if ($mcId < 0)
                $error_messages['mcId'] = 'Nombre négatif';
            // The id exist in BDD
            else if($query->rowCount() != 0 ) {
                $error_messages['mcId'] = "Cet ID est déjà enregistré";
            }


            // IMAGE UPLOAD        
            // No image
            if (empty($textureImg))
                $error_messages['textureImg'] = 'Donnée manquante';
            // Size reached
            else if ($_FILES['textureImg']['size'] > 15360)
                $error_messages['textureImg'] = 'Taille max. 15Ko';
            // Incorrect extension
            else if (!in_array($extension,array('png','gif','jpg','jpeg')))
                $error_messages['textureImg'] = 'Extension non reconnue';
            // Not a square picture
            else if(getimagesize($_FILES['textureImg']['tmp_name'])[0] != getimagesize($_FILES['textureImg']['tmp_name'])[1])
                $error_messages['textureImg'] = "L'image n'est pas carrée";


            // CATEGORY
            // Value empty
            if (empty($category))
                $error_messages['category'] = 'Donnée manquante';
            // Text too long
            else if (strlen($category) > 20)
                $error_messages['category'] = '20 lettres max.';
            // Text too short
            else if (strlen($category) < 3)
                $error_messages['category'] = '3 lettres min.';


            // DESCRIPTION
            // Value empty
            if (empty($description))
                $error_messages['description'] = 'Donnée manquante';
            // Text too long
            else if (strlen($description) > 35)
                $error_messages['description'] = '35 lettres max.';
            // Text too short
            else if (strlen($description) < 3)
                $error_messages['description'] = '3 lettres min.';





            // If the form is fill correctly
            if(empty($error_messages)) {
                // Save image in a specific folder
                move_uploaded_file($_FILES['textureImg']['tmp_name'],'img/uploaded/' . $textureImg);

                // Prepare the SQL request
                $prepare = $pdo->prepare('INSERT INTO items (title, mcId, textureImg, category, description) VALUES (:title, :mcId, :textureImg, :category, :description)');

                $prepare->bindValue(':title', $title);
                $prepare->bindValue(':mcId', $mcId);
                $prepare->bindValue(':textureImg', $textureImg);
                $prepare->bindValue(':category', $category);
                $prepare->bindValue(':description', $description);

                // Execute the SQL request
                $prepare->execute();

                // Clean input value
                $_POST['title']       = '';
                $_POST['mcId']        = '';
                $_POST['category']    = '';
                $_POST['description'] = '';
                $_POST['pseudo']      = '';
            }
        }
    }

    // If the user use the 'delete' post form
    else if($_POST['type'] == 'delete') {
        if (isset($_SESSION['admin'])) {
            $exec = $pdo->prepare('DELETE FROM `items` WHERE `items`.`id` = :id');
            
            $exec->bindValue(':id', $_POST['id']);
            $exec->execute();
        }
    }

    else if($_POST['type'] == 'unlogin') {
        $_SESSION = array();
        session_destroy();
    }

    // If the user use the 'login' post form
    else if($_POST['type'] == 'login') {
        
        // Create variables for each post values
        $pseudo = $_POST['pseudo'];
        $pass = sha1($_POST['pass']);

        // Verifiy if user/pass is in BDD
        $query = $pdo->prepare("SELECT * FROM members WHERE pseudo = :pseudo AND pass = :pass");
        
        $query->bindValue(':pseudo', $pseudo);
        $query->bindValue(':pass', $pass);
        $query->execute();
        
        if($query->rowCount() == 0) {
            $error_login['login'] = 'Combinaison incorrecte';
        }
        else {
            $_SESSION['admin'] = 1;
            $_SESSION['pseudo'] = $pseudo;
        }

        if(empty($error_login)) {
        }
    }
    
    // If the user use the 'order' post form
    else if($_POST['type'] == 'order') {
        $_SESSION['order'] = $_POST['order']; // Keep the choice in the session
    }
}

// Define an empty value when she don't exist
$_POST['title']       = (isset($_POST['title']))       ? $_POST['title']       : '';
$_POST['mcId']        = (isset($_POST['mcId']))        ? $_POST['mcId']        : '';
$_POST['category']    = (isset($_POST['category']))    ? $_POST['category']    : '';
$_POST['description'] = (isset($_POST['description'])) ? $_POST['description'] : '';
$_POST['pseudo']      = (isset($_POST['pseudo']))      ? $_POST['pseudo']      : '';