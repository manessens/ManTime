<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $idc
 * @property string $id_fit
 * @property int $id_agence
 * @property string $nom_client
 *
 * @property \App\Model\Entity\Agence $agence
 */
class Client extends Entity
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
        'nom_client' => true,
        'id_fit' => true,
        'id_agence' => true,
        'agence'   => true
    ];
}
