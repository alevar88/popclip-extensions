<?php declare(strict_types=1);

if ($input = getenv('POPCLIP_FULL_TEXT')) {
    $project = rawurlencode(getenv('POPCLIP_OPTION_PROJECT'));
    $timeZone = getenv('POPCLIP_OPTION_TIME_ZONE') ?: 'UTC';
    $dateTime = new \DateTime('now', new DateTimeZone($timeZone));
    $title = $dateTime->format(getenv('POPCLIP_OPTION_TITLE_DATE_FORMAT') ?: 'd/m/Y H:i:s');
    $input = rawurlencode($input);
    $command = "open \"agenda://x-callback-url/create-note?project-title=$project&title=$title&text=$input\"";
    exec($command, $output, $status);
    if ($status === 0) {
        echo "ok";
    }
    exit($status);
}