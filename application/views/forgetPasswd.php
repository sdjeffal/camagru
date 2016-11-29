<section class="center">
    <h1>Mot de passe oublié:</h1>
    <ul class="notification">
        <li>Ton <strong>adresse Email</strong> doit-être <strong>valide</strong> afin de changer de mot de passe.</li>
    </ul>
    <form method="POST" action="index.php?controller=User&action=forgetPasswd">
        <div>
            <input name="mail" type="email" placeholder="email" value="<?= $_POST["mail"]?>"/>
        </div>
        <?php putFlush('errorEmail');?>
        <?php putFlush('errorEmailExists');?>
        <input class="btn like" type="submit" name="submit" value="OK"/>
    </form>
</section>