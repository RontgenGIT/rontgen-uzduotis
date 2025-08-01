<h2><?= __('Request Loan') ?></h2>
<?= $this->Form->create($loanRequest) ?>
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
    'value' => null,
    'empty' => true
]) ?>

<?= $this->Form->button(__('Submit Request')) ?>
<?= $this->Form->end() ?>