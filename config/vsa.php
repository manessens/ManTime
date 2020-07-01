<?php
return [
    'vsa' => [
        'token'=>'',
        'base'=>'https://synvance-pp.vsactivity.com/api/',
        'wait'=>'Attente',
        'end'=>'Terminé',
        'run'=>'Lancé',
        'err'=>'Erreur',
        'nerr'=>'Vérifié',
        'logdir'=>'vsa_log',
        'logdir_end'=>'vsa_log/final',
        'logname'=>'Log_export_vsa_',
        'logname_end'=>'Log_final_export_vsa_',
        'abs_path'=>'/var/www/html/preprod/ManTime/tmp/',
        'deliveryCode'=>'CDSTMA',
        'entity'=>'157',
        'profil'=>[
            '1'=>'CDSTMA', // Technique ABAP
            '2'=>'CDS_TMA_TechABAP', // Technique ABAP expert
            '3'=>'CDSExpertSAP', // Fonctionnel confirmé
            '4'=>'CDS_TMA_Fonctionnel', // Fonctionnel / BI / BC
            '5'=>'CDS_TMA_CPExpert' // Expert / CP / Référent
        ],
    ],
];
