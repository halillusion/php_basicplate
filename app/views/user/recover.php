				<div class="container">
					<div class="row">
						<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
							<form class="form-section my-5" method="post" id="verifyForm" onsubmit="formSender(event, 'user/recover'); return false">
								<h1 class="fw-light"><?php echo lang('def.recover'); ?></h1>
								<p class="small text-muted"><?php echo lang('def.recover_desc'); ?></p>
								<div class="form-loader position-absolute top-50 start-50 translate-middle">
									<span class="visually-hidden"><?php echo lang('def.loading'); ?></span>
									<div class="spinner-border text-light mx-auto" role="status" aria-hidden="true"></div>
								</div>
								<?php 
								createCSRF();
								if (isset($_GET['token']) !== false) {
									?>
									<div class="form-floating mb-3">
										<input type="text" class="form-control" id="token" name="verify_token" value="<?php echo filter($_GET['token']); ?>" placeholder="<?php echo lang('def.token'); ?>" required>
										<label for="token"><?php echo lang('def.token'); ?></label>
									</div>
									<div class="form-floating mb-3">
										<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo lang('def.password'); ?>" required>
										<label for="password"><?php echo lang('def.new_password'); ?></label>
									</div>
									<?php
								} else {
									?>
									<div class="form-floating mb-3">
										<input type="email" class="form-control" id="email" name="email" placeholder="<?php echo lang('def.email'); ?>" required>
										<label for="email"><?php echo lang('def.email'); ?></label>
									</div>
									<?php
								}	?>
								<div class="d-grid mb-3">
									<button class="btn btn-primary" type="submit"><?php echo lang('def.recover'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>