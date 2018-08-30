<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function welcome($user)
    {
        $this->to($user->email)
            ->subject(sprintf('Bienvenu %s sur ManTime', $user->fullname))
            ->emailFormat('html');
    }

    public function resetPassword($user)
    {
        $this
            ->to($user->email)
            ->subject('Réinitialisation du mot de passe ManTime')
            ->emailFormat('html');
    }
    
    public function implementedEvents()
    {
        return [
            'Users.afterSave' => 'onRegistration'
        ];
    }

    public function onRegistration(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $this->send('welcome', [$entity]);
        }elseif ($entity->prem_connect) {
            $this->send('resetPassword', [$entity]);
        }
    }
}
