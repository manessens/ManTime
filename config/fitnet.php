<?php
return [
    'fitnet' => [
        'login'=>'matthias.vincent@manessens.com',
        'password'=>'M@nV17!%',
        'base'=>'https://evaluation.fitnetmanager.com/',
        'wait'=>'Attente',
        'end'=>'Terminé',
        'run'=>'Lancé',
        'err'=>'Erreur',
        'nerr'=>'Vérifié',
        'logdir'=>'fitnet_log',
        'logdir_end'=>'fitnet_log/final',
        'logname'=>'Log_export_fitnet_',
        'logname_end'=>'Log_final_export_fitnet_',
        'abs_path'=>'/var/www/html/preprod/ManTime/tmp/',
        'profil'=>[
            '1'=>[ // Mancorp
                '1'=>'0', // Technique ABAP
                '2'=>'0', // Technique ABAP expert
                '3'=>'0', // Fonctionnel / BI / BC
                '4'=>'0', // Expert / CP / Référent
            ],
            '2'=>[ // Nantes
                '1'=>'154', // Technique ABAP
                '2'=>'153', // Technique ABAP expert
                '3'=>'155', // Fonctionnel / BI / BC
                '4'=>'152', // Expert / CP / Référent
            ],
            '3'=>[ // Paris
                '1'=>'140', // Technique ABAP
                '2'=>'142', // Technique ABAP expert
                '3'=>'144', // Fonctionnel / BI / BC
                '4'=>'146', // Expert / CP / Référent
            ],
        ],
    ],
];
