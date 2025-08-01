<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mano Piniginė - Valdymo Skydas</title>
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ff5252;
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .wallet-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .balance {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .wallet-address {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #666;
            word-break: break-all;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 150px;
            justify-content: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
        }

        .action-btn.secondary:hover {
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.3);
        }

        .transactions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .transactions h3 {
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.3rem;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-desc {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .transaction-date {
            font-size: 0.8rem;
            color: #666;
        }

        .transaction-amount {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .transaction-amount.positive {
            color: #4caf50;
        }

        .transaction-amount.negative {
            color: #f44336;
        }

        .no-transactions {
            text-align: center;
            color: #666;
            padding: 2rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .header {
                padding: 1rem;
            }

            .balance {
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
                align-items: center;
            }

            .action-btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="https://rontgen.lt/lending/img/logo_white.png" alt="Rontgen">
        </div>
        <div class="user-info">
            <span>Sveiki, <?= h($this->request->getSession()->read('Auth.User.email')) ?></span>
            <?= $this->Html->link('Atsijungti', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'logout-btn']) ?>
        </div>
    </div>

    <div class="container">
        <!-- Navigation -->
        <div class="actions" style="margin-bottom:2rem; justify-content: center; flex-wrap:wrap;">
            <?= $this->Html->link('Prašyti paskolos', ['controller' => 'Loans', 'action' => 'request'], ['class' => 'action-btn']) ?>
            <?= $this->Html->link('Mano paskolos', ['controller' => 'Loans', 'action' => 'my_loans'], ['class' => 'action-btn']) ?>
            <?= $this->Html->link('Investuoti į paskolas', ['controller' => 'Loans', 'action' => 'invest_list'], ['class' => 'action-btn']) ?>
            <?= $this->Html->link('Mano investicijos', ['controller' => 'Investments', 'action' => 'my_investments'], ['class' => 'action-btn']) ?>
            <?= $this->Html->link('Kriterijai', ['controller' => 'InvestmentCriteria', 'action' => 'edit'], ['class' => 'action-btn']) ?>
        </div>

        <!-- Flash Messages -->
        <?= $this->Flash->render() ?>

        <!-- Piniginės apžvalga -->
        <div class="wallet-card">
            <div class="balance">€<?= number_format($wallet->balance, 2) ?></div>
            <p>Dabartinis balansas</p>

            <div class="wallet-address">
                <strong>Jūsų piniginės adresas:</strong><br>
                <?= h($wallet->address) ?>
                <br><small>Pasidalinkite šiuo adresu, kad gautumėte pinigų iš kitų</small>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">€<?= number_format($totalIncoming, 2) ?></div>
                    <div class="stat-label">Iš viso gauta</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">€<?= number_format($totalOutgoing, 2) ?></div>
                    <div class="stat-label">Iš viso išsiųsta</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= count($recentTransactions) ?></div>
                    <div class="stat-label">Paskutinės transakcijos</div>
                </div>
            </div>
        </div>

        <!-- Veiksmo mygtukai -->
        <div class="actions">
            <?= $this->Html->link('💸 Siųsti pinigus', ['controller' => 'ClientTransfers', 'action' => 'send'], ['class' => 'action-btn']) ?>
            <?= $this->Html->link('💳 Papildyti piniginę', ['controller' => 'ClientPayments', 'action' => 'topup'], ['class' => 'action-btn secondary']) ?>
        </div>

        <!-- Paskutinės transakcijos -->
        <div class="transactions">
            <h3>Paskutinės transakcijos</h3>
            <?php if (!empty($recentTransactions)): ?>
                <?php foreach ($recentTransactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-desc"><?= h($transaction->description) ?></div>
                            <div class="transaction-date"><?= $transaction->created->format('M j, Y g:i A') ?></div>
                        </div>
                        <div class="transaction-amount <?= $transaction->amount >= 0 ? 'positive' : 'negative' ?>">
                            <?= $transaction->amount >= 0 ? '+' : '' ?>€<?= number_format($transaction->amount, 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-transactions">
                    Transakcijų dar nėra. Pradėkite papildydami savo piniginę arba gaudami pinigų iš kitų!
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>