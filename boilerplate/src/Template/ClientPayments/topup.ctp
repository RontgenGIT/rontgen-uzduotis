<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('topup.page_title') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
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
            background: #4ecdc4;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #44a08d;
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
            background: #f0fdfc;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            border: 2px solid #4ecdc4;
        }

        .current-balance {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4ecdc4;
            margin-bottom: 0.5rem;
        }

        .amount-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .amount-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 1rem;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .amount-btn:hover {
            border-color: #4ecdc4;
            background: #f0fdfc;
        }

        .amount-btn.selected {
            background: #4ecdc4;
            color: white;
            border-color: #4ecdc4;
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
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }

        .form-help {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .payment-info {
            background: #e8f4f8;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
            border-left: 4px solid #4ecdc4;
        }

        .payment-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-details {
            color: #666;
            line-height: 1.6;
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
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.3);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .paysera-redirect {
            text-align: center;
            padding: 2rem;
        }

        .redirect-message {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .redirect-btn {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .redirect-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.3);
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

            .amount-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="https://rontgen.lt/lending/img/logo_white.png" alt="Rontgen">
        </div>
        <?= $this->Html->link(__('topup.back_button'), ['controller' => 'Client', 'action' => 'dashboard'], ['class' => 'back-btn']) ?>
    </div>

    <div class="container">
        <!-- Flash Messages -->
        <?= $this->Flash->render() ?>

        <?php if (isset($paymentFormData)): ?>
            <!-- Payment POST Form -->
            <div class="form-card">
                <div class="paysera-redirect">
                    <h1 class="form-title"><?= __('topup.redirect.title') ?></h1>
                    <p class="redirect-message">
                        <?= __('topup.redirect.message', ['amount' => number_format($amount, 2)]) ?>
                    </p>

                    <!-- Auto-submit POST form -->
                    <form id="paymentForm" method="POST" action="<?= h($paymentFormData['_payment_url']) ?>">
                        <?php foreach ($paymentFormData as $key => $value): ?>
                            <?php if ($key !== '_payment_url'): ?>
                                <input type="hidden" name="<?= h($key) ?>" value="<?= h($value) ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <button type="submit" class="redirect-btn">
                            <?= __('topup.redirect.button') ?>
                        </button>
                    </form>

                    <div class="payment-info">
                        <div class="payment-title"><?= __('topup.redirect.secure_title') ?></div>
                        <div class="payment-details">
                            <?= __('topup.redirect.secure_description') ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Top Up Form -->
            <div class="form-card">
                <h1 class="form-title"><?= __('topup.form.title') ?></h1>
                <p class="form-subtitle"><?= __('topup.form.subtitle') ?></p>

                <div class="wallet-info">
                    <div class="current-balance">€<?= number_format($wallet->balance, 2) ?></div>
                    <p><?= __('topup.form.current_balance') ?></p>
                </div>

                <?= $this->Form->create(null, ['url' => ['action' => 'topup']]) ?>

                <div class="form-group">
                    <label class="form-label"><?= __('topup.form.quick_amount_label') ?></label>
                    <div class="amount-options">
                        <div class="amount-btn" data-amount="10">€10</div>
                        <div class="amount-btn" data-amount="25">€25</div>
                        <div class="amount-btn" data-amount="50">€50</div>
                        <div class="amount-btn" data-amount="100">€100</div>
                        <div class="amount-btn" data-amount="250">€250</div>
                        <div class="amount-btn" data-amount="500">€500</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="amount"><?= __('topup.form.custom_amount_label') ?></label>
                    <?= $this->Form->control('amount', [
                        'type' => 'number',
                        'class' => 'form-input',
                        'placeholder' => '0.00',
                        'required' => true,
                        'min' => 1,
                        'max' => 1000,
                        'step' => 0.01,
                        'label' => false,
                        'id' => 'amount-input'
                    ]) ?>
                    <div class="form-help"><?= __('topup.form.amount_help') ?></div>
                </div>

                <div class="payment-info">
                    <div class="payment-title">
                        <?= __('topup.form.payment_info_title') ?>
                    </div>
                    <div class="payment-details">
                        <?= __('topup.form.payment_info_details') ?>
                    </div>
                </div>

                <div class="form-actions">
                    <?= $this->Form->button(__('topup.form.proceed_button'), [
                        'type' => 'submit',
                        'class' => 'btn btn-primary',
                        'id' => 'submit-btn'
                    ]) ?>
                    <?= $this->Html->link(__('topup.form.cancel_button'), ['controller' => 'Client', 'action' => 'dashboard'], [
                        'class' => 'btn btn-secondary'
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountButtons = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount-input');
        const submitBtn = document.getElementById('submit-btn');

        // Handle quick amount selection
        amountButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove selected class from all buttons
                amountButtons.forEach(btn => btn.classList.remove('selected'));

                // Add selected class to clicked button
                this.classList.add('selected');

                // Set the amount in the input
                const amount = this.dataset.amount;
                amountInput.value = amount;

                // Update submit button text
                updateSubmitButton(amount);
            });
        });

        // Handle custom amount input
        amountInput.addEventListener('input', function() {
            // Remove selected class from all buttons
            amountButtons.forEach(btn => btn.classList.remove('selected'));

            // Update submit button text
            updateSubmitButton(this.value);
        });

        function updateSubmitButton(amount) {
            if (amount && parseFloat(amount) > 0) {
                submitBtn.textContent = `<?= __('topup.form.pay_button') ?>`.replace('{amount}', parseFloat(amount).toFixed(2));
            } else {
                submitBtn.textContent = '<?= __('topup.form.proceed_button') ?>';
            }
        }

        // Auto-submit payment form if form data is provided
        <?php if (isset($paymentFormData)): ?>
        setTimeout(function() {
            document.getElementById('paymentForm').submit();
        }, 3000);
        <?php endif; ?>
    });
    </script>
</body>
</html>
