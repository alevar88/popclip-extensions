<?php

if ($input = getenv('POPCLIP_TEXT')) {
    $tz = new DateTimeZone('UTC');
    $format = 'Y-m-d H:i:s';

    $input = trim($input);

    try {
        echo is_numeric($input)
            ? (new DateTime(date($format, (int) $input), $tz))->format($format)
            : (new DateTime($input, $tz))->getTimestamp();
        exit(0);
    } catch (Exception $e) {
        echo "$input: " . $e->getMessage();
        exit(1);
    }
}

?>