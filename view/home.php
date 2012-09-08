<div id="modal_idea" class="modal fade">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>Modal header</h3>
	</div>
	<div class="modal-body">
		<p>One fine body…</p>
		</div>
		<div class="modal-footer">
		<a href="#" class="btn">Close</a>
		<a href="#" class="btn btn-primary">Save changes</a>
	</div>
</div>

<ul class="list">
	<? foreach ($ideas as $idea) {
		$isLiker = $idea->likers->exists($visitor->username);
		$likerCount = $idea->likers->count();
		?>
		<li class="list-item clearfix">
			<div class="idea-title float-left">
				<?= $idea->summary ?>
				<i class="icon-tags"></i>
				<? foreach ($idea->tags as $tag) { ?>
					<a class="idea-label" href="?q=<?= urlencode("tag:{$tag}") ?>"><?= $tag ?></a>
				<? } ?>
				<small class="idea-creator"> - <?= $idea->creator ?></small>
			</div>
			<div class="idea-like float-right">
				<a class="btn need-auth <?= $isLiker ? ' disabled' : '' ?>"
					href="/api/ideas/<?= $idea->guid ?>/likes"><i class="icon-thumbs-up"></i>
					<cite><?= $likerCount ?></cite> 人顶
				</a>
			</div>
		</li>
	<? } ?>
</ul>