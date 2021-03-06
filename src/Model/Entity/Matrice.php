<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Matrice Entity
 *
 * @property int $idm
 * @property string $nom_matrice
 *
 * @property \App\Model\Entity\LignMat[] $lignes
 */
class Matrice extends Entity
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
        'nom_matrice' => true,
        'lign_mat' => true
    ];
}
