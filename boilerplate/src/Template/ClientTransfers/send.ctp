<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siųsti pinigus - Mano Piniginė</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .back-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
        }

        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
        }

        .wallet-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .current-balance {
            font-size: 1.5rem;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #f44336;
        }

        .form-help {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .transfer-summary {
            background: #e8f4f8;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
            border-left: 4px solid #667eea;
        }

        .summary-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: 600;
            color: #333;
        }

        .balance-after {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .balance-after.low {
            color: #ff9800;
        }

        .balance-after.insufficient {
            color: #f44336;
        }

        .balance-after.good {
            color: #4caf50;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .header {
                padding: 1rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="https://rontgen.lt/lending/img/logo_white.png" alt="Rontgen">
        </div>
        <?= $this->Html->link('← Grįžti į valdymo skydą', ['controller' => 'Client', 'action' => 'dashboard'], ['class' => 'back-btn']) ?>
    </div>

    <div class="container">
        <!-- Flash Messages -->
        <?= $this->Flash->render() ?>

        <div class="form-card">
            <h1 class="form-title">💸 Siųsti pinigus</h1>
            <p class="form-subtitle">Pervesk pinigus į kitą piniginę akimirksniu</p>

            <div class="wallet-info">
                <div class="current-balance">€<?= number_format($senderWallet->balance, 2) ?></div>
                <p>Galimas balansas</p>
            </div>

            <?= $this->Form->create(null, ['url' => ['action' => 'send']]) ?>

            <div class="form-group">
                <label class="form-label" for="recipient_wallet_address">Gavėjo piniginės adresas</label>
                <?= $this->Form->control('recipient_wallet_address', [
                    'type' => 'text',
                    'class' => 'form-input',
                    'placeholder' => 'WLT1234567890ABCDEF...',
                    'required' => true,
                    'label' => false,
                    'pattern' => 'WLT[A-Z0-9]+',
                    'title' => 'Piniginės adresas turi prasidėti WLT ir toliau eiti raidės bei skaičiai'
                ]) ?>
                <div class="form-help">Įveskite piniginės adresą asmens, kuriam norite siųsti pinigus</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="amount">Suma (EUR)</label>
                <?= $this->Form->control('amount', [
                    'type' => 'number',
                    'class' => 'form-input',
                    'placeholder' => '0.00',
                    'required' => true,
                    'min' => 0.01,
                    'step' => 0.01,
                    'max' => $senderWallet->balance,
                    'label' => false
                ]) ?>
                <div class="form-help">Maksimali suma: €<?= number_format($senderWallet->balance, 2) ?></div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Aprašymas (neprivalomas)</label>
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'class' => 'form-input',
                    'placeholder' => 'Kam skirtas šis pervedimas?',
                    'rows' => 3,
                    'label' => false
                ]) ?>
                <div class="form-help">Pridėkite pastabą, kad prisimintumėte šį pervedimą</div>
            </div>

            <div class="transfer-summary">
                <div class="summary-title">Pervedimo santrauka</div>
                <div class="summary-row">
                    <span class="summary-label">Iš:</span>
                    <span class="summary-value"><?= h(substr($senderWallet->address, 0, 20)) ?>...</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Į:</span>
                    <span class="summary-value" id="recipient-display">Įveskite gavėjo adresą</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Suma:</span>
                    <span class="summary-value" id="amount-display">€0.00</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Balansas po pervedimo:</span>
                    <span class="summary-value balance-after good" id="balance-after">€<?= number_format($senderWallet->balance, 2) ?></span>
                </div>
            </div>

            <div class="form-actions">
                <?= $this->Form->button('Siųsti pinigus', [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'confirm' => 'Ar tikrai norite siųsti šiuos pinigus? Šio veiksmo negalima atšaukti.'
                ]) ?>
                <?= $this->Html->link('Atšaukti', ['controller' => 'Client', 'action' => 'dashboard'], [
                    'class' => 'btn btn-secondary'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const recipientInput = document.querySelector('input[name="recipient_wallet_address"]');
        const amountInput = document.querySelector('input[name="amount"]');
        const recipientDisplay = document.getElementById('recipient-display');
        const amountDisplay = document.getElementById('amount-display');
        const balanceAfter = document.getElementById('balance-after');
        const currentBalance = <?= json_encode($senderWallet->balance) ?>;

        function updateSummary() {
            // Update recipient display
            if (recipientInput.value) {
                recipientDisplay.textContent = recipientInput.value.substring(0, 20) + '...';
            } else {
                recipientDisplay.textContent = 'Įveskite gavėjo adresą';
            }

            // Update amount display and balance after
            if (amountInput.value && parseFloat(amountInput.value) > 0) {
                const amount = parseFloat(amountInput.value);
                amountDisplay.textContent = '€' + amount.toFixed(2);

                const newBalance = currentBalance - amount;
                balanceAfter.textContent = '€' + newBalance.toFixed(2);

                // Update balance color
                balanceAfter.className = 'summary-value balance-after';
                if (newBalance < 0) {
                    balanceAfter.classList.add('insufficient');
                    balanceAfter.textContent += ' (Nepakanka lėšų!)';
                } else if (newBalance < 10) {
                    balanceAfter.classList.add('low');
                } else {
                    balanceAfter.classList.add('good');
                }
            } else {
                amountDisplay.textContent = '€0.00';
                balanceAfter.textContent = '€' + currentBalance.toFixed(2);
                balanceAfter.className = 'summary-value balance-after good';
            }
        }

        recipientInput.addEventListener('input', updateSummary);
        amountInput.addEventListener('input', updateSummary);
    });
    </script>
</body>
</html>
