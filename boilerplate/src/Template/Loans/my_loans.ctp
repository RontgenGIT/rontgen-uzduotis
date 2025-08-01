<h2><?= __('My Loans') ?></h2>
<table>
    <tr>
        <th><?= __('Project') ?></th>
        <th><?= __('Amount') ?></th>
        <th><?= __('Status') ?></th>
        <th><?= __('Interest %') ?></th>
    </tr>
    <?php foreach ($loans as $loan): ?>
        <tr>
            <td><?= h($loan->project_name) ?></td>
            <td><?= h($loan->amount) ?></td>
            <td><?= __(h($loan->status)) ?></td>
            <td><?= h($loan->interest_rate) ?></td>
            <td>
                <?php if (!$loan->repaid): ?>
                    <?= $this->Html->link(
                        __('Repay Investors Now'),
                        ['action' => 'repayInvestors', $loan->id],
                        [
                            'class' => 'btn btn-sm btn-warning',
                            'confirm' => __('Are you sure you want to repay all investors now?')
                        ]
                    ) ?>
                <?php else: ?>
                    <span class="badge badge-success"><?= __('Already Repaid') ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>