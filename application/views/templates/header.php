<nav>
	<ul class="topnav">
		<li><a href="index.php?controller=Index&action=index" class="brand">Camagru</a></li>
		<li><a href="index.php?controller=Index&action=index">galerie</a></li>
		<li><a href="index.php?controller=Mounting&action=index">monter une image</a></li>
		<?php if ($this->islog() !== true){?>
		<li class="right"><a href="index.php?controller=User&action=viewLogin">login</a></li>
		<li class="right"><a href="index.php?controller=User&action=viewRegistration">inscription</a></li>
		<?php }else {?>
		<li class="right"><a href="index.php?controller=User&action=logout">logout</a></li>
		<li class="right"><a href="#"><?= $_SESSION['user']['username']?></a></li>
		<?php } ?>
	</ul>
</nav>