<?php
/**
 * Plugin Name: Firmao API Integration
 * Version: 1.0
 * Author: Bartosz Kibiłko
 * Description: Obsługa API Firmao z wykorzystaniem pluginu Contact Form 7
 */
include 'firmao-admin.php';


add_action('wpcf7_before_send_mail', 'sendCustomerToFirmao');
function sendCustomerToFirmao($contactForm)
{
    $submission = WPCF7_Submission::get_instance();

    // Get the post data and other post meta values.
    if ($submission) {
        $request = $submission->get_posted_data();

        $customerGroup = $request['customer-group'] ?? null;
        $requestData = [
            "description" => "Mail: {$request['your-message']}",
            "emails" => [$request['your-email']],
            "label" => $request['your-name'] . ' <' . $request['your-email'] . '>',
            "name" => "{$request['your-name']}",
            "phones" => [$request['your-telephone']],
        ];

        if (!empty($customerGroup)) {
            $requestData['customerGroups'] = [$customerGroup];
        }

        $json = json_encode($requestData);
        $url = getCustomersEndpoint();
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            [
                "Content-type: application/json; charset=UTF-8",
                "Authorization: Basic " . getAuth()
            ]
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 201 && $status != 200) {
            die("Error: call to API failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);
    }
}
