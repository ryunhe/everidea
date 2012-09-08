<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="###">Everidea</a>
			<form class="navbar-form" method="GET">
				<input type="text" class="navbar-input" name="q" value="<?= $query ?>" placeholder="在想啥呢…" autocomplete="off" />
				<input type="button" class="btn btn-primary need-auth" value="添加点子" data-toggle="modal" data-target="#modal_post" />
			</form>
		</div>
	</div>
</div>

<div id="modal_post" class="modal hide">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>添加新点子</h3>
	</div>
	<form class="form form-post idea-post" action="/api/ideas/">
		<div class="modal-body">
			<div class="control-group">
				<label class="label-post">From “<?= $visitor->username ?>” : </label>
				<textarea name="summary" class="input-post input-xlarge post-summary" rows="5"></textarea>
			</div>
			<div class="control-group">
				<label class="label-post">Tags : </label>
				<input type="text" name="tags" class="input-post input-xlarge post-tags" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="button" class="btn" value="取消" data-dismiss="modal" />
			<input type="submit" class="btn btn-primary" value="添加点子" />
		</div>
	</form>
</div>