<?php

namespace App\Controller;

use App\Controller\AppController;

class InvestmentCriteriaController extends AppController
{
    public function edit()
    {
        $userId = $this->Auth->user('id');
        $criteria = $this->InvestmentCriteria->find()
            ->where(['user_id' => $userId])
            ->first();

        if (!$criteria) {
            $criteria = $this->InvestmentCriteria->newEntity([]);
            $criteria->user_id = $userId;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            foreach ($data as $key => $value) {
                if (is_string($value) && trim($value) === '') $data[$key] = null;
                if (is_array($value) && empty($value)) $data[$key] = null;
            }

            if (isset($data['preferred_region']) && is_array($data['preferred_region'])) {
                $data['preferred_region'] = implode(',', $data['preferred_region']);
            }

            foreach (['require_collateral', 'require_real_estate'] as $boolField) {
                $data[$boolField] = !empty($data[$boolField]) && $data[$boolField] == '1';
            }

            $criteria = $this->InvestmentCriteria->patchEntity($criteria, $data);

            if ($this->InvestmentCriteria->save($criteria)) {
                $this->Flash->success(__('Your criteria have been saved.'));
                return $this->redirect(['action' => 'edit']);
            }
            $this->Flash->error(__('Unable to save criteria.'));
        }

        $this->set(compact('criteria'));
    }

    public function reset()
    {
        $userId = $this->Auth->user('id');
        $criteriaTable = $this->InvestmentCriteria;

        $criteria = $criteriaTable->find()->where(['user_id' => $userId])->all();
        foreach ($criteria as $item) {
            $criteriaTable->delete($item);
        }

        $this->Flash->success(__('Your investment criteria have been reset.'));
        return $this->redirect(['action' => 'edit']);
    }
}
