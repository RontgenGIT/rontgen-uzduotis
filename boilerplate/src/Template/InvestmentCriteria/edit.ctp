<h1><?= __('Set Your Investment Criteria') ?></h1>
<?= $this->Form->create($criteria, ['class' => 'criteria-form']) ?>

<div class="field">
    <?= $this->Form->control('min_credit_score', [
        'label' => __('Minimum Credit Score'),
        'class' => 'form-control'
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('min_loan_amount', [
        'label' => __('Min Loan Amount'),
        'class' => 'form-control'
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('max_loan_amount', [
        'label' => __('Max Loan Amount'),
        'class' => 'form-control'
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('risk_tolerance', [
        'type' => 'select',
        'empty' => __('Any'),
        'options' => [
            'very low' => __('Very Low'),
            'low' => __('Low'),
            'medium' => __('Medium'),
            'high' => __('High'),
            'very high' => __('Very High')
        ],
        'label' => __('Maximum Risk Tolerance'),
        'class' => 'form-select'
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('loan_purpose', [
        'type' => 'select',
        'empty' => __('Any'),
        'options' => [
            'Real Estate' => __('Real Estate'),
            'Business' => __('Business'),
            'Equipment Purchase' => __('Equipment Purchase'),
            'Expading Services' => __('Expading Services'),
            'Marketing' => __('Marketing'),
            'Franchise' => __('Franchise'),
        ],
        'label' => __('Loan Purpose'),
        'class' => 'form-select'
    ]) ?>
</div>
<div class="field">
    <label><?= __('Require Collateral') ?></label>
    <?= $this->Form->checkbox('require_collateral', [
        'class' => 'form-check-input',
        'label' => false,
        'value' => 1,
        'hiddenField' => true
    ]) ?>
</div>
<div class="field">
    <label><?= __('Require Real Estate') ?></label>
    <?= $this->Form->checkbox('require_real_estate', [
        'class' => 'form-check-input',
        'label' => false,
        'value' => 1,
        'hiddenField' => true
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('business_or_individual', [
        'type' => 'select',
        'options' => [
            'business' => __('Business'),
            'individual' => __('Individual')
        ],
        'label' => __('Business or Individual'),
        'empty' => __('Any'),
        'class' => 'form-select'
    ]) ?>
</div>
<div class="field">
    <?= $this->Form->control('preferred_region', [
        'type' => 'select',
        'multiple' => true,
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
        'label' => __('Preferred Country'),
        'empty' => __('Any'),
        'class' => 'form-select'
    ]) ?>
</div>
<div class="actions">
    <?= $this->Form->button(__('Save Criteria'), ['class' => 'button is-primary']) ?>
</div>
<?= $this->Form->end() ?>

<div class="actions">
    <?= $this->Form->postLink(
        __('Reset All Criteria'),
        ['action' => 'reset'],
        [
            'class' => 'button is-danger',
            'confirm' => __('Are you sure you want to reset all your investment criteria?')
        ]
    ) ?>
</div>