				<div class="container">
					<div class="row">
						<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
							<form class="form-section my-5" method="post" id="loginForm" onsubmit="formSender(event, 'user/account'); return false">
								<h1 class="fw-light"><?php echo lang('def.account'); ?></h1>
								<div class="form-loader position-absolute top-50 start-50 translate-middle">
									<span class="visually-hidden"><?php echo lang('def.loading'); ?></span>
									<div class="spinner-border text-light mx-auto" role="status" aria-hidden="true"></div>
								</div>
								<?php createCSRF(); ?>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="u_name" name="u_name" value="<?php echo $_SESSION['user']->u_name; ?>" placeholder="<?php echo lang('def.username'); ?>" required>
									<label for="u_name"><?php echo lang('def.username'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user']->email; ?>" placeholder="name@example.com" required>
									<label for="email"><?php echo lang('def.email'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="f_name" name="f_name" value="<?php echo $_SESSION['user']->f_name; ?>" placeholder="<?php echo lang('def.first_name'); ?>" required>
									<label for="f_name"><?php echo lang('def.first_name'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="l_name" name="l_name" value="<?php echo $_SESSION['user']->l_name; ?>" placeholder="<?php echo lang('def.last_name'); ?>" required>
									<label for="l_name"><?php echo lang('def.last_name'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="date" class="form-control" id="b_date" name="b_date" value="<?php echo date('d-m-Y', $_SESSION['user']->b_date); ?>" placeholder="<?php echo lang('def.birth_date'); ?>">
									<label for="b_date"><?php echo lang('def.birth_date'); ?></label>
								</div>
								<hr class="mb-5">
								<div class="form-floating mb-3">
									<input type="password" class="form-control" id="pass" name="password" placeholder="<?php echo lang('def.password'); ?>" required>
									<label for="pass"><?php echo lang('def.password'); ?></label>
								</div>
								<div class="d-grid mb-3">
									<button class="btn btn-primary" type="submit"><?php echo lang('def.update'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>