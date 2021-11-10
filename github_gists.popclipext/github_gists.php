<?php declare(strict_types=1);

if ($inputText = getenv('POPCLIP_FULL_TEXT')) {
    try {
        $gistUrl = createGist($inputText);
        if (filter_var($gistUrl, FILTER_VALIDATE_URL)) {
            echo $gistUrl;
            exit(0);
        }
    } catch (Exception|JsonException $e) {
        echo $e->getMessage();
        exit(1);
    }

    echo "Something went wrong";
    exit(1);
}

/**
 * @throws JsonException|Exception
 */
function createGist(string $content): ?string {
    $timeZone = getenv('POPCLIP_OPTION_TIME_ZONE') ?: 'UTC';
    $dateTime = new DateTime('now', new DateTimeZone($timeZone));

    $filename = "auto_gist_{$dateTime->getTimestamp()}";

    $postData = [
        'public' => false,
        'description' => sprintf(
            "This gist created by auto at %s", $dateTime->format('r')
        ),
        'files' => [
            $filename => [
                'content' => $content,
            ],
        ],
    ];

    $ch = curl_init('https://api.github.com/gists');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($postData, JSON_THROW_ON_ERROR),
        CURLOPT_HTTPHEADER => [
            'Authorization: token ' . getenv('POPCLIP_OPTION_GITHUB_TOKEN'),
            'Accept: application/vnd.github.v3+json',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
        ]
    ]);

    $result = json_decode(curl_exec($ch), true, 512, JSON_THROW_ON_ERROR);
    return $result['files'][$filename]['raw_url'] ?? null;
}