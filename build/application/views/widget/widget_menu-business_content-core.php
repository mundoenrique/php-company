<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($this->session->has_userdata('logged')): ?>
<nav class="navbar-secondary line-main-nav flex bg-secondary items-center">
	<ul class="main-nav-user flex my-0 list-style-none">
		<li class="nav-item mr-1 inline big-modal <?= setCurrentPage($currentClass, lang('GEN_MENU_ENTERPRISE')); ?>">
			<a class="nav-link pr-2 semibold primary" href="<?= base_url($enterpriseUrl); ?>">
				<?= lang('GEN_MENU_ENTERPRISE') ?>
			</a>
		</li>
		<?php if($this->session->has_userdata('user_access')): ?>
		<?php foreach($userAccess AS $firstLevel): ?>
		<?php $textMenu = $this->create_menu->mainMenu($firstLevel->idPerfil); ?>
		<li class="nav-item mr-1 inline <?= setCurrentPage($currentClass, $textMenu); ?>">
			<a class="nav-link px-2 semibold primary"><?= $textMenu; ?></a>
			<ul class="dropdown-user pl-0 regular tertiary bg-secondary list-style-none list-inline">
				<?php $secondLevel = $this->create_menu->secondaryMenu($firstLevel) ?>
				<?php foreach($secondLevel->second AS $submenu): ?>
				<li <?= $submenu->link != 'javascript:' ? 'class="big-modal"' : ''; ?>>
					<a <?= $submenu->link ? 'href="'.$submenu->link.'"' : '';  ?>><?= $submenu->text ?></a>
					<?php if(!empty($secondLevel->third) && $submenu->text == $secondLevel->third[0]->title): ?>
					<?php unset($secondLevel->third[0]) ?>
					<ul class="pl-0 regular tertiary bg-secondary list-style-none list-inline">
						<?php foreach($secondLevel->third AS $levelThird): ?>
						<li <?= $levelThird->link != 'javascript:' ? 'class="big-modal"' : ''; ?>>
							<a href="<?= $levelThird->link ?>"><?= $levelThird->text ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</nav>
<?php endif; ?>
