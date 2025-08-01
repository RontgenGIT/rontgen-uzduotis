<h1><?= __('Edit Investment') ?></h1>

<?= $this->Flash->render() ?>

<?= $this->Form->create($investment) ?>
<fieldset>
    <legend><?= __('Please update the investment details') ?></legend>
    <?= $this->Form->control('amount', [
        'label' => __('Amount'),
        'type' => 'number',
        'step' => '0.01'
    ]) ?>
    <?= $this->Form->control('status', [
        'label' => __('Status'),
        'type' => 'select',
        'options' => [
            'requested' => __('requested'),
            'approved' => __('approved'),
            'rejected' => __('rejected'),
            'funded' => __('funded')
        ]
    ]) ?>
    <?= $this->Form->control('loan_id', [
        'label' => __('Loan Project'),
        'options' => $loans
    ]) ?>
    <?= $this->Form->control('user_id', [
        'label' => __('Investor'),
        'options' => $users
    ]) ?>
</fieldset>
<?= $this->Form->button(__('Update Investment')) ?>
<?= $this->Form->end() ?>