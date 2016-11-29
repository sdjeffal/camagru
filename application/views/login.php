<section class="center">
    <h1>login</h1>
    <?php putFlush('errorAccessForbidden');?>
    <?php putFlush('errorAll');?>
    <?php putFlush('errorNotFound');?>
    <form method="POST" action="index.php?controller=user&action=login">
    <div>
    <?php if (isset($_POST["login"]) && !empty($_POST["login"])) : ?>
        <input name="login" type="text" value="<?= $_POST['login'] ?>" placeholder="identifiant"/>
    <?php else : ?>
        <input name="login" type="text" value="" placeholder="identifiant"/>
    <?php endif; ?>
    </div>
    <div>
        <input name="passwd" type="password" placeholder="mot de passe" value=""/>
    </div>
    <div class="">
        <input class="btn like" type="submit" name="submit" value="OK"/>
    </div>
    </form>
    <p><a class="btn dislike" href="index.php?controller=User&action=viewForgetPasswd">Mot de passe oublié ?</a></p>
</section>
