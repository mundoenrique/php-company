<?php
defined('BASEPATH') or exit('No direct script access allowed');
// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
$config['recaptcha_site_key'] = lang('SETT_KEY_RECAPTCHA'); //'6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf';
$config['recaptcha_secret_key'] = '6Lejt6MUAAAAAM9P_R_Fz55p8-NUOS-P7HqXIJ6W';
// reCAPTCHA supported 40+ languages listed here:
// https://developers.google.com/recaptcha/docs/language
$config['recaptcha_lang'] = 'en';
/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */