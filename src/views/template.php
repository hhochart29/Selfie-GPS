<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="UTF-8">
        <title>Selfie GPS</title>
        <link rel="icon" type="image/png" href="<?php echo $faviconURL; ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo $styleSheetURL; ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo $styleSheetURL2; ?>">

    </head>
    <body>
        <header><?php echo $header; ?></header>
        <nav id="menu">
            <ul>
                <?php
                    foreach ($menu as $adresse => $texte) {
                    echo '<a href="' . $adresse . '"><li>' . $texte . '</li></a>';}
                ?>
            </ul>
        </nav>
        <main><?php echo $content; ?></main>
        <footer>
            <div id="menufooter">
                <ul>
                    <?php foreach ($menu as $adresse => $texte) {
                        echo '<li><a href="' . $adresse . '">' . $texte . '</a></li>';} ?>
                </ul>
            </div>
        </footer>
    </body>
</html>
