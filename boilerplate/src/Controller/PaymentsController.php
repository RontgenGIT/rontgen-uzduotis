<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;

class PaymentsController extends AppController
{
    /**
     * This controller previously handled Paysera payments integration.
     * It has been replaced with internal wallet-to-wallet transfers.
     * All payment functionality is now handled by TransfersController.
     */

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Redirect to internal transfers instead of external payments
     */
    public function index()
    {
        $this->Flash->info('External payments have been replaced with internal wallet transfers.');
        return $this->redirect(['controller' => 'Transfers', 'action' => 'create']);
    }

    /**
     * Legacy method - redirects to transfers
     */
    public function buildRequest($walletId = null)
    {
        $this->Flash->info('External payments are no longer available. Use internal transfers instead.');
        return $this->redirect(['controller' => 'Transfers', 'action' => 'create']);
    }
}
