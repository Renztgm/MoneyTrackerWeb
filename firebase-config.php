<?php
$firebaseConfig = [
    'apiKey' => 'AIzaSyCxMoF0mTrZYej5K8h1_MkXQ3eKQ-4FZvE',
    'databaseURL' => 'https://moneytrackerweb-default-rtdb.firebaseio.com',
    'configs' => [
        'authApp' => [
            'apiKey' => 'AIzaSyCxMoF0mTrZYej5K8h1_MkXQ3eKQ-4FZvE',
            'authDomain' => 'moneytracker-c1dd1.firebaseapp.com',
            'projectId' => 'moneytracker-c1dd1',
            'storageBucket' => 'moneytracker-c1dd1.firebasestorage.app',
            'messagingSenderId' => '426881166394',
            'appId' => '1:426881166394:web:8f8ad1b09fc5b9267ede6e'
        ],
        'app' => [
            'apiKey' => 'AIzaSyCxMoF0mTrZYej5K8h1_MkXQ3eKQ-4FZvE',
            'authDomain' => 'moneytracker-c1dd1.firebaseapp.com',
            'projectId' => 'moneytracker-c1dd1',
            'storageBucket' => 'moneytracker-c1dd1.firebasestorage.app',
            'messagingSenderId' => '71356341269',
            'appId' => '1:71356341269:web:8ae54dbdfebe06acd3c21c',
            'measurementId' => 'G-49036TSCHY'
        ]
    ]
];

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/javascript');
    $configsJson = json_encode($firebaseConfig['configs'], JSON_UNESCAPED_SLASHES);
    $apiKeyJson = json_encode($firebaseConfig['apiKey'], JSON_UNESCAPED_SLASHES);
    $dbUrlJson = json_encode($firebaseConfig['databaseURL'], JSON_UNESCAPED_SLASHES);
    echo "window.FIREBASE_CONFIGS = $configsJson;\n";
    echo "window.FIREBASE_API_KEY = $apiKeyJson;\n";
    echo "window.FIREBASE_DB_URL = $dbUrlJson;\n";
    exit;
}

return [
    'apiKey' => $firebaseConfig['apiKey'],
    'databaseURL' => $firebaseConfig['databaseURL']
];
