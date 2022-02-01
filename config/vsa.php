<?php
return [
    'vsa' => [
        'token'=>'',
        'base'=>'https://synvance.vsactivity.com/api/',
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
            '1'=>'CDS_TMA_TechABAP', // Technique ABAP
            '2'=>'CDS_-_TMA_ABAP_Expert', // Technique ABAP expert
            '3'=>'CDSExpertSAP', // Fonctionnel confirmé
            '4'=>'CDS_TMA_Fonctionnel', // Fonctionnel / BI / BC
            '5'=>'CDS_TMA_CPExpert', // Expert / CP / Référent
            '6'=>'EPM-BI-CF', // EPM & BI - Cons. confirmé
            '7'=>'EPM-BI-MANAGER-EXPERT', // EPM & BI MANAGER EXPERT
            '8'=>'EPM-BI_CS', // EPM & BI Cons. sénior
            '9'=>'SALESFORCE-CONSULTING', // SALESFORCE-CONSULTING
            '10'=>'SAP-CFC', // SAP-Cons.confirmé fonc.
            '11'=>'SAP-CFJ', // SAP-Cons.junior fonc.
            '12'=>'SAP-CFS', // SAP-Cons.sénior fonc.
            '13'=>'SAP-CTC', // SAP-Cons.confirmé tech.
            '14'=>'SAP-CTJ', // SAP-Cons.junior tech.
            '15'=>'SAP-CTS', // SAP-Cons.sénior tech.
            '16'=>'SAP-MANAGER-EXPERT-ARCHI-CDP', // SAP-Manager Expert Archi CDP
        ],
    ],
];
