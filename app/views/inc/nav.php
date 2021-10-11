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
							<a class="nav-link" href="<?php echo urlGenerator('login'); ?>"><?php echo lang('def.login'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="https://sozluk.rap90.com/"><?php echo lang('def.home'); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<main>