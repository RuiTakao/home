<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddImageNameAddStatusAddPostOrderToPosts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('posts');
        $table->addColumn('image_name', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('status', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('post_order', 'integer', [
            'default' => 0,
            'limit' => 11,
        ]);
        $table->update();
    }
}
