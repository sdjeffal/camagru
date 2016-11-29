<section class="center">
    <h1>Changement de mot de passe</h1>
    <?php
    putFlush('errorAll');
    putFlush('errorModified');
    ?>
    <form method="POST" action="index.php?controller=User&action=changePasswd">
        Nouveau mot de passe: <input name="passwd" type="password" value="<?= $_POST["passwd"]?>"/>
        <?php putFlush('errorPasswd');?>
        <br />
        Retaper ton nouveau mot de passe: <input name="passwdbis" type="password" value="<?= $_POST["passwdbis"]?>"/>
        <?php putFlush('errorPasswdBis');?>
        <br />
        <input type="hidden" name="login" value="<?= $login ?>">
        <input type="submit" name="submit" value="OK"/>
    </form>
</section>