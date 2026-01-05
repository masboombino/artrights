<?php

use App\Models\Complain;

return [
    // Supported types
    'types' => [
        'complaint' => Complain::TYPE_COMPLAINT,
        'report' => Complain::TYPE_REPORT,
    ],
    
    // Targets for each role (works for both complaints and reports)
    'targets' => [
        'artist' => [
            'gestionnaire' => Complain::TYPE_ARTIST_TO_GESTIONNAIRE,
            'admin' => Complain::TYPE_ARTIST_TO_ADMIN,
        ],
        'agent' => [
            'gestionnaire' => Complain::TYPE_AGENT_TO_GESTIONNAIRE,
            'admin' => Complain::TYPE_AGENT_TO_ADMIN,
        ],
        'gestionnaire' => [
            'admin' => Complain::TYPE_GESTIONNAIRE_TO_ADMIN,
            'agent' => Complain::TYPE_GESTIONNAIRE_TO_AGENT,
        ],
        'admin' => [
            'super_admin' => Complain::TYPE_ADMIN_TO_SUPERADMIN,
            'gestionnaire' => Complain::TYPE_ADMIN_TO_GESTIONNAIRE,
            'agent' => Complain::TYPE_ADMIN_TO_AGENT,
        ],
        'super_admin' => [
            'admin' => Complain::TYPE_SUPERADMIN_TO_ADMIN,
        ],
    ],
];

