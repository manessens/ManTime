<?php
return [
    // PREPROD
    'fitnet' => [
        // @TODO Ajouter un compte admin fitnet
        'login'=>'testexterne@manessens.com',
        'password'=>'motdepasse',
        'base'=>'https://manessens.fitnetmanager.com/',
        'wait'=>'Attente',
        'end'=>'Terminé',
        'run'=>'Lancé',
        'err'=>'Erreur',
        'nerr'=>'Vérifié',
        'logdir'=>'fitnet_log',
        'logdir_end'=>'fitnet_log/final',
        'logname'=>'Log_export_fitnet_',
        'logname_end'=>'Log_final_export_fitnet_',
        'abs_path'=>'/var/www/html/ManTime/tmp/',
        'profil'=>[
            '1'=>[ // Mancorp
                '1'=>null, // Technique ABAP
                '2'=>null, // Technique ABAP expert
                '3'=>null, // Fonctionnel / BI / BC
                '4'=>null, // Expert / CP / Référent
            ],
            // @TODO ID fitnet a synchroniser avec la prod
            '2'=>[ // Nantes
                '1'=>'', // Technique ABAP
                '2'=>'', // Technique ABAP expert
                '3'=>'', // Fonctionnel / BI / BC
                '4'=>'', // Expert / CP / Référent
            ],
            '3'=>[ // Paris
                '1'=>'', // Technique ABAP
                '2'=>'', // Technique ABAP expert
                '3'=>'', // Fonctionnel / BI / BC
                '4'=>'', // Expert / CP / Référent
            ],
        ],
    ],
];
