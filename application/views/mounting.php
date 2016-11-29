
<section class="center">
    <?php putFlush("error_status"); ?>
    <?php putFlush("success_status"); ?>
    <h1>Prends ta photo OKLM !</h1>
    <form name="uploadImg" id="formRadio" action="" method="POST">
        <h3>Choisis ton cadre:</h3>
        <div class="responsive">
          <div class="img">
              <label for="birthday">
                  <input type="radio" name="frame" value="birthday"/>
                  <img src="<?=BASE?>public/frames/birthday.png" alt="birthday" width="300" height="200"/>
              </label>
          </div>
        </div>
        <div class="responsive">
          <div class="img">
            <label for="paque">
                <input type="radio" name="frame" value="paque"/>
                <img src="<?=BASE?>public/frames/paque.png" alt="paque" width="300" height="200"/>
            </label>
          </div>
        </div>
        <div class="responsive">
          <div class="img">
            <label for="noel">
                <input type="radio" name="frame" value="noel"/>
                <img src="<?=BASE?>public/frames/noel.png" alt="noel" width="300" height="200"/>
            </label>
          </div>
        </div>
        <div class="clearfix"></div>
        <h3>Importe ta propre photo:</h3>
        <input type="file" name="imgperso" value="imgperso">
        <button id="startUpload" class="btn like" type="submit" name="submit" value="no">upload</button>
    </form>
    <div class="clearfix"></div>
    <div id="area">
        <video id="video"></video>
        <img id="filter" src="<?=BASE?>public/frames/birthday.png" alt="birthday" width="800px" height="600px"/>
    </div>
    <div class="clearfix"></div>
    <button id="startbutton" name="choiceframe">Choisis ton cadre</button>
    <div class="clearfix"></div>
    <canvas id="canvas"></canvas>
    <img id="photo" src="http://placehold.it/800x600.png&text=Ton montage" alt="photo" width="800px" height="600px">
    <div class="clearfix"></div>
</section>
<aside id="aside">
    <?php foreach ($last as $key => $img): ?>
            <div class="miniature">
                <?= "<a href='index.php?controller=mounting&action=delMounting&id=".$img->getId()."'>&times;</a>";?>
                <?= "<img src='".BASE.$img->getMiniUrl()."' alt='".$img->getTitle."'>" ;?>
            </div>
    <?php endforeach; ?>
</aside>
<script type="text/javascript" src="<?=BASE?>public/js/takephoto.js"></script>