<?php

namespace FriendsOfRedaxo\BlockPeek;

use rex;
use rex_be_controller;
use rex_extension_point;
use rex_request;
use rex_template;

class TemplateListHider
{
    /**
     * OUTPUT_FILTER (LATE) on the templates list page only. Strips the <tr> for our
     * hidden row by matching template_id=<id> with a non-digit boundary so id "1"
     * doesn't accidentally match "10".
     */
    public static function register(rex_extension_point $ep): void
    {
        if (!rex::isBackend()) {
            return;
        }
        if (rex_be_controller::getCurrentPagePart(1) !== 'templates') {
            return;
        }
        if (rex_request::request('function', 'string', '') !== '') {
            return;
        }

        $template = rex_template::forKey(TemplateInstaller::KEY);
        if ($template === null) {
            return;
        }
        $hideId = $template->getId();

        $content = $ep->getSubject();
        if (!is_string($content)) {
            return;
        }

        $result = preg_replace_callback(
            '/<tr\b[^>]*>.*?<\/tr>/s',
            static function (array $m) use ($hideId): string {
                return preg_match('/template_id=' . $hideId . '\D/', $m[0]) ? '' : $m[0];
            },
            $content
        );

        if ($result !== null && $result !== $content) {
            $ep->setSubject($result);
        }
    }
}
