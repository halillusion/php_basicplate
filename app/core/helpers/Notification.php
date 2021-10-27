<?php

namespace app\core\helpers;

use app\core\Database;
use app\vendor\phpmailer\PHPMailer;
use app\vendor\phpmailer\Exception as PHPMailerException;

class Notification {

	function __construct() {
	}

	public static function create (string $type, array $data) {

		switch ($type) {

			case 'register':

				$link = urlGenerator('verify', [], ['token' => $data['token']]);

				$title = lang('notification.register_title');
				$mailBody = str_replace(['[USER]', '[LINK]'], [$data['u_name'], $link], lang('notification.register_mail_body'));

				if (! config('settings.mail_queue')) {

					$status = self::sendEmail([
						'title'				=> $title,
						'content'			=> $mailBody,
						'recipient_name'	=> $data['u_name'],
						'recipient_email'	=> $data['email'],
					]);

					$status = $status ? 'completed' : 'pending';
					
				} else {

					$status = 'pending';

				}

				$fileName = slugGenerator($title.'_'.$data['u_name'].'_'.tokenGenerator(8)).'.html';

				pathChecker('app/storage/email/'.$status);
				file_put_contents(path('app/storage/email/'.$status.'/'.$fileName), $mailBody);

				$insert = [
					'date'		=> time(),
					'email'		=> $data['email'],
					'name'		=> $data['u_name'],
					'title'		=> $title,
					'user_id'	=> $data['user_id'],
					'sender_id'	=> 0,
					'file'		=> $fileName,
					'status'	=> $status
				];

				return (new Database)
					->table('email_logs')
					->insert($insert);

			break;
		}

	}

	public static function sendEmail (array $content) {

		if (config('app.dev_mode')) {
			$content['recipient_email'] = config('settings.contact_email');
		}

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

					$return = false;
					// dump($e->errorMessage());
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