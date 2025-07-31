<div class="users form content">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('login.title') ?></legend>
        <?= $this->Form->control('email', ['label' => __('login.email')]) ?>
        <?= $this->Form->control('password', ['label' => __('login.password')]) ?>
    </fieldset>
    <?= $this->Form->button(__('login.button')); ?>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('login.register_link'), ['action' => 'register']) ?>

    <div style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
        <h4><?= __('login.test_credentials.title') ?></h4>
        <?php foreach ($seedUsers as $user): ?>
            <div style="margin-bottom: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 3px;">
                <p><strong><?= __('login.test_credentials.email') ?>:</strong> <?= h($user['email']) ?></p>
                <p><strong><?= __('login.test_credentials.password') ?>:</strong> <?= h($user['password']) ?></p>
                <p><strong>Role:</strong> <?= h($user['role']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
