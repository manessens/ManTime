<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Projet Entity
 *
 * @property int $idp
 * @property int $idc
 * @property \Cake\I18n\FrozenTime $date_debut
 * @property \Cake\I18n\FrozenTime $date_fin
 */
class Projet extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'idc' => true,
        'date_debut' => true,
        'date_fin' => true
    ];
}
