<h2><?= __('Loan Offers (Admin)') ?></h2>
<table>
    <tr>
        <th><?= __('Project') ?></th>
        <th><?= __('Amount') ?></th>
        <th><?= __('Status') ?></th>
        <th><?= __('Actions') ?></th>
    </tr>
    <?php foreach ($loans as $loan): ?>
        <tr>
            <td><?= h($loan->project_name) ?></td>
            <td><?= h($loan->amount) ?></td>
            <td><?= h($loan->status) ?></td>
            <td>
                <?php if ($loan->status === 'requested'): ?>
                    <?= $this->Html->link(__('Approve'), ['action' => 'adminApprove', $loan->id]) ?> |
                    <?= $this->Html->link(__('Reject'), ['action' => 'adminReject', $loan->id], [
                        'confirm' => __('Are you sure you want to reject this loan?')
                    ]) ?> |
                <?php endif; ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'adminEdit', $loan->id]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>