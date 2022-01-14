<?php

return [
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
    'scopes'                           => [
        /** View and manage your Google Analytics data. */
        "https://www.googleapis.com/auth/analytics",
        /** Edit Google Analytics management entities. */
        "https://www.googleapis.com/auth/analytics.edit",
        /** Manage Google Analytics Account users by email address. */
        "https://www.googleapis.com/auth/analytics.manage.users",
        /** View Google Analytics user permissions. */
        "https://www.googleapis.com/auth/analytics.manage.users.readonly",
        /** Create a new Google Analytics account along with its default property and view. */
        "https://www.googleapis.com/auth/analytics.provision",
        /** View your Google Analytics data. */
        "https://www.googleapis.com/auth/analytics.readonly",
        /** Manage Google Analytics user deletion requests. */
        "https://www.googleapis.com/auth/analytics.user.deletion"
    ]
];
