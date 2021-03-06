<?php 
session_start();
// Include php files
include 'includes/config.php';
include 'includes/handle_form.php';

// Define dession order and default session order
$_SESSION['order'] = (isset($_SESSION['order'])) ? $_SESSION['order'] : 'mcId';

// Fetch all items in right order
$query = $pdo->query('SELECT * FROM `items` ORDER BY `items`.`'.$_SESSION['order'].'` ASC');
$items = $query->fetchAll();

// Make a json format
$json_items = json_encode($items);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <title>Gestionnaire d'items Minecraft</title>
        <link rel="icon" type="image/png" href="img/favicon.png" sizes="64x64">
        <link href="https://fonts.googleapis.com/css?family=Quantico:400,400i,700,700i" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.min.css">
    </head>

    <body>

        <?php 

        if(!isset($_SESSION['admin'])) // If it is an admin, don't show the header
        {

        ?>
        <header class="header"> <!-- BASIC HEADER -->
            <div class="container">
                <div class="loginBar text-right">
                    <div id="errorTestLogin"><p><?= array_key_exists('login', $error_login) ? $error_login['login'] : '' ?></p></div>
                    <form action="#" method="post">
                        <input type="hidden" name="type" value="login">
                        <label for="pseudoInput">Administrer la page :</label>
                        <input type="text" name="pseudo" id="pseudoInput" placeholder="Pseudonyme" required>
                        <input type="password" name="pass" placeholder="Mot de passe" required>
                        <input type="submit" value="Valider">
                    </form>
                </div>
            </div>
        </header>
        <div class="toggleButton">
            <span>Administration</span>
            <img src="img/hamburger.svg" alt="menu" width="30" />
        </div>
        <?php

        } // End of the condition

        if(isset($_SESSION['admin'])) // If it is an admin, show the header
        {

        ?>
        <header class="header"> <!-- ADMIN HEADER -->
            <div class="container">
                <div class="loginBar text-right">
                    <form action="#" method="post">
                        <input type="hidden" name="type" value="unlogin">
                        <label>Session : <?= $_SESSION['pseudo'] ?></label>
                        <input type="submit" value="Se déconnecter">
                    </form>
                </div>
            </div>
        </header>
        <div class="toggleButton">
            <span>Administration</span>
            <img src="img/hamburger.svg" alt="menu" width="30" />
        </div>
        <?php

        } // End of the condition

        ?>

       <div class="musicButton">
           <img src="img/record_cat_off.png" alt="music" width="64" />
       </div>

        <div class="container main">

            <section id="titleSearch" class="row"> <!-- TITLE SEARCH -->
                <div class="col-md-12 text-center">
                    <div class="logo">
                        <img src="img/minecraft_logo.png" alt="Logo Minecraft" width="700" />
                        <p class="splash">C'est magique !</p>
                    </div>

                    <h1>Gestionnaire d'items Minecraft</h1>
                    <h2>Cataloguez vos items favoris, et proposez vos propres items à la communauté !</h2>

                    <div class="titleBorder col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1"><div class="titleBox">
                        <div class="row"><input type="search" placeholder="Rechercher un bloc (nom / id / type)" id="search" class=""></div>
                        <form action="#" method="post">
                            <input type="hidden" name="type" value="order">

                            <span class="sortText">Trier par:</span>

                            <!-- Keep the selected order in the session variable -->
                            <select name="order" class="order">
                                <option value="mcId"     <?php if($_SESSION['order'] == 'mcId' )    echo 'selected="selected"' ?>>ID Minecraft</option>
                                <option value="title"    <?php if($_SESSION['order'] == 'title')    echo 'selected="selected"' ?>>Nom d'item</option>
                                <option value="category" <?php if($_SESSION['order'] == 'category') echo 'selected="selected"' ?>>Catégorie</option>
                                <option value="date"     <?php if($_SESSION['order'] == 'date')     echo 'selected="selected"' ?>>Date d'ajout</option>
                            </select>
                            <input type="submit" name="valid" value="Valider" class="valid">
                        </form>
                    </div></div>
                </div>
            </section>

            <section id="blocList" class="row"> <!-- BLOC LIST -->

                <?php foreach($items as $_item): ?> <!-- For each item, display an element -->
                <div class="col-lg-3 col-md-4 col-sm-6 blocCase id_<?=$_item->id ?>">
                    <div class="border ">
                        <div class="itemBox text-center ">
                            <?php

                            if(isset($_SESSION['admin'])) // If it is an admin, show the delete button
                            {

                            ?>
                            <form action="#" method="post">
                                <input type="hidden" name="type" value="delete">
                                <input type="hidden" name="id" value="<?=$_item->id ?>"> <!-- Needed to select the element-->
                                <button class="delete">
                                    <img src="img/delete.svg" onmouseover="this.src='img/delete_hover.svg';" onmouseout="this.src='img/delete.svg';" width="20" alt="Supprimer" />
                                    <span>Supprimer</span>
                                </button>
                            </form>
                            <?php

                            } // End of the condition

                            ?>
                            <div class="titleGroup">
                                <span class="title"><?=$_item->title ?></span>
                                <span class="mcId">#<?=$_item->mcId ?></span>
                            </div>
                            <div><img src="img/uploaded/<?=$_item->textureImg ?>" alt="Item" class="textureImg" width="64" /></div>
                            <p class="category"><?=$_item->category ?></p>
                            <p class="description"><?=$_item->description ?></p>
                            <p class="date">Ajout : <?=Date('G:i\,\ \l\e d/m/Y', strtotime($_item->date))?></p> <!-- Formate date -->
                        </div>
                    </div>
                </div>
                <?php endforeach ?>


            </section>
            <section id="addBlockForm" class="row"> <!-- ITEM FORM -->

                <div class="col-lg-8 col-lg-offset-2 formCase">
                    <div class="border">
                        <div class="itemBox text-center">
                            <h2 class="title">Ajouter un item</h2>
                            <form action="#" method="post" enctype="multipart/form-data" class="addForm"> <!-- Add multipart enctype for images -->

                                <div class="nameItem <?= array_key_exists('title', $error_messages) ? 'error' : '' ?>"> <!-- Add class error -->
                                    <label for="nameItemInput">— Nom de l'item —</label>
                                    <div class="hidden-xs"><p><?= array_key_exists('title', $error_messages) ? $error_messages['title'] : '' ?></p></div> <!-- Display error message -->
                                    <img src="img/error.svg" alt="error" width="20" />
                                    <input type="text" autocomplete="off" name="title" id="nameItemInput" placeholder="Herbe" value="<?= $_POST['title'] ?>" required> <!-- Keep post value in the input -->
                                </div>

                                <div class="numberId <?= array_key_exists('mcId', $error_messages) ? 'error' : '' ?>">
                                    <label for="numberIdInput">— ID —</label>
                                    <div class="hidden-xs"><p><?= array_key_exists('mcId', $error_messages) ? $error_messages['mcId'] : '' ?></p></div>
                                    <img src="img/error.svg" alt="error" width="20" />
                                    <input type="number" autocomplete="off" name="mcId" id="numberIdInput" placeholder="2" value="<?= $_POST['mcId'] ?>" required>
                                </div>

                                <div class="picture <?= array_key_exists('textureImg', $error_messages) ? 'error' : '' ?>">
                                    <label for="uploadPicture">— Aperçu de l'item —</label>
                                    <div class="hidden-xs"><p><?= array_key_exists('textureImg', $error_messages) ? $error_messages['textureImg'] : '' ?></p></div>
                                    <img src="img/error.svg" alt="error" width="20" />
                                    <input type="file" autocomplete="off" name="textureImg" id="uploadPicture" required>
                                    <span>Carré &lt; 15Ko</span>
                                </div>

                                <div class="nameCategory <?= array_key_exists('category', $error_messages) ? 'error' : '' ?>">
                                    <label for="nameCategoryInput">— Catégorie —</label>
                                    <div class="hidden-xs"><p><?= array_key_exists('category', $error_messages) ? $error_messages['category'] : '' ?></p></div>
                                    <img src="img/error.svg" alt="error" width="20" />
                                    <input list="itemList" autocomplete="off" type="text" name="category" id="nameCategoryInput" placeholder="Bloc" value="<?= $_POST['category'] ?>" required>
                                    <datalist id="itemList">
                                        <option value="Bloc"></option>
                                        <option value="Décoratif"></option>
                                        <option value="Redstone"></option>
                                        <option value="Transport"></option>
                                        <option value="Divers"></option>
                                        <option value="Nourriture"></option>
                                        <option value="Outil"></option>
                                        <option value="Combat"></option>
                                        <option value="Potion"></option>
                                        <option value="Matière première"></option>
                                    </datalist>
                                </div>

                                <div class="textDescription <?= array_key_exists('description', $error_messages) ? 'error' : '' ?>">
                                    <label for="textDescriptionInput">— Description —</label>
                                    <div class="hidden-xs"><p><?= array_key_exists('description', $error_messages) ? $error_messages['description'] : '' ?></p></div>
                                    <img src="img/error.svg" alt="error" width="20" />
                                    <input type="text" autocomplete="off" name="description" id="textDescriptionInput" placeholder="Composé de terre et d'herbe" value="<?= $_POST['description'] ?>"></input>
                                </div>

                                <div>
                                    <input type="hidden" name="type" value="add" />
                                    <input type="submit" value="Valider" class="valid">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </section>

            <footer class="text-center"> <!-- FOOTER -->
                <p>Made with <span id="hearth">&hearts;</span> by Simon.L</p>
            </footer>
        </div>

        <audio id="sound" autoplay loop>
            <source src="sounds/cat.mp3" type="audio/mp3" />
        </audio>

        <script>
            var json_items = <?= $json_items; ?>; // Get items table in JS
        </script>
        <script src="js/script.js"></script>
    </body>
</html>