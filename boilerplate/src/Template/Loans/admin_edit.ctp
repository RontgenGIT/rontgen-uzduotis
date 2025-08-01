<h2><?= __('Edit Loan') ?></h2>
<?= $this->Form->create($loan) ?>
<?= $this->Form->control('project_name', ['label' => __('Project Name')]) ?>
<?= $this->Form->control('description', ['label' => __('Description')]) ?>
<?= $this->Form->control('amount', ['label' => __('Borrowing Amount')]) ?>
<?= $this->Form->control('income', ['label' => __('Monthly Income')]) ?>
<?= $this->Form->control('credit_score', ['label' => __('Credit Score')]) ?>
<?= $this->Form->control('interest_rate', ['label' => __('Interest Rate')]) ?>
<?= $this->Form->control('return_date', [
    'type' => 'date',
    'label' => __('Return Date'),
    'required' => true,
    'empty' => true
]) ?>
<?= $this->Form->control('status', [
    'label' => __('Status'),
    'type' => 'select',
    'options' => [
        'requested' => __('Requested'),
        'approved' => __('Approved'),
        'rejected' => __('Rejected'),
        'finished' => __('Finished')
    ]
]) ?>
<?= $this->Form->button(__('Save Changes')) ?>
<?= $this->Form->end() ?>