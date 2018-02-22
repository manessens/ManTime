<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Activity Entity
 *
 * @property int $ida
 * @property int $idp
 *
 * @property \App\Model\Entity\Activitie $activitie
 * @property \App\Model\Entity\Projet $projet
 */
class Activity extends Entity
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
        '*' => true,
        'ida' => false,
        'idp' => false,
        'activitie' => true,
        'projet' => true
    ];
}
