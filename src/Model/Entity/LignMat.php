<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LignMat Entity
 *
 * @property int $id_ligne
 * @property int $idm
 * @property float $heur
 * @property float $jour
 *
 * @property \App\Model\Entity\Profil[] $id_profil
 */
class LignMat extends Entity
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
        'idm' => true,
        'id_profil' => true,
        'heur' => true,
        'jour' => true
    ];
}
