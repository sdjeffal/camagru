<section class="center">
    <h1>Mot de passe oublié:</h1>
    <form method="POST" action="index.php?controller=User&action=forgetPasswd">
        <div>
            <input name="mail" type="email" placeholder="email" value="<?= $_POST["mail"]?>"/>
        </div>
        <?php putFlush('errorEmail');?>
        <?php putFlush('errorEmailExists');?>
        <input class="btn like" type="submit" name="submit" value="OK"/>
    </form>
</section>