	<?= $this->Form->create($role); ?>
	<fieldset>
		<legend><?= __('Add Role') ?></legend>
		<?php
			echo $this->Form->input('name');
			echo $this->Form->input('login_redirect');
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit')) ?>
	<?= $this->Form->end() ?>
