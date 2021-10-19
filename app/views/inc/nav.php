		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<a class="navbar-brand" href="#"><?php echo config('app.name'); ?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo urlGenerator(); ?>"><?php echo lang('def.home'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo urlGenerator('contact'); ?>"><?php echo lang('def.contact'); ?></a>
						</li>
						<?php 
						if (auth()) { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo urlGenerator('account'); ?>"><?php echo lang('def.account'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-action="logout" href="javascript:;"><?php echo lang('def.logout'); ?></a>
							</li>
						<?php
						} else { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo urlGenerator('login'); ?>"><?php echo lang('def.login'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo urlGenerator('register'); ?>"><?php echo lang('def.register'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo urlGenerator('recover'); ?>"><?php echo lang('def.recover'); ?></a>
							</li>
						<?php
						}	?>
					</ul>
				</div>
			</div>
		</nav>
		<main>