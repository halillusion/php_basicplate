				<div class="container">
					<div class="row">
						<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
							<form class="form-section my-5" method="post" id="loginForm" onsubmit="formSender(event, 'contact'); return false">
								<h1 class="fw-light"><?php echo lang('def.contact'); ?></h1>
								<div class="form-loader position-absolute top-50 start-50 translate-middle">
									<span class="visually-hidden"><?php echo lang('def.loading'); ?></span>
									<div class="spinner-border text-light mx-auto" role="status" aria-hidden="true"></div>
								</div>
								<?php createCSRF(); ?>
								<div class="form-floating mb-3">
									<select class="form-select" id="type" name="type" aria-label="<?php echo lang('def.contact_type'); ?>">
										<option selected value="other"><?php echo lang('def.other'); ?></option>
										<option value="bug_report"><?php echo lang('def.bug_report'); ?></option>
										<option value="suggestion"><?php echo lang('def.suggestion'); ?></option>
									</select>
									<label for="type"><?php echo lang('def.contact_type'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="subject" name="subject" placeholder="<?php echo lang('def.subject'); ?>" required>
									<label for="subject"><?php echo lang('def.subject'); ?></label>
								</div>
								<div class="form-floating mb-3">
									<textarea class="form-control" placeholder="<?php echo lang('def.message'); ?>" id="message" name="message"></textarea>
									<label for="message"><?php echo lang('def.message'); ?></label>
								</div>
								<div class="d-grid mb-3">
									<button class="btn btn-primary" type="submit"><?php echo lang('def.send'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>