<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Wallet $senderWallet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back to Wallet'), ['controller' => 'Wallets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('View Transactions'), ['controller' => 'Transactions', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="transfers form large-9 medium-8 columns content">
    <h3><?= __('Transfer Money') ?></h3>

    <div class="wallet-info">
        <h4><?= __('Your Wallet Information') ?></h4>
        <p><strong><?= __('Wallet Address:') ?></strong> <code><?= h($senderWallet->address) ?></code></p>
        <p><strong><?= __('Current Balance:') ?></strong> <?= h(number_format($senderWallet->balance, 2)) ?> <?= h($senderWallet->currency) ?></p>
        <p><em><?= __('Share your wallet address with others to receive transfers') ?></em></p>
    </div>

    <?= $this->Form->create(null, ['url' => ['action' => 'create']]) ?>
    <fieldset>
        <legend><?= __('Transfer Details') ?></legend>
        <?= $this->Form->control('recipient_wallet_address', [
            'type' => 'text',
            'label' => __('Recipient Wallet Address'),
            'required' => true,
            'placeholder' => __('Enter the recipient wallet address (e.g., WLT1234567890ABCDEF)'),
            'help' => __('Enter the wallet address of the person you want to send money to'),
            'pattern' => 'WLT[A-Z0-9]+',
            'title' => __('Wallet address must start with WLT followed by alphanumeric characters')
        ]) ?>

        <?= $this->Form->control('amount', [
            'type' => 'number',
            'label' => __('Amount'),
            'required' => true,
            'min' => 0.01,
            'step' => 0.01,
            'max' => $senderWallet->balance,
            'placeholder' => __('0.00'),
            'help' => __('Maximum amount: ') . number_format($senderWallet->balance, 2) . ' ' . h($senderWallet->currency)
        ]) ?>

        <?= $this->Form->control('description', [
            'type' => 'textarea',
            'label' => __('Description (Optional)'),
            'placeholder' => __('Enter a description for this transfer'),
            'rows' => 3,
            'help' => __('Optional description to help you remember what this transfer was for')
        ]) ?>
    </fieldset>

    <div class="transfer-summary" style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <h5><?= __('Transfer Summary') ?></h5>
        <p><strong><?= __('From:') ?></strong> <code><?= h($senderWallet->address) ?></code></p>
        <p><strong><?= __('To:') ?></strong> <span id="recipient-display"><?= __('Enter recipient wallet address above') ?></span></p>
        <p><strong><?= __('Amount:') ?></strong> <span id="amount-display"><?= __('Enter amount above') ?></span></p>
        <p><strong><?= __('Your balance after transfer:') ?></strong> <span id="balance-after"><?= number_format($senderWallet->balance, 2) ?> <?= h($senderWallet->currency) ?></span></p>
    </div>

    <div class="form-actions">
        <?= $this->Form->button(__('Transfer Money'), [
            'type' => 'submit',
            'class' => 'button success',
            'id' => 'transfer-button',
            'confirm' => __('Are you sure you want to proceed with this transfer?')
        ]) ?>
        <?= $this->Html->link(__('Cancel'), ['controller' => 'Wallets', 'action' => 'index'], [
            'class' => 'button secondary'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recipientInput = document.querySelector('input[name="recipient_wallet_address"]');
    const amountInput = document.querySelector('input[name="amount"]');
    const recipientDisplay = document.getElementById('recipient-display');
    const amountDisplay = document.getElementById('amount-display');
    const balanceAfter = document.getElementById('balance-after');
    const currentBalance = <?= json_encode($senderWallet->balance) ?>;
    const currency = <?= json_encode($senderWallet->currency) ?>;

    function updateSummary() {
        // Update recipient display
        if (recipientInput.value) {
            recipientDisplay.innerHTML = '<code>' + recipientInput.value + '</code>';
        } else {
            recipientDisplay.textContent = 'Enter recipient wallet address above';
        }

        // Update amount display and balance after
        if (amountInput.value && parseFloat(amountInput.value) > 0) {
            const amount = parseFloat(amountInput.value);
            amountDisplay.textContent = amount.toFixed(2) + ' ' + currency;

            const newBalance = currentBalance - amount;
            balanceAfter.textContent = newBalance.toFixed(2) + ' ' + currency;

            // Color coding for balance
            if (newBalance < 0) {
                balanceAfter.style.color = 'red';
                balanceAfter.textContent += ' (Insufficient funds!)';
            } else if (newBalance < 10) {
                balanceAfter.style.color = 'orange';
            } else {
                balanceAfter.style.color = 'green';
            }
        } else {
            amountDisplay.textContent = 'Enter amount above';
            balanceAfter.textContent = currentBalance.toFixed(2) + ' ' + currency;
            balanceAfter.style.color = 'black';
        }
    }

    recipientInput.addEventListener('input', updateSummary);
    amountInput.addEventListener('input', updateSummary);
});
</script>

<style>
.wallet-info {
    background-color: #e8f4f8;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    border-left: 4px solid #2ba6cb;
}

.transfer-summary {
    border: 1px solid #ddd;
}

.form-actions {
    margin-top: 20px;
}

.form-actions .button {
    margin-right: 10px;
}

.help-text {
    font-size: 0.875em;
    color: #666;
    margin-top: 5px;
}
</style>
