<h1><?= __('All Investments') ?></h1>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><?= __('ID') ?></th>
            <th><?= __('Investor') ?></th>
            <th><?= __('Amount') ?></th>
            <th><?= __('Status') ?></th>
            <th><?= __('Loan Project') ?></th>
            <th><?= __('Invested On') ?></th>
            <th><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($investments as $investment): ?>
            <tr>
                <td><?= h($investment->id) ?></td>
                <td>
                    <?= h($investment->user->email ?? __('Unknown')) ?>
                </td>
                <td><?= number_format($investment->amount, 2) ?></td>
                <td><?= h($investment->status) ?></td>
                <td>
                    <?= h($investment->loan->project_name ?? __('Unknown')) ?>
                </td>
                <td>
                    <?= $investment->loan->created ? h($investment->loan->created->format('Y-m-d H:i')) : '<em>' . __('N/A') . '</em>' ?>
                </td>
                <td>
                    <?= $this->Html->link(__('View'), ['action' => 'view', $investment->id], ['class' => 'btn btn-sm btn-info', 'title' => __('View')]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'admin-edit', $investment->id], ['class' => 'btn btn-sm btn-warning', 'title' => __('Edit')]) ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $investment->id],
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'confirm' => __('Are you sure you want to delete investment {0}?', $investment->id),
                            'title' => __('Delete')
                        ]
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>