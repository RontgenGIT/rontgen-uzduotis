<div class="users form content">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('register.title') ?></legend>
        <?= $this->Form->control('email', ['label' => __('register.email')]) ?>
        <?= $this->Form->control('password', ['label' => __('register.password')]) ?>
    </fieldset>
    <?= $this->Form->button(__('register.submit')) ?>
    <?= $this->Form->end() ?>
</div>
