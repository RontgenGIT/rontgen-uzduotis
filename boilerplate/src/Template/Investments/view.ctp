<h1><?= __('Investment Details') ?></h1>

<table class="table table-bordered table-striped" style="max-width:600px;">
    <tr>
        <th><?= __('ID') ?></th>
        <td><?= h($investment->id) ?></td>
    </tr>
    <tr>
        <th><?= __('Investor') ?></th>
        <td>
            <?php if (!empty($investment->user)) : ?>
                <?= h($investment->user->email ?? __('N/A')) ?>
            <?php else : ?>
                <em><?= __('Unknown') ?></em>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Amount Invested') ?></th>
        <td><?= number_format($investment->amount, 2) ?></td>
    </tr>
    <tr>
        <th><?= __('Status') ?></th>
        <td>
            <?php if ($investment->status === 'funded') : ?>
                <span style="color:green; font-weight:bold;"><?= __('Funded') ?></span>
            <?php else : ?>
                <?= __(h($investment->status)) ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th><?= __('Created') ?></th>
        <td>
            <?= $investment->created
                ? $investment->created->format('Y-m-d H:i')
                : ($investment->loan->created
                    ? '<span title="Using loan date">' . $investment->loan->created->format('Y-m-d H:i') . '</span>'
                    : '<em>' . __('N/A') . '</em>'
                )
            ?>
        </td>
    </tr>
    <?php if (!empty($investment->loan)) : ?>
        <tr style="background:#f7f7f7;">
            <th colspan="2"><?= __('Loan Information') ?></th>
        </tr>
        <tr>
            <th><?= __('Project Name') ?></th>
            <td>
                <?= h($investment->loan->project_name ?? __('Unknown')) ?>
            </td>
        </tr>
        <tr>
            <th><?= __('Total Loan Amount') ?></th>
            <td><?= number_format($investment->loan->amount ?? 0, 2) ?></td>
        </tr>
        <tr>
            <th><?= __('Interest Rate (%)') ?></th>
            <td><?= h($investment->loan->interest_rate ?? __('N/A')) ?></td>
        </tr>
        <tr>
            <th><?= __('Loan Status') ?></th>
            <td><?= __(h($investment->loan->status ?? __('N/A'))) ?></td>
        </tr>
    <?php endif; ?>
</table>