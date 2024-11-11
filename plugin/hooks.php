<?php

namespace konzentrik\Sociabli;

use Kirby\Http\Remote;
use Exception;

return [
    'page.changeStatus:after' => function ($newPage, $oldPage) {
        $publishStatusOptions = ['draft', 'public'];

        if (!$newPage->isDraft() && $oldPage->isDraft()) {
            $webhookId = option('konzentrik.sociabli.webhookId', null);
            $webhookToken = option('konzentrik.sociabli.token', null);

            $fieldIntro = option('konzentrik.sociabli.fields.intro', 'description');
            $fieldText = option('konzentrik.sociabli.fields.text', 'text');
            $fieldImage = option('konzentrik.sociabli.fields.image', null);
            $fieldTags = option('konzentrik.sociabli.fields.tags', 'tags');

            $publishStatus = option('konzentrik.sociabli.publishStatus', 'draft');

            if (is_null($webhookId) || is_null($webhookToken)) {
                return;
            }

            if (!in_array($publishStatus, $publishStatusOptions)) {
                throw new Exception('Sociabli: Invalid publish status ' . $publishStatus . '. Must be one of ' . implode(', ', $publishStatusOptions));
            }

            try {
                $headers = ['Authorization: Bearer ' . $webhookToken, 'Content-Type: application/json'];
                $text = $newPage->{$fieldText}()->value();

                $pregResult = preg_match_all('/\(image:\s*(\S+)(.*)\)/i', $text, $imageMatches);

                if ($pregResult !== false && count($imageMatches[1]) > 0) {
                    foreach ($imageMatches[1] as $index => $imageName) {
                        if (!isset($imageName)) {
                            return;
                        }

                        $pageFile = $newPage->file($imageName);

                        if (is_null($pageFile)) {
                            continue;
                        }

                        $alt = $pageFile->alt()->isEmpty() ? '' : $pageFile->alt()->value();
                        $text = str_replace($imageMatches[0][$index], '![' . $alt . '](' . $pageFile->url() . ')', $text);
                    }
                }

                $requestBody = [
                    'title' => $newPage->title()->value(),
                    'intro' => $newPage->{$fieldIntro}()->value(),
                    'text' => $text,
                    'tags' => $newPage->{$fieldTags}()->split(','),
                    'url' => $newPage->url(),
                    'publishStatus' => $publishStatus,
                ];

                if (!is_null($fieldImage)) {
                    $image = $newPage->{$fieldImage}();
                    if (!is_null($image) && $image->isNotEmpty()) {
                        $requestBody['image'] = $image->toFile()->url();
                    }
                }

                Remote::request('https://smoggy-rosabelle-konzentrik-754b049a.koyeb.app/webhook/' . $webhookId, [
                    'method' => 'POST',
                    'headers' => $headers,
                    'data' => json_encode($requestBody),
                ]);

                // TODO feat: set crosspost toggle to false after successful crosspost
            } catch (Exception $e) {
                throw new Exception('Error sending post to Sociabli: ' . $e->getMessage());
            }
        }
    },
];
