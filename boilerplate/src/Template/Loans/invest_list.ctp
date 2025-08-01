<h1><?= __('Investment Opportunities & Status') ?></h1>
<style>
.table-responsive {
    width: 100%;
    overflow-x: auto;
}
.table {
    min-width: 900px;
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
    font-size: 0.98em;
}
.table th, .table td {
    padding: 8px 10px;
    vertical-align: middle;
    white-space: nowrap;
}
.table th {
    background: #f6f8fa;
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
    box-shadow: 0 1px 2px rgba(0,0,0,0.07);
    margin-right: 0.2em;
}
.badge-success    { background: #38b000; }
.badge-primary    { background: #007bff; }
.badge-secondary  { background: #6c757d; }
.badge-info       { background: #4caf50; }
.badge-warning    { background: #fd7e14; }
.badge-light      { background: #adb5bd; color: #212529; }

.action-group {
    display: inline-flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border: none;
    border-radius: 5px;
    background: #f5f5f5;
    color: #444;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.15s;
    text-decoration: none;
    min-width: 32px;
    min-height: 32px;
    justify-content: center;
}
.action-btn:hover {
    background: #e1eafd;
    color: #007bff;
}
.action-btn .icon {
    font-size: 1.2em;
    margin-right: 4px;
    display: inline-block;
}
.action-btn.invest {
    background: #e9f9ee;
    color: #38b000;
}
.action-btn.invest:hover {
    background: #d2f4db;
    color: #256000;
}
.action-btn.view {
    background: #e6f0fa;
    color: #007bff;
}
.action-btn.view:hover {
    background: #d3e6fa;
    color: #004085;
}

@media (max-width: 900px) {
    .table-responsive { margin-bottom: 16px; }
    .table th, .table td {
        font-size: 0.97em;
        padding: 6px 6px;
    }
    .action-btn span {
        display: none;
    }
}
</style>
<?php
function svgIcon($name) {
    if ($name === 'view') {
        return '<svg class="icon" viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M10 4c5 0 8 5.5 8 6s-3 6-8 6-8-5.5-8-6 3-6 8-6zm0 10c3.86 0 7-4.14 7-4s-3.14-4-7-4-7 4.14-7 4 3.14 4 7 4zm0-6a2 2 0 110 4 2 2 0 010-4z"/></svg>';
    }
    if ($name === 'invest') {
        return '<svg class="icon" viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 12H9v-2h2v2zm0-4H9V7h2v3z"/></svg>';
    }
    return '';
}
?>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><?= __('ID') ?></th>
            <th><?= __('Project Name') ?></th>
            <th><?= __('Total Amount') ?></th>
            <th><?= __('Amount Left') ?></th>
            <th><?= __('Interest Rate (%)') ?></th>
            <th><?= __('Status') ?></th>
            <th><?= __('Return Date') ?></th>
            <th><?= __('Repayment Status') ?></th>
            <th><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($loans as $loan): ?>
            <tr>
                <td><?= h($loan->id) ?></td>
                <td><?= h($loan->project_name) ?></td>
                <td><?= number_format($loan->amount, 2) ?></td>
                <td>
                    <?php
                    $amountLeft = $loan->amount - ($loan->total_invested ?? 0);
                    echo number_format($amountLeft, 2);
                    ?>
                </td>
                <td><?= number_format($loan->interest_rate, 2) ?></td>
                <td>
                    <?php if ($loan->status === 'finished'): ?>
                        <span class="badge badge-success"><?= __('Finished') ?></span>
                    <?php elseif ($loan->status === 'approved'): ?>
                        <span class="badge badge-primary"><?= __('Open') ?></span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><?= __(ucfirst($loan->status)) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= $loan->return_date ? h($loan->return_date->format('Y-m-d')) : '<em>' . __('Not set') . '</em>' ?>
                </td>
                <td>
                    <?php if (isset($loan->repaid) && $loan->repaid): ?>
                        <span class="badge badge-info"><?= __('Paid Back') ?></span>
                    <?php elseif ($loan->status === 'finished' && $loan->return_date && $loan->return_date <= \Cake\I18n\FrozenTime::now()): ?>
                        <span class="badge badge-warning"><?= __('Awaiting Repayment') ?></span>
                    <?php else: ?>
                        <span class="badge badge-light"><?= __('Pending') ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-group">
                        <?= $this->Html->link(
                            svgIcon('view') . '<span>' . __('View') . '</span>',
                            ['controller' => 'Investments', 'action' => 'view', $loan->id],
                            ['escape' => false, 'class' => 'action-btn view', 'title' => __('View')]
                        ) ?>
                        <?php if ($loan->status === 'approved'): ?>
                            <?= $this->Html->link(
                                svgIcon('invest') . '<span>' . __('Invest') . '</span>',
                                ['controller' => 'Loans', 'action' => 'invest', $loan->id],
                                ['escape' => false, 'class' => 'action-btn invest', 'title' => __('Invest')]
                            ) ?>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>