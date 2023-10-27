<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $image_path
 * @property bool $image_flg
 * @property string $url
 * @property bool $url_flg
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Post extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => true,
        'product_name' => true,
        'product_detail' => true,
        'url_flg' => true,
        'url_path' => true,
        'image_flg' => true,
        'image_alt_name' => true,
        'image_path' => true,
        'product_view_flg' => true,
        'product_order' => true,
        'created' => true,
        'modified' => true,
    ];
}
