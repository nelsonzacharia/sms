<?php

namespace Nelson\Sms;

use Exception;
use GuzzleHttp\Client;
use Nelson\Sms\Models\SmsLog;

class Sms
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $senderId;
    protected bool $logEnabled;
    protected Client $client;
    protected $config;
    protected $authHeader;

    public function __construct(array $config)
    {
        info('Sms config'. print_r($config, true));
        $this->config = $config;
        $this->authHeader = $this->generateAuthHeader();
        $this->baseUrl = $config['base_url'];
        $this->apiKey = $config['api_key']??'';
        $this->senderId = $config['sender_id']??'';
        $this->logEnabled = $config['log_enabled']?? false;
        $this->client = $config['client'] ?? new Client(['base_uri' => $this->baseUrl,'timeout' => 15]);
    }

    /**
     * Send a single SMS message
     */
    public function send(string $to, string $message, ?string $reference = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to' => $to,
            'text' => $message,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('/text/single', $payload);
    }

    /**
     * Send one message to multiple destinations
     */
    public function sendToMultiple(array $recipients, string $message, ?string $reference = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to' => $recipients,
            'text' => $message,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('/text/single', $payload);
    }

    /**
     * Send multiple messages to multiple destinations
     */
    public function sendMultipleMessages(array $messages, ?string $reference = null): array
    {
        $payload = [
            'messages' => $messages,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('/text/multi', $payload);
    }

    /**
     * Schedule a message
     */
    public function schedule(string $to, string $message, string $date, string $time, ?string $reference = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to' => $to,
            'text' => $message,
            'date' => $date,
            'time' => $time,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('/text/schedule', $payload);
    }

    /**
     * Fetch delivery reports
     */
    public function getDeliveryReports(): array
    {
        return $this->dispatchRequest('/delivery-reports', [], 'GET');
    }

    /**
     * Core method for sending SMS request and handling response
     */
    protected function dispatchRequest(string $endpoint, array $payload = [], string $method = 'POST'): array
    {
        $responseData = [
            'status' => 'failed',
            'code' => null,
            'message' => null,
            'body' => null,
            'status_object' => null, // store API status block if available
        ];

        try {
            $options = [
                'headers' => [
                    'Authorization' => $this->authHeader,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ];

            if ($method === 'POST') {
                $options['json'] = $payload;
                $response = $this->client->post($endpoint, $options);
            } else {
                $response = $this->client->get($endpoint, $options);
            }

            $responseBody = json_decode($response->getBody(), true);
            $responseCode = $response->getStatusCode();

            // Extract status block (if present)
            $statusObject = $responseBody['status'] ?? null;

            $responseData = [
                'status' => ($responseCode === 200) ? 'success' : 'failed',
                'code' => $responseCode,
                'message' => $responseBody['message'] ?? ($statusObject['description'] ?? 'OK'),
                'body' => $responseBody,
                'status_object' => $statusObject,
            ];
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }

        // Log SMS only for POST (sending or scheduling)
        if ($this->logEnabled && $method === 'POST') {
            $this->logToDatabase($payload, $responseData);
        }

        return $responseData;
    }

    /**
     * Save SMS details and response in the database
     */
    protected function logToDatabase(array $payload, array $responseData): void
    {
        // Normalize recipients
        $recipients = [];

        if (isset($payload['to'])) {
            $recipients = is_array($payload['to']) ? $payload['to'] : [$payload['to']];
        } elseif (isset($payload['messages'])) {
            foreach ($payload['messages'] as $msg) {
                if (isset($msg['to'])) {
                    $recipients = array_merge($recipients, (array)$msg['to']);
                }
            }
        }

        $recipients = array_unique($recipients);

        // Extract status data if present
        $status = $responseData['status_object'] ?? [];

        foreach ($recipients as $recipient) {
            SmsLog::create([
                'recipient' => $recipient,
                'message' => $payload['text'] ?? json_encode($payload['messages'] ?? []),
                'status' => $responseData['status'],
                'response_code' => $responseData['code'],
                'response_message' => $responseData['message'],
                'response_body' => json_encode($responseData['body']),
                'status_group_id' => $status['groupId'] ?? null,
                'status_group_name' => $status['groupName'] ?? null,
                'status_id' => $status['id'] ?? null,
                'status_name' => $status['name'] ?? null,
                'status_description' => $status['description'] ?? null,
            ]);
        }
    }


    private function generateAuthHeader()
    {
        $credentials = $this->config['username'] . ':' . $this->config['password'];
        return 'Basic ' . base64_encode($credentials);
    }


    /**
     * Send a single SMS message
     */
    public function sendTestSingle(string $to, string $message, ?string $reference = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to' => $to,
            'text' => $message,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('test/text/single', $payload);
    }

    public function sendMultipleDestinationTest(array $recipients, string $message, ?string $reference = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to' => $recipients,
            'text' => $message,
        ];

        if ($reference) {
            $payload['reference'] = $reference;
        }

        return $this->dispatchRequest('test/text/single', $payload);
    }
}
