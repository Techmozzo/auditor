<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Client\ApiException;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\DateSigned;
use DocuSign\eSign\Model\FullName;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\InlineTemplate;
use DocuSign\eSign\Model\CompositeTemplate;
use DocuSign\eSign\Model\EnvelopeDefinition;
use Illuminate\Support\Facades\Log;

class DocuSign
{
    /**
     * @param Array $request
     */
    public function send(array $request): void
    {
        /**
         *
         * Step 2
         * Instantiate the eSign API client and set the OAuth path used by the JWT request
         *
         * Generate a new JWT access token
         *
         */
        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath(env('DS_AUTH_SERVER'));
        try {
            $accessToken = $this->getToken($apiClient);
        } catch (\Throwable $th) {
           Log::info(['Authentication-Failed' => $th->getMessage()]);
        }
        /**
         *
         * Step 3
         * Get user's info i.e. accounts array and base path
         *
         * Update the base path. The result in demo will be https://demo.docusign.net/restapi
         * User default account is always first in the array
         *
         */
        $userInfo = $apiClient->getUserInfo($accessToken);
        $accountInfo = $userInfo[0]->getAccounts();
        $apiClient->getConfig()->setHost($accountInfo[0]->getBaseUri() . env('DS_ESIGN_URI_SUFFIX'));
        /**
         *
         * Step 4
         * Build the envelope object
         *
         * Make an API call to create the envelope and display the response in the view
         *
         */
        $envelopeDefenition = $this->buildEnvelope($request);
        try {
            $envelopeApi = new EnvelopesApi($apiClient);
            $result = $envelopeApi->createEnvelope($accountInfo[0]->getAccountId(), $envelopeDefenition);
            Log::info(['Send-Envelop-success' => $result]);
        } catch (\Throwable $th) {
            Log::info(['Send-Envelop' => $th->getMessage()]);
        }
        // return view('contract.response')->with('result', $result);
    }

    /**
     * @param Array $request
     *
     * @return EnvelopeDefinition
     */
    private function buildEnvelope(array $request): EnvelopeDefinition
    {
        $document = new Document([
            'document_id' => $request['confirmation_request_id'],
            'document_base64' => base64_encode(file_get_contents($request['path'])),
            'file_extension' =>  $request['extension'],
            'name' => $request['name']
        ]);

        $signers = [];

        foreach ($request['signatories'] as $signatory) {

            $sign_here_tab = new SignHere([
                'anchor_string' => "**signature**",
                'anchor_units' => "pixels",
                'anchor_x_offset' => "100",
                'anchor_y_offset' => "0",
                'recipient_id' => $signatory->id,
            ]);

            $sign_here_tabs = [$sign_here_tab];

            $tabs = new Tabs([
                'sign_here_tabs' => $sign_here_tabs
            ]);

            $signers[] = new Signer([
                'email' => $signatory->email,
                'name' => $signatory->name,
                'recipient_id' => $signatory->id,
                'tabs' => $tabs
            ]);
        }

        $recipients = new Recipients([
            'signers' => $signers
        ]);

        $inline_template = new InlineTemplate([
            'recipients' => $recipients,
            'sequence' => "1"
        ]);
        $inline_templates = [$inline_template];
        $composite_template = new CompositeTemplate([
            'composite_template_id' => "1",
            'document' => $document,
            'inline_templates' => $inline_templates
        ]);
        $composite_templates = [$composite_template];
        $envelope_definition = new EnvelopeDefinition([
            'composite_templates' => $composite_templates,
            'email_subject' => "Audit Confirmation Request",
            'status' => "sent"
        ]);

        return $envelope_definition;
    }

    /**
     * @param ApiClient $apiClient
     *
     * @return string
     */
    private function getToken(ApiClient $apiClient): string
    {
        try {
            $privateKey = file_get_contents(storage_path(env('DS_KEY_PATH')), true);
            $response = $apiClient->requestJWTUserToken(
                $ikey = env('DS_CLIENT_ID'),
                $userId = env('DS_IMPERSONATED_USER_ID'),
                $key = $privateKey,
                $scope = env('DS_JWT_SCOPE')
            );
            $token = $response[0];
            $accessToken = $token->getAccessToken();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $accessToken;
    }
}
