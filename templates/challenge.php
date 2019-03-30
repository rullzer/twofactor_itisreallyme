<p><?php p($l->t(
		'You are trying to login to Nextcloud using the credentials of %s.
		Only press the button below if you are %s. If you are not %s cancel the login now!',
			[
				$_['user'],
				$_['user'],
				$_['user'],
			]
	)) ?></p>

<form method="POST" class="itisreallyme-form">
	<input type="hidden" name="challenge" value="yes it is me!">
	<button class="primary two-factor-submit" type="submit">
		<?php p($l->t('Yes it is really me!')); ?>
	</button>
</form>
