<?php

use FriendsOfRedaxo\BlockPeek\TemplateInstaller;

/** @var rex_addon_interface $this */

// Idempotent. Creates the hidden rex_template row on first install
// (migrating any customized rex_config['template'] + assets_head/body
// into it). Subsequent re-installs leave the row untouched.
TemplateInstaller::seedOrMigrate();

// Drop obsolete config keys. rex_config::remove() is a no-op if the key
// doesn't exist, so this is safe on fresh installs.
rex_config::remove('block_peek', 'template');
rex_config::remove('block_peek', 'assets_head');
rex_config::remove('block_peek', 'assets_body');
