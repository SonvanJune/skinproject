<?php

namespace App\Services;

use App\DTOs\GetMailDTO;
use App\Mail\ClientContactMail;
use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class MailService
{
    //constants
    // Constants
    public const FILE_CONTENT_REGEX = '/^(?:\s*<[^>]+>.*?<\/[^>]+>\s*|.*{{\s*[\$a-zA-Z_][a-zA-Z0-9_()\'\"\s]*\s*}}.*|.*@[\w]+.*)+$/s';

    /**
     * Get all mail forms from the views directory
     * 
     * @param Request $request
     * @param UserService $userService
     * @return array as list of GetMailDTO objects
     */
    public function getAllMailForms(Request $request, UserService $userService): array
    {
        $files = File::files(resource_path('views/mail'));
        $returnedFiles = [];

        foreach ($files as $file) {
            $mail_file_name = $file->getFilename();
            $mail_name = ucwords(str_replace('_', ' ', str_replace('.blade.php', '', $mail_file_name)));

            switch ($mail_file_name) {
                case "forgeting_password.blade.php":
                case "trading.blade.php":
                case "registration.blade.php":
                    $mail_name .= " (sending OTP)";
                    break;
            }

            $key = config("mail.key", UserService::DEFAULT_ENCRYPT_KEY);

            $returnedFiles[] = new GetMailDTO($userService->encrypt_with_key($mail_file_name, $key), $mail_name, $mail_file_name);
        }

        return $returnedFiles;
    }

    /**
     * get a GetMailDTO from an encrypted mail file name
     * 
     * @param string $encrypted_file_name
     * @param UserService $userService
     * @return string|GetMailDTO
     */
    public function getMailByEncryptedFileName(string $encrypted_file_name, UserService $userService): string| GetMailDTO
    {
        $key = config("mail.key", UserService::DEFAULT_ENCRYPT_KEY);

        $mail_file_name = $userService->decrypt_with_key($encrypted_file_name, $key);

        $mail_name = ucwords(str_replace('_', ' ', str_replace('.blade.php', '', $mail_file_name)));
        $mail_path_name = resource_path('views/mail') . "/" . $mail_file_name;

        if (!file_exists($mail_path_name)) {
            return 'Mail\'s file does not exist';
        }

        try {
            $content = file_get_contents($mail_path_name);
            $content = str_replace("@extends('layouts.mail')", '', $content);
            $content = str_replace("@section('mail-content')", "", $content);
            $content = str_replace("@endsection", "", $content);
            $content = trim($content);
        } catch (Exception $e) {
            return 'Cannot read mail\'s file content';
        }

        $attributes = [
            'user' => 'Show client\'s full name',
            'app_name' => 'Show app\' name',
            'support_mail' => 'Show the support mail address',
        ];

        switch ($mail_file_name) {
            case "forgeting_password.blade.php":
            case "trading.blade.php":
            case "registration.blade.php":
                $required_attributes = [
                    'valid_duration' => 'Show the limit duration that the otp is allowed to be used',
                    'otp' => 'Show the otp code that will be sent for the client'
                ];
                break;
            case "client_contact.blade.php":
                $attributes = [
                    ...$attributes,
                    'phone' => 'Show client\'s phone number',
                    'email' => 'Show client\'s contact email address',
                    'country' => 'Show client\'s country name',
                ];

                $required_attributes = [
                    'content' => 'Show the content that the client wants to mention'
                ];
                break;
            case "invoice.blade.php":
                $attributes = [
                    ...$attributes,
                    'phone' => 'Show client\'s phone number',
                    'email' => 'Show client\'s contact email address',
                    'country' => 'Show client\'s country name',
                ];

                $required_attributes = [
                    'invoice_table' => 'Show list of ordered products',
                    'order_date' => 'Show the date that user purchased for their order',
                ];
                break;
            default:
                $required_attributes = [];
                break;
        }

        return new GetMailDTO($userService->encrypt_with_key($mail_file_name, $key), $mail_name, $mail_file_name, $content, $attributes, $required_attributes ?? []);
    }

    /**
     * update the content of a mail file
     * 
     * @param Request $request
     * @param string $encrypted_file_name
     * @param UserService $userService
     * @return bool|string
     */
    public function updateMailFile(Request $request, string $encrypted_file_name, UserService $userService): bool| string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'content' => ['required', "regex:" . self::FILE_CONTENT_REGEX]
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $getMailDTO = $this->getMailByEncryptedFileName($encrypted_file_name, $userService);

        if (is_string($getMailDTO)) {
            return $getMailDTO;
        }

        foreach ($getMailDTO->required_attributes as $attribute => $description) {
            if (!str_contains($request->content, '{{ $' . $attribute . ' }}')) {
                return "Missing required attribute: $attribute - $description";
            }
        }

        $mail_path_name = resource_path('views/mail') . "/" . $getMailDTO->mail_file_name;
        $content =  "@extends('layouts.mail')";
        $content .= "@section('mail-content')";
        $content .= $request->content;
        $content .= "@endsection";

        try {
            file_put_contents(
                $mail_path_name,
                $content
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * format html content with prettier
     * 
     * @param string $html
     * @return string formatted html content
     */
    public function formatHtmlWithPrettier($html)
    {
        $escapedHtml = escapeshellarg($html);
        return shell_exec("echo $escapedHtml | prettier --parser html");
    }

    public function sendEmailFinishPayment(array $data, User $user, UserService $userService)
    {
        $validator = Validator::make(
            $data,
            [
                'order_id' => 'required|string',
                'country' => 'nullable|max:255',
                'currency' => 'nullable|max:255',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $order = Order::where('order_id', $data['order_id'])->first();
        if (!$order) {
            return 'Order not found.';
        }

        $cart = $order->cart;
        $password = $userService->decrypt_with_key($user->user_password_level_2, UserService::DEFAULT_ENCRYPT_KEY);
        $zipFilePath = (new FileService())->createPasswordProtectedZipWithMorePathInMemory($cart->products, $password, true);
        $mail = new InvoiceMail(
            $user,
            $zipFilePath,
            __('message.webName') . '_' . 'products.zip',
            $order,
            $password,
            $data['currency'] ?? 'USD',
            $data['country'] ?? 'Not Found',
        );

        if (isset($tempZipFilePath) && file_exists($tempZipFilePath)) {
            @unlink($tempZipFilePath);
        }

        try {
            Mail::to($user->user_email)->send($mail);
            return true;
        } catch (\Exception $e) {
            return "Error sending invoice email: " . $e->getMessage();
        }
    }


    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'email' => 'required|max:255',
                'country' => 'required|max:255',
                'phone' => 'required|max:255',
                'subject' => 'required|max:255',
                'content' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $mail = new Mailable();
        $mail = new ClientContactMail(
            $request->input('name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('subject'),
            $request->input('content'),
            $request->input('country')
        );

        try {
            Mail::to(config('mail.email_customer', ''))->send($mail);
            return true;
        } catch (\Exception $e) {
            return "Error sending OTP email: " . $e->getMessage();
        }
    }
}
