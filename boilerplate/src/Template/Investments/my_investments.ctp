<h2><?= __('My Investments') ?></h2>
<table>
    <tr>
        <th><?= __('Loan') ?></th>
        <th><?= __('Amount') ?></th>
        <th><?= __('Status') ?></th>
        <th><?= __('Interest %') ?></th>
        <th><?= __('Action') ?></th>
    </tr>
    <?php foreach ($investments as $i): ?>
        <tr>
            <td><?= h($i->loan->project_name) ?></td>
            <td><?= h($i->amount) ?></td>
            <td><?= __(h($i->status)) ?></td>
            <td><?= h($i->loan->interest_rate) ?></td>
            <td><?= $this->Html->link(__('View'), ['action' => 'view', $i->id]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>