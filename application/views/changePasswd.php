<section class="center">
    <h1>Changement de mot de passe</h1>
    <ul class="notification">
        <li>Ton <strong>mot de passe</strong> doit contenir entre <strong>6 et 24 caractères alphanumériques avec au moins une lettre minuscule, une majuscule et un chiffre</strong>.</li>
    </ul>
    <?php
    putFlush('errorAll');
    putFlush('errorModified');
    ?>
    <form method="POST" action="index.php?controller=User&action=changePasswd">
        <div>
            <input name="passwd" type="password" placeholder="nouveau mot de passe" value="<?= $_POST["passwd"]?>"/>
        </div>
        <?php putFlush('errorPasswd');?>
        <br />
        <div>
            <input name="passwdbis" type="password" placeholder="Retaper ton nouveau mot de passe" value="<?= $_POST["passwdbis"]?>"/>
        </div>
        <?php putFlush('errorPasswdBis');?>
        <br />
        <input type="hidden" name="login" value="<?= $login ?>">
        <input type="submit" name="submit" value="OK"/>
    </form>
</section>