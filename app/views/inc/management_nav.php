<ul class="nav flex-column mt-5">
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management'); ?>" href="<?php echo urlGenerator('management'); ?>">
			<?php echo lang('def.management'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/pages'); ?>" href="<?php echo urlGenerator('management/pages'); ?>">
			<?php echo lang('def.pages'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/contents'); ?>" href="<?php echo urlGenerator('management/contents'); ?>">
			<?php echo lang('def.contents'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/categories'); ?>" href="<?php echo urlGenerator('management/categories'); ?>">
			<?php echo lang('def.categories'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/media'); ?>" href="<?php echo urlGenerator('management/media'); ?>">
			<?php echo lang('def.media'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/contact-forms'); ?>" href="<?php echo urlGenerator('management/contact-forms'); ?>">
			<?php echo lang('def.contact_forms'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/users'); ?>" href="<?php echo urlGenerator('management/users'); ?>">
			<?php echo lang('def.users'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/user-roles'); ?>" href="<?php echo urlGenerator('management/user-roles'); ?>">
			<?php echo lang('def.user_roles'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/logs'); ?>" href="<?php echo urlGenerator('management/logs'); ?>">
			<?php echo lang('def.logs'); ?>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link<?php echo $this->activeLink('management/settings'); ?>" href="<?php echo urlGenerator('management/settings'); ?>">
			<?php echo lang('def.settings'); ?>
		</a>
	</li>
</ul>