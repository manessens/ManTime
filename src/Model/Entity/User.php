<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $idu
 * @property string $id_fit
 * @property string $prenom
 * @property string $nom
 * @property string $email
 * @property string $mdp
 * @property int $ido
 * @property int $actif
 * @property int $admin
 * @property int $role
 * @property int $prem_connect
 *
 * @property \App\Model\Entity\Origine $origine
 */
class User extends Entity
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
        'id_fit' => true,
        'prenom' => true,
        'nom' => true,
        'email' => true,
        'mdp' => true,
        'actif' => true,
        'ido' => true,
        'origine' => true,
        'prem_connect' => true,
        'admin' => true,
        'role' => true
    ];

    protected function _getFullname()
    {
        return ucfirst($this->_properties['prenom']) . ' ' .
            ucfirst($this->_properties['nom']);
    }

    protected function _setMdp($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }

    protected function _setPassword2($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }

}
