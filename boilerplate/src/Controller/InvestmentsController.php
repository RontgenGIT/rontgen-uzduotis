<?php

namespace App\Controller;

use App\Controller\AppController;

class InvestmentsController extends AppController
{
    public function adminView()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->loadModel('Investments');
        $investments = $this->Investments->find()
            ->contain(['Loans', 'Users'])
            ->order(['Investments.created' => 'DESC'])
            ->all();
        $this->set(compact('investments'));
    }

    public function view($id = null)
    {
        $this->loadModel('Investments');
        $investment = $this->Investments->get($id, [
            'contain' => ['Loans', 'Users']
        ]);
        $this->set(compact('investment'));
    }

    public function myInvestments()
    {
        $this->loadModel('Investments');
        $userId = $this->Auth->user('id');
        $investments = $this->Investments->find()
            ->where(['Investments.user_id' => $userId])
            ->contain(['Loans', 'Users'])
            ->toList();
        $this->set(compact('investments'));
    }

    public function adminEdit($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->loadModel('Investments');
        $investment = $this->Investments->get($id, [
            'contain' => ['Loans', 'Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $investment = $this->Investments->patchEntity($investment, $this->request->getData());
            if ($this->Investments->save($investment)) {
                $this->Flash->success(__('The investment has been updated.'));
                return $this->redirect(['action' => 'adminView']);
            }
            $this->Flash->error(__('The investment could not be updated. Please, try again.'));
        }
        $loans = $this->Investments->Loans->find('list')->toArray();
        $users = $this->Investments->Users->find('list')->toArray();
        $this->set(compact('investment', 'loans', 'users'));
    }

    public function delete($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('Investments');
        $investment = $this->Investments->get($id);
        if ($this->Investments->delete($investment)) {
            $this->Flash->success(__('The investment has been deleted.'));
        } else {
            $this->Flash->error(__('The investment could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'adminView']);
    }
}