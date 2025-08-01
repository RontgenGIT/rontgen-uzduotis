<h1><?= __('Loan Details') ?></h1>
<style>
    .table-details,
    .table-investments {
        margin-bottom: 30px;
        border-collapse: collapse;
        width: 100%;
        font-size: 1em;
        background: #fff;
    }

    .table-details th,
    .table-details td,
    .table-investments th,
    .table-investments td {
        padding: 8px 12px;
        text-align: left;
        background: #f7fbff;
        border-bottom: 1px solid #e8e8e8;
    }

    .table-details th,
    .table-investments th {
        width: 220px;
        background: #f0f4fa;
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.7em;
        font-size: 0.98em;
        font-weight: 500;
        line-height: 1.5;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 0.6em;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.07);
        margin-right: 0.2em;
    }

    .badge-success {
        background: #38b000;
    }

    .badge-primary {
        background: #007bff;
    }

    .badge-secondary {
        background: #6c757d;
    }

    .badge-info {
        background: #4caf50;
    }

    .badge-warning {
        background: #fd7e14;
    }

    .badge-light {
        background: #adb5bd;
        color: #212529;
    }
</style>

<table class="table-details">
    <tr>
        <th><?= __('Loan ID') ?></th>
        <td><?= h($loan->id) ?></td>
    </tr>
    <tr>
        <th><?= __('Project Name') ?></th>
        <td><?= h($loan->project_name) ?></td>
    </tr>
    <tr>
        <th><?= __('Description') ?></th>
        <td><?= h($loan->description) ?></td>
    </tr>
    <tr>
        <th><?= __('Country') ?></th>
        <td><?= h($loan->country ?? $loan->preferred_region) ?></td>
    </tr>
    <tr>
        <th><?= __('Business/Individual') ?></th>
        <td>
            <?php
            $labels = [
                'business' => __('Business'),
                'individual' => __('Individual'),
            ];
            echo h($labels[$loan->business_or_individual] ?? '');
            ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Loan Purpose') ?></th>
        <td>
            <?php
            $purposes = [
                'Real Estate' => __('Real Estate'),
                'Business' => __('Business'),
                'Equipment Purchase' => __('Equipment Purchase'),
                'Expading Services' => __('Expading Services'),
                'Marketing' => __('Marketing'),
                'Franchise' => __('Franchise'),
            ];
            $value = $loan->purpose ?? $loan->loan_purpose ?? '';
            echo h($purposes[$value] ?? $value);
            ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Total Amount') ?></th>
        <td><?= number_format($loan->amount, 2) ?></td>
    </tr>
    <tr>
        <th><?= __('Interest Rate (%)') ?></th>
        <td><?= number_format($loan->interest_rate, 2) ?></td>
    </tr>
    <tr>
        <th><?= __('Credit Score') ?></th>
        <td><?= h($loan->credit_score) ?></td>
    </tr>
    <tr>
        <th><?= __('Income') ?></th>
        <td><?= number_format($loan->income, 2) ?></td>
    </tr>
    <tr>
        <th><?= __('Collateral Required?') ?></th>
        <td><?= $loan->collateral ? __('Yes') : __('No') ?></td>
    </tr>
    <tr>
        <th><?= __('Real Estate Required?') ?></th>
        <td><?= $loan->real_estate ? __('Yes') : __('No') ?></td>
    </tr>
    <tr>
        <th><?= __('Risk Level') ?></th>
        <td>
            <?php
            $riskLabels = ['Very Low', 'Low', 'Medium', 'High', 'Very High'];
            $riskIndex = $loan->risk ?? 2;
            echo '<span class="badge badge-warning">' . ($riskLabels[$riskIndex] ?? $riskIndex) . '</span>';
            ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Status') ?></th>
        <td>
            <?php if ($loan->status === 'finished'): ?>
                <span class="badge badge-success"><?= __('Finished') ?></span>
            <?php elseif ($loan->status === 'approved'): ?>
                <span class="badge badge-primary"><?= __('Open') ?></span>
            <?php else: ?>
                <span class="badge badge-secondary"><?= __(ucfirst($loan->status)) ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Return Date') ?></th>
        <td><?= $loan->return_date ? h($loan->return_date->format('Y-m-d')) : '<em>' . __('Not set') . '</em>' ?></td>
    </tr>
    <tr>
        <th><?= __('Created') ?></th>
        <td><?= $loan->created ? h($loan->created->format('Y-m-d H:i')) : '' ?></td>
    </tr>
</table>

<h2><?= __('Investments') ?></h2>
<?php if (!empty($investments)): ?>
    <table class="table-investments">
        <thead>
            <tr>
                <th><?= __('Investor') ?></th>
                <th><?= __('Amount') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Invested At') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($investments as $investment): ?>
                <tr>
                    <td><?= h($investment->user->name ?? $investment->user->username ?? $investment->user_id) ?></td>
                    <td><?= number_format($investment->amount, 2) ?></td>
                    <td>
                        <?php if ($investment->status === 'funded'): ?>
                            <span class="badge badge-info"><?= __('Funded') ?></span>
                        <?php elseif ($investment->status === 'pending'): ?>
                            <span class="badge badge-warning"><?= __('Pending') ?></span>
                        <?php else: ?>
                            <span class="badge badge-light"><?= __(ucfirst($investment->status)) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $investment->created ? h($investment->created->format('Y-m-d H:i')) : '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p><?= __('No investments yet for this loan.') ?></p>
<?php endif; ?>