<!-- Register Modal -->
<div class="modal fade" id="adminModalAdd" role="dialog">
	<div class="modal-dialog">
		<!-- Register Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#add">New entry</a></li>
						<li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
						<li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
					</ul>
				</h4>
			</div>
				<div class="modal-body" style="padding-left: 30px; padding-right: 30px;">
					<div class="tab-content">
						<div id="add" class="tab-pane fade in active">
							<div class="form-group">
								<label for="username"><span class="glyphicon glyphicon-user"></span>&nbsp;Username</label>
								<input class="form-control" type="text" name="username" id="username" placeholder="Username.." />
							</div>
							<div class="form-group">
								<label for="password"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Password</label>
								<input class="form-control" type="password" name="password" id="password" placeholder="Password.." />
							</div>
						</div>
						<div id="menu1" class="tab-pane fade">
							<h3>Menu 1</h3>
							<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
						<div id="menu2" class="tab-pane fade">
							<h3>Menu 2</h3>
							<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
						</div>
						<div id="menu3" class="tab-pane fade">
							<h3>Menu 3</h3>
							<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Submit</button>
					<button type="button" class="btn btn-default pull-left clearFormButton">Reset</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<a class="btn btn-primary" data-target="#adminModalAdd" data-toggle="modal" href="#">
	<span class="glyphicon glyphicon-cog"></span>&nbsp;Admin Options
</a>

<?php

?>