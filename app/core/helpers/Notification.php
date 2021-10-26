<?php

namespace app\core\helpers;

use app\core\Database;
use app\vendor\phpmailer\src\PHPMailer;
use app\vendor\phpmailer\src\Exception as PHPMailerException;

class Notification {

	function __construct() {
	}

	public static function create (string $type, array $data) {

		switch ($type) {

			case 'register':

				$link = urlGenerator('verify', [], ['token' => $data['token']]);

				$title = lang('notification.register_title');
				$mailBody = str_replace(['[USER]', '[LINK]'], [$data['u_name'], $link], lang('notification.register_mail_body'));

				dump(config('settings.mail_send_type'), true);

				if (! config('settings.mail_queue')) {

					$status = self::sendEmail([
						'title'				=> $title,
						'content'			=> $mailBody,
						'recipient_name'	=> $data['u_name'],
						'recipient_email'	=> $data['email'],
					]);
					
				} else {

					$status = 'pending';

				}

				pathChecker('app/storage/email');

				dump($status);

				$insert = [
					'date'	=> time(),
					'email'	=> $data['email'],
					'name'	=> $data['u_name']
				];

				dump($mailBody);

			break;
		}

	}

	public static function sendEmail (array $content) {

		switch (config('settings.mail_send_type')) {
			case 'smtp':
				$mail = new PHPMailer(true);
				$mail->setLanguage(lang('lang_code'));

				try {
					$mail->SMTPDebug	= 0;
					$mail->isSMTP();
					$mail->Host			= config('settings.smtp_address');
					$mail->SMTPAuth		= true;
					$mail->Username		= config('settings.smtp_email_address');
					$mail->Password		= config('settings.smtp_email_pass');
					$mail->SMTPSecure	= config('settings.smtp_secure') == 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
					$mail->Port			= config('settings.smtp_port');
					$mail->CharSet		= config('app.charset');

					
					//Recipients
					$mail->setFrom(config('settings.smtp_email_address'), config('settings.name'));
					$mail->addAddress($content['recipient_email'], $content['recipient_name']);
					$reply = config('settings.contact_email');
					if (! $reply OR $reply == '') {
						$reply = config('settings.smtp_email_address');
					}
					$mail->addReplyTo($reply, config('settings.name') );

					// Content
					$mail->isHTML(true);
					$mail->Subject = $content['title'];
					$mail->Body    = $content['content'];
					$mail->AltBody = trim(strip_tags($content['content']));

					$return = $mail->send();

				} catch (PHPMailerException $e) {

					$return = false; // $e->errorMessage();
				}
				break;
									
			default:
				$headers = "Reply-To: ". config('settings.contact_email') . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$return = mail($content['recipient_email'], $content['title'], $content['content'], $headers);
				break;
		}

		return $return;

	}

}