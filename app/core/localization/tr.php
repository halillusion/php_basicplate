<?php

// PHP Basicplate - Turkish Translation

return [

	/**
	 *  Basic language definitions
	 * 
	 **/
	'app' => [
		'lang_code'							=> 'tr',
		'lang_bigcode'						=> 'tr-TR',
		'lang_name'							=> 'Türkçe',
		'locale'							=> 'tr_TR.UTF-8',
		'timezone'							=> 'Europe/Istanbul',
		'charset'							=> 'UTF-8',
		'dir'								=> 'ltr',
		'plural_suffix'						=> '',
	],

	/**
	 *  Basic alert and message definitions
	 * 
	 **/
	'alert' => [
		'error'								=> 'Hata.',
		'warning'							=> 'Uyarı.',
		'success'							=> 'Harika.',
		'form_cannot_empty'					=> 'Form boş gönderilemez!',
		'you_have_an_session'				=> 'Zaten bir oturuma sahipsiniz!',
		'you_have_not_an_session'			=> 'Açık bir oturumunuz yok!',
		'account_not_found'					=> 'Böyle bir hesap bulunamadı!',
		'user_info_incorrect'				=> 'Hesap bilgileriniz yanlış!',
		'user_not_found'					=> 'Böyle bir hesap yok!',
		'you_are_logged_in'					=> 'Oturumunuz açıldı!',
		'you_are_logged_out'				=> 'Oturum sonlandırıldı!',
		'session_create_fail'				=> 'Oturum oluşturulamadı!',
		'a_problem_occurred'				=> 'Bir sorun oluştu!',
		'form_token_invalid'				=> 'Form anahtarı geçersiz!',
		'not_allowed_request'				=> 'İzin verilmeyen istek!',
		'contact_form_saved'				=> 'Mesajınızı başarıyla aldık!',
		'contact_form_not_saved'			=> 'Mesajınızı alırken bir sorun oluştu!',
		'already_created_this_username'		=> 'Zaten bu kullanıcı adıyla oluşturulmuş bir hesap var.',
		'already_created_this_email'		=> 'Zaten bu eposta adresiyle oluşturulmuş bir hesap var.',
		'your_account_has_been_created'		=> 'Hesabınız başarıyla oluşturuldu!',
		'your_account_could_not_be_created'	=> 'Hesabınız oluşturulurken bir sorun oluştu!',
		'this_account_already_verified'		=> 'Bu hesap zaten doğrulandı.',
		'your_account_has_been_verified'	=> 'Hesap başarıyla doğrulandı.',
		'your_account_could_not_be_verified'=> 'Hesap doğrulanırken bir sorun oluştu!',
		'cant_send_recover_link_unverified'	=> 'E-posta doğrulaması yapılmayan hesaplara kurtarma bağlantısını gönderemiyoruz.',
		'failed_send_password_reset_link'	=> 'Şifre sıfırlama bağlantısı gönderilemedi.',
		'sent_password_reset_link'			=> 'Şifre sıfırlama bağlantısı gönderildi.',
		'cant_reset_password_unverified'	=> 'Doğrulanmamış hesaplar şifre sıfırlayamaz.',
		'cant_change_email_unverified'		=> 'Doğrulanmamış hesaplar eposta adreslerini güncelleyemez.',
		'cant_change_password_unverified'	=> 'Doğrulanmamış hesaplar şifrelerini güncelleyemez.',
		'password_has_been_reset'			=> 'Şifreniz başarıyla sıfırlandı.',
		'failed_resetting_password'			=> 'Şifre sıfırlanırken bir sorun oluştu.',
		'no_changes_to_save'				=> 'Kaydedilecek bir değişiklik yok!',
		'profile_updated'					=> 'Hesabınız başarıyla güncellendi.',
		'failed_profile_updated'			=> 'Hesabınız güncellenirken bir sorun oluştu.',
	],

	/**
	 *  Notification definitions
	 * 
	 **/
	'notification' => [
		'register_title'					=> 'Hesabınız Oluşturuldu!',
		'register_mail_body'				=> 'Selam [USER], <br>
		Hesabınız başarıyla oluşturuldu. Aşağıdaki yöntemlerle hesabınızı doğrulayabilirsiniz. <br>
		<a href="[LINK]">Şimdi doğrula.</a>',
		'recover_title'						=> 'Hesap Kurtarma',
		'recover_mail_body'					=> 'Selam [USER], <br>
		Az önce bir hesap kurtarma talebi aldık. Bu işlem size aitse aşağıdaki bağlantıyla şifrenizi yenileyebilirsiniz. <br>
		<a href="[LINK]">Şimdi yenile.</a>',
		'recover_completed_title'			=> 'Hesabınız Kurtarıldı!',
		'recover_completed_mail_body'		=> 'Selam [USER], <br>
		Şifreniz başarıyla sıfırlandı. İşlem size ait değilse lütfen iletişime geçin.',	
		'verify_email_title'				=> 'Eposta Adresini Doğrula',
		'verify_email_mail_body'			=> 'Selam [USER], <br>
		Eposta doğrulaması gerekiyor. Bunu aşağıdaki bağlantıya tıklayarak gerçekleştirebilirsin. <br>
		<a href="[LINK]">Şimdi doğrula.</a>',
	],

	/**
	 *  Other definitions
	 * 
	 **/
	'def'	=> [
		'home'								=> 'Ana sayfa',
		'login'								=> 'Giriş Yap',
		'logout'							=> 'Çıkış',
		'register'							=> 'Kayıt Ol',
		'recover'							=> 'Hesabı Kurtar',
		'login_desc'						=> 'Eposta adresiniz ya da şifrenizle giriş yapabilirsiniz.',
		'email_or_username'					=> 'Eposta Adresi ya da Kullanıcı Adı',
		'password'							=> 'Şifre',
		'loading'							=> 'Yükleniyor...',
		'contact'							=> 'İletişim',
		'account'							=> 'Hesap',
		'username'							=> 'Kullanıcı Adı',
		'email'								=> 'Eposta Adresi',
		'first_name'						=> 'Adınız',
		'last_name'							=> 'Soyadınız',
		'birth_date'						=> 'Doğum Tarihi',
		'password'							=> 'Şifre',
		'update'							=> 'Güncelle',
		'subject'							=> 'Konu',
		'send'								=> 'Gönder',
		'contact_type'						=> 'Mesaj Türü',
		'other'								=> 'Diğer',
		'bug_report'						=> 'Hata Raporlama',
		'suggestion'						=> 'Öneri, İstek',
		'message'							=> 'Mesaj',
		'verify'							=> 'Doğrula',
		'token'								=> 'Anahtar',
		'verify_desc'						=> 'Anahtarı aşağıdaki alana yapıştırıp hesabınızı doğrulayabilirsiniz.',
		'recover_desc'						=> 'Bu sayfadan hesap şifrenizi sıfırlayabilirsiniz.',
		'new_password'						=> 'Yeni Şifre',
	],

];