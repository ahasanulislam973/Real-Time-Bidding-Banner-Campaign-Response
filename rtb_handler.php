<?php

// Load campaign data
$campaigns = require 'campaign.php';

// Parse the incoming bid request
$request = json_decode(file_get_contents('php://input'), true);

if (!$request) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

// Validate the bid request
if (empty($request['id']) || empty($request['imp']) || empty($request['device'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required bid request fields.']);
    exit;
}

foreach ($request['imp'] as $imp) {
    if (empty($imp['bidfloor'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid impression data in bid request. Bidfloor is required.']);
        exit;
    }
}

// Extract relevant parameters from the bid request
$device = $request['device'] ?? [];
$geo = $device['geo'] ?? [];
$bidFloor = $request['imp'][0]['bidfloor'] ?? 0;

// Filter campaigns based on bid request parameters
$eligibleCampaigns = array_filter($campaigns, function ($campaign) use ($geo, $bidFloor) {
    return ($campaign['country'] === ($geo['country'] ?? '') || $campaign['country'] === 'No Filter') &&
           $campaign['price'] >= $bidFloor;
});

// Select the campaign with the highest bid price
$selectedCampaign = null;

if (!empty($eligibleCampaigns)) {
    usort($eligibleCampaigns, function ($a, $b) {
        return $b['price'] <=> $a['price'];
    });
    $selectedCampaign = $eligibleCampaigns[0];
}

if ($selectedCampaign) {
    // Build the response
    $response = [
        'id' => $request['id'],
        'bid' => [
            'id' => $request['imp'][0]['id'],
            'price' => $selectedCampaign['price'],
            'ad' => [
                'campaign' => $selectedCampaign['campaignname'],
                'advertiser' => $selectedCampaign['advertiser'],
                'creative_type' => $selectedCampaign['creative_type'],
                'image_url' => $selectedCampaign['image_url'],
                'landing_page' => $selectedCampaign['url']
            ]
        ]
    ];
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'No suitable campaign found']);
}
