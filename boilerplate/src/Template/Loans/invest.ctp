<h2><?= __('Invest in Loan: {0}', h($loan->project_name)) ?></h2>
<p>
  <?= __('Total Amount:') ?> <strong><?= h($loan->amount) ?></strong><br>
  <?= __('Amount Left to Invest:') ?> <strong><?= h($amountLeft) ?></strong><br>
  <?= __('Interest Rate:') ?> <strong><?= h($loan->interest_rate) ?>%</strong>
</p>

<?= $this->Form->create($investment) ?>
<?= $this->Form->control('amount', [
  'label' => __('Invest Amount'),
  'max' => $amountLeft,
  'type' => 'number',
  'step' => '0.01'
]) ?>
<?= $this->Form->button(__('Invest Now')) ?>
<?= $this->Form->end() ?>