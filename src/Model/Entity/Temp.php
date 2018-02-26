<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Temp Entity
 *
 * @property int $idt
 * @property \Cake\I18n\FrozenTime $date
 * @property float $time
 * @property int $idu
 * @property int $idp
 * @property int $id_profil
 * @property int $ida
 *
 * @property \App\Model\Entity\User $users
 * @property \App\Model\Entity\Projet $projet
 * @property \App\Model\Entity\Profil $profil
 * @property \App\Model\Entity\Activitie $activitie
 */
class Temp extends Entity
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
        'date' => true,
        'time' => true,
        'idu' => true,
        'idp' => true,
        'id_profil' => true,
        'ida' => true,
        'users' => true,
        'projet' => true,
        'profil' => true,
        'activitie' => true
    ];
}
