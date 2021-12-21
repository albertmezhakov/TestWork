<?php

return [
    'OAuth2' => [
        'CLIENT_ID' => <client_id>,
        'GUILD_ID' => <guild_id>,
        'CLIENT_SECRET' => '<secret>',
        'BOT_TOKEN' => '<token>',

        'REDIRECT_URI' => 'https://<domen_site>/site/callback',
        'AUTH_URL' => 'https://discord.com/api/oauth2/authorize',
        'TOKEN_URL' => 'https://discord.com/api/oauth2/token',
        'REVOKE_URL' => 'https://discord.com/api/oauth2/token/revoke',
        'API_URL' => 'https://discord.com/api/users/@me',
        'GUILD_URL' => 'https://discord.com/api/guilds/%d/members/%d',
    ],
];
