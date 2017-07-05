<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
    	'/'=>'site/first-page',
    	'admin'=>'admin/admin/index',
    	'meter'=>'site/meter',
    	'admin/index'=>'admin/admin/index',
    	'admin/about'=>'admin/admin/about',
	    'admin/login'=>'site/login',
	    'admin/logout'=>'site/logout',
        'admin/<controller:\w+>' => 'admin/<controller>/index',
        'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
		'/<buildingUrl:\w+>'=>'site/index'
    ]
];