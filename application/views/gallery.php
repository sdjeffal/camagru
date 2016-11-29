<section class="center">
    <?php putFlush("error_not_login") ?>
    <?php putFlush("error_empty_comment") ?>
    <?php putFlush("error_empty_gallery") ?>
    <?php putFlush("error_add_comment") ?>
    <?php putFlush("success_add_comment") ?>
    <h1><?= $gallery->getTitle(); ?></h1>
    <img class="img-center" src="<?= BASE.$gallery->getUrl() ?>" alt="<?= $gallery->getTitle() ?>"/>
    <div class="">
      <p><?= $gallery->getUser()->getUsername() ?> <time><?= $gallery->getCreateTime() ?></time></p>
      <?php $n = $gallery->getLike(); ?>
      <?php
        if ($n === 0)
            $message = "personne ne kiffe";
        else if ($n === 1)
            $message = $n." personne kiffe";
        else
            $message = $n." personnes kiffent";
        ?>
      <p><?= $message ?></p>
      <?php if (!$this->isLog() || $this->isLike($gallery->getId()) === false): ?>
          <a class="btn like" href="index.php?controller=index&action=addLike&image_id=<?= $gallery->getId()?>">kiffe</a>
      <?php else: ?>
          <a class="btn dislike" href="index.php?controller=index&action=delLike&image_id=<?= $gallery->getId()?>">ne plus kiffé</a>
      <?php endif ?>
    </div>
    <?php foreach($gallery->getComments() as $key => $comment): ?>
        <div class="comment">
            <p><?= $comment->getUser()->getUsername() ?> a commenté le <time><?= $comment->getCreateTime() ?></time>:</p>
            <blockquote><?= $comment->getMessage();?></blockquote>
        </div>
        <div class="clearfix"></div>
    <?php endforeach; ?>
    <div class="clearfix"></div>
    <form action="index.php?controller=gallery&action=addComment" method="POST">
        <textarea name="message" placeholder="ecris ton comment..."></textarea>
        <input type="hidden" name="gallery_id" value="<?= $gallery->getId()?>">
        <div class="clearfix"></div>
        <input class="btn like" type="submit" value="envoyer">
    </form>
</section>