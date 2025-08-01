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

<?= $this->Form->control('collateral', [
    'type' => 'select',
    'options' => [1 => __('Yes'), 0 => __('No')],
    'label' => __('Collateral Available?'),
    'empty' => true
]) ?>

<?= $this->Form->control('country', [
    'type' => 'select',
    'options' => [
        'LT' => __('Lithuania'),
        'LV' => __('Latvia'),
        'EE' => __('Estonia'),
        'PL' => __('Poland'),
        'DE' => __('Germany'),
        'FR' => __('France'),
        'ES' => __('Spain'),
        'IT' => __('Italy'),
        'UK' => __('United Kingdom'),
        'US' => __('United States'),
    ],
    'label' => __('Country'),
    'empty' => true
]) ?>

<?= $this->Form->control('purpose', [
    'type' => 'select',
    'options' => [
        'Real Estate' => __('Real Estate'),
        'Business' => __('Business'),
        'Equipment Purchase' => __('Equipment Purchase'),
        'Expading Services' => __('Expading Services'),
        'Marketing' => __('Marketing'),
        'Franchise' => __('Franchise'),
    ],
    'label' => __('Purpose'),
    'empty' => true
]) ?>

<?= $this->Form->control('business_or_individual', [
    'type' => 'select',
    'options' => [
        'business' => __('Business'),
        'individual' => __('Individual'),
    ],
    'label' => __('Business or Individual?'),
    'empty' => true
]) ?>

<?= $this->Form->control('real_estate', [
    'type' => 'select',
    'options' => [1 => __('Yes'), 0 => __('No')],
    'label' => __('Do You Own Real Estate?'),
    'empty' => true
]) ?>

<?= $this->Form->button(__('Submit Request')) ?>
<?= $this->Form->end() ?>