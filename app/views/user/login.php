				<div class="container">
					<div class="row">
						<div class="col-4 offset-4">
							<form class="form-section my-5" method="post" id="loginForm" onsubmit="formSender('loginForm', 'user/login'); return false">
								<h1 class="fw-light"><?php echo lang('def.login'); ?></h1>
								<p class="small text-muted"><?php echo lang('def.login_desc'); ?></p>
								<div class="form-loader position-absolute top-50 start-50 translate-middle">
									<span class="visually-hidden"><?php echo lang('def.loading'); ?></span>
									<div class="spinner-border text-light mx-auto" role="status" aria-hidden="true"></div>
								</div>
								<?php createCSRF(); ?>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="email" placeholder="name@example.com">
									<label for="email"><?php echo lang('def.email_or_username'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="password" class="form-control" id="pass" placeholder="<?php echo lang('def.password'); ?>">
									<label for="pass"><?php echo lang('def.password'); ?></label>
								</div>
								<div class="d-grid mb-3">
									<button class="btn btn-primary" type="submit"><?php echo lang('def.login'); ?></button>
								</div>
								<div class="d-flex justify-content-between">
									<a href="<?php echo urlGenerator('register'); ?>"><?php echo lang('def.register'); ?></a>
									<a href="<?php echo urlGenerator('recover'); ?>"><?php echo lang('def.recover'); ?></a>
								</div>
							</form>
						</div>
					</div>
				</div>