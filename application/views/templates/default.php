<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href= "<?=BASE?>public/css/resetstylesheet.css" media="screen">
        <link rel="stylesheet" href= "<?=BASE?>public/css/style.css" media="screen">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <noscript>
            <div class="alert warning">
                <strong>Attention</strong> javascript est désactivé, tu dois activer javascript pour importer une image ou une photo avec ta webcam.
            </div>
        </noscript>
        <header class="">
            <!--<nav> include in header.php -->
            <?php include "header.php" ;?>
        </header>
            <?php echo $content ;?>
        <footer class=" footer-distributed">
            <?php include "footer.php" ;?>
        </footer>
    </body>
</html>
