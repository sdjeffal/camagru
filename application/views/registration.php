<section class="center">
    <h1>Inscris toi mec</h1>
    <h3>Pk t'inscrire ?</h3>
    <ul>
        <li>Pour prendre tes propres photos.</li>
        <li>Pour dire haut et fort ce que tu penses des photos des autres en commentant.</li>
        <li>Pour que tout le monde sachent celles que tu kiffes.</li>
    </ul>
    <?php putFlush('errorAll');?>
    <form method="POST" name="registration" action="index.php?controller=User&action=registration">
        <div>
            <input name="login" type="text" placeholder="identifiant" value="<?= $_POST["login"]?>" />
        </div>
        <?php putFlush('errorLogin');?>
        <?php putFlush('errorLoginExists');?>
        <div>
            <input name="mail" type="email" placeholder="email" value="<?= $_POST["mail"]?>" />
        </div>
        <?php putFlush('errorEmail');?>
        <?php putFlush('errorEmailExists');?>
        <div>
            <input name="passwd" type="password" placeholder="mot de passe" />
        </div>
        <?php putFlush('errorPasswd');?>
        <div>
            <input name="passwdbis" type="password" placeholder="Retape ton mot de passe" />
        </div>
        <?php putFlush('errorPasswdBis');?>
        <input class="btn like" type="submit" name="submit" value="OK"/>
    </form>
</section>
<script type="text/javascript" src="<?=BASE?>public/js/validationRegistration.js"></script>