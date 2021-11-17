				<div class="container-fluid management">
					<div class="row">
						<div class="col-12 col-md-2 sidebar">
							<?php require(path('app/views/inc/management_nav.php')); ?>
						</div>
						<div class="col-12 col-md-10 content">
							<h1 class="m-0"><?php echo lang('page.users'); ?></h1>
							<p class="text-muted"><?php echo lang('page.users_desc'); ?></p>
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="usersTable">
								</table>
							</div>
						</div>
					</div>
				</div>