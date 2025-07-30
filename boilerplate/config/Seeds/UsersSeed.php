<?php

use Migrations\AbstractSeed;
use Cake\ORM\TableRegistry;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        // Clear existing users to avoid duplicates (safer method for SQLite)
        $existingUsers = $usersTable->find()->all();
        foreach ($existingUsers as $user) {
            $usersTable->delete($user, ['atomic' => false]);
        }

        $users = [
            [
                'email' => 'admin@test.com',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'email' => 'user1@test.com',
                'password' => 'user123',
                'role' => 'user'
            ],
            [
                'email' => 'user2@test.com',
                'password' => 'user123',
                'role' => 'user'
            ]
        ];

        foreach ($users as $userData) {
            $user = $usersTable->newEntity($userData);
            if ($usersTable->save($user, ['atomic' => false])) {
                echo "User {$userData['email']} ({$userData['role']}) seeded successfully.\n";
            } else {
                echo "Failed to seed user {$userData['email']}.\n";
                print_r($user->getErrors());
            }
        }
    }
}
