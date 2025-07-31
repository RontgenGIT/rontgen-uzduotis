<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= __('Edit Your Profile') ?></h3>
            </div>
            <div class="card-body">
                <?= $this->Form->create($user) ?>
                <fieldset>
                    <?= $this->Form->control('email', [
                        'label' => __('Email'),
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                    <?= $this->Form->control('current_password', [
                        'type' => 'password',
                        'label' => __('Current Password'),
                        'class' => 'form-control',
                        'required' => true,
                        'autocomplete' => 'current-password',
                        'value' => ''
                    ]) ?>
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => __('New Password'),
                        'class' => 'form-control',
                        'value' => '',
                        'autocomplete' => 'new-password'
                    ]) ?>
                </fieldset>
                <div class="mt-3">
                    <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div>
            <h3><?= __('Account Information') ?></h3>
            <dl>
                <dt><?= __('Registered:') ?></dt>
                <dd><?= h($user->created->format('M d, Y')) ?></dd>

                <dt><?= __('Last Updated:') ?></dt>
                <dd><?= h($user->modified->format('M d, Y')) ?></dd>
            </dl>
        </div>
    </div>
</div>
