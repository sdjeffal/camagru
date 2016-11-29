<section class="center">
    <?php putFlush("error_not_login") ?>
    <?php putFlush("error_empty_gallery") ?>
    <h1>Home</h1>
    <?php if (empty($galleries)): ?>
        <article>
            <p class="alert info">Il n'y a pas d'image pour le moment</p>
        </article>
    <?php endif; ?>
    <?php foreach ($galleries as $key => $gallery): ?>
    <article>
        <div class="responsive">
            <div class="img">
                <a href="index.php?controller=gallery&action=viewGallery&image_id=<?= $gallery->getId() ?>">
                    <img src="<?= BASE.$gallery->getUrl() ?>" alt="<?= $gallery->getTitle() ?>" />
                </a>
            </div>
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
    </article>
    <?php endforeach; ?>
    <div class="clearfix"></div>
    <?php if ($pagination->getSets() > 1) :?>
        <div class="pagination">
        <?php for ($i=1; $i <= $pagination->getSets() ; $i++) :?>
            <?php if ($i === $pagination->getSetNumber()) :?>
            <a class="btn btn-inv" href=""><?= $i ?></a>
            <?php else: ?>
            <a class="btn" href="index.php?controller=index&action=index&page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor ?>
        </div>
    <?php endif ?>
</section>