<div class="notrender-content flex items-center justify-center">
	<div class="flex items-center">
		<h1><?= $title ?></h1>
		<h2><?= $msg1 ?></h2>
		<h2><?= $msg2 ?></h2>
		<?php if($platform == 'browser'): ?>
		<ul class="list-inline flex justify-between">
			<li class="list-inline-item">
				<img class="browser-img" src="<?= $this->asset->insertFile('icon-chrome.svg','images'); ?>" alt="Icono chrome">
				<span class="browser-name">Google Chrome</span>
				<span class="browser-version">Version 48+</span>
			</li>
			<li class="list-inline-item">
				<img class="browser-img" src="<?= $this->asset->insertFile('icon-firefox.svg','images'); ?>" alt="Icono firefox">
				<span class="browser-name">Mozilla Firefox</span>
				<span class="browser-version">Version 30+</span>
			</li>
			<li class="list-inline-item">
				<img class="browser-img" src="<?= $this->asset->insertFile('icon-safari.svg','images'); ?>" alt="Icono safari">
				<span class="browser-name">Apple Safari</span>
				<span class="browser-version">Version 10+</span>
			</li>
			<li class="list-inline-item">
				<img class="browser-img" src="<?= $this->asset->insertFile('icon-edge.svg','images'); ?>" alt="Icono safari">
				<span class="browser-name">Microsoft Edge</span>
				<span class="browser-version">Version 14+</span>
			</li>
		</ul>
		<?php endif; ?>
	</div>
</div>
