<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_templates')->delete();
        
        \DB::table('email_templates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'reset_password',
                'subject' => 'Reset Password',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">Reset Your Password</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Tap the button below to reset your account password. If you didn\'t request for reset password, you can safely delete this email.
</h3>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#787759;text-align: center;">Reset Password</a>
</div>
<p style="font-size:17px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">If that doesn\'t work, copy and paste the following link in your browser:{{link}}</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","link":"Link for reset password","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-13 22:38:27',
                'updated_at' => '2021-04-29 14:17:44',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'sign_up_confirmation',
                'subject' => 'Sign up Confirmation',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;">Verify your email to start using {{app_name}}</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Thank you for signing up. Click the button below to verify your {{app_name}} account.
</h3>
<div style="margin: 40px 0; text-align:center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 150px;background-color:#787759;text-align: center;">Verify Email Address</a>
</div>
<p style="font-size:17px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">If that doesn\'t work, copy and paste the following link in your browser: <a href="{{link}}" target="_blank">{{link}}</a></p>
<p style="font-size:17px;line-height: 25px;font-weight: normal;color: #555;">If you did not create an account, no further action is required.</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","link":"Link for Verify Email Address","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-12-03 23:28:21',
                'updated_at' => '2021-04-29 14:20:02',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'send_password',
                'subject' => 'Account Password',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">Account Password</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">To login your account, please use the following password: </span><b>{{password}}</b></p>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">Do not share this password with anyone. {{app_name}} takes your account security very seriously. {{app_name}} will never ask you to disclose your password.</span></p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","password":"Account Password"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-02-28 12:34:55',
                'updated_at' => '2021-04-29 14:22:12',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'reset_two_factor_authentication',
            'subject' => 'Reset Two Factor Authentication (2FA)',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
</h3><h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; color: rgb(0, 0, 0); font-size: 22px;">Reset Two Factor Authentication (2FA)</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">Hi {{name}},
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;margin-bottom: 0;">
Please open Google Authenticator App and reset your 2FA by adding below details:
</h3>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size:18px;   line-height: 25px;">
<table style="border: 1px solid #ddd;width: 100%;">
<tbody>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">App Name :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{app_name}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Email :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{email}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Secret Key :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{secret_key}}</td>
</tr>
</tbody></table>

</div>
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","email":"User email","secret_key":"Google Authenticator Secret Key For Reset Two Factor Authentication"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-10-05 12:47:13',
                'updated_at' => '2021-04-29 14:24:02',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'email_verification_otp',
                'subject' => 'Email Verification OTP',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">Email Verification OTP (One Time Password)</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">To verify your request, please use the following code: </span><b>{{code}}</b></p>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">If you didn\'t request this, you can ignore this email or let us know.</span></p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","code":"6 Digits Code"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-12 15:19:04',
                'updated_at' => '2021-08-12 16:38:52',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'deposit_request',
                'subject' => 'Deposit Request',
                'content' => '<div style="padding: 10px 30px;">
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 22px; margin-top: 20px; color: rgb(0, 0, 0);">Deposit Request</h3><h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 18px; line-height: 25px; font-weight: normal;">
Hi Admin, 
</h3>
<p style="line-height: 25px; margin-top: 40px; margin-bottom: 40px;"><font color="#555555" face="Segoe, Segoe UI, sans-serif"><span style="font-size: 17px;">The deposit request has been submitted by <b>{{name}} ({{email}})</b>. Click on the button below to view the deposit request.</span></font></p>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#787759;text-align: center;">View Deposit Request</a>
</div>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","email":"Email Address","link":"Link for view deposit request","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-13 17:53:50',
                'updated_at' => '2021-08-16 12:30:48',
            ),
            6 => 
            array (
                'id' => 7,
                'type' => 'withdrawal_request',
                'subject' => 'Withdrawal Request',
                'content' => '<div style="padding: 10px 30px;">
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 22px; margin-top: 20px; color: rgb(0, 0, 0);">Withdrawal Request</h3><h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 18px; line-height: 25px; font-weight: normal;">
Hi Admin, 
</h3>
<p style="line-height: 25px; margin-top: 40px; margin-bottom: 40px;"><font color="#555555" face="Segoe, Segoe UI, sans-serif"><span style="font-size: 17px;">The withdrawal request has been submitted by <b>{{name}} ({{email}})</b>. Click on the button below to view the withdrawal request.</span></font></p>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#787759;text-align: center;">View Withdrawal Request</a>
</div>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","email":"Email Address","link":"Link for view withdrawal request","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-13 17:56:05',
                'updated_at' => '2021-08-16 12:31:06',
            ),
            7 => 
            array (
                'id' => 8,
                'type' => 'investment_request',
                'subject' => 'Investment Request',
                'content' => '<div style="padding: 10px 30px;">
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 22px; margin-top: 20px; color: rgb(0, 0, 0);">Investment Request</h3><h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 18px; line-height: 25px; font-weight: normal;">
Hi Admin, 
</h3>
<p style="line-height: 25px; margin-top: 40px; margin-bottom: 40px;"><font color="#555555" face="Segoe, Segoe UI, sans-serif"><span style="font-size: 17px;">An investment request has been submitted by <b>{{name}} ({{email}})</b>. Click on the button below to view the investment request.</span></font></p>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#787759;text-align: center;">View Investment Request</a>
</div>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","email":"Email Address","link":"Link for view investment request","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-26 18:06:46',
                'updated_at' => '2021-08-26 18:06:46',
            ),
            8 => 
            array (
                'id' => 9,
                'type' => 'account_approval',
                'subject' => 'Account Approval By Admin',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">Account Approval By Admin</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">Congratulations! your account has been approved by the admin. Now you are able to log in to the site.</span></p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-30 13:07:22',
                'updated_at' => '2021-08-30 13:07:22',
            ),
            9 => 
            array (
                'id' => 10,
                'type' => 'account_disabled',
                'subject' => 'Account Disabled',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">Your account is disabled</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">Your account is disabled because your account balance is less than 0.01 for more than 3 months. Please contact with Admin in case of any concerns.</span></p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-08-30 13:13:05',
                'updated_at' => '2021-08-30 13:13:05',
            ),
            10 => 
            array (
                'id' => 11,
                'type' => 'account_approve_request',
                'subject' => 'Account Approval By Admin Request',
                'content' => '<div style="padding: 10px 30px;">
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 22px; margin-top: 20px; color: rgb(0, 0, 0);">Registered New User</h3>
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; font-size: 18px; line-height: 25px; font-weight: normal;">
Hi Admin, 
</h3>
<p style="line-height: 25px; margin-top: 40px; margin-bottom: 40px;"><font color="#555555" face="Segoe, Segoe UI, sans-serif"><span style="font-size: 17px;">New Investor registered on your website&nbsp;</span><br><span style="font-size: 17px;"><b>Name:</b></span><b style="font-size: 17px;">{{name}} </b><br><b style="font-size: 17px;">Email:{{email}}</b><span style="font-size: 17px;">.</span><br><span style="font-size: 17px;">Click on the button below to view the request.&nbsp;</span></font></p>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#787759;text-align: center;" referrerpolicy="origin">View Request</a>
</div>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","email":"Email Address","link":"Link for view register request","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-09-16 18:06:46',
                'updated_at' => '2021-09-16 10:00:50',
            ),
            11 => 
            array (
                'id' => 12,
                'type' => 'document_req',
                'subject' => 'Documents uploaded Successfully',
                'content' => '<div style=" padding:10px 30px 10px; 
<h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; color: rgb(0, 0, 0); font-size: 22px;">Documents uploaded Successfully</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi {{name}}, 
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Thank you for uploading the documents. We will review your documents, {{setting_days}} days are required for the verification purpose.</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">In case of any concern, contact admin.&nbsp;</h3>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","email":"Email Address","link":"Link for view register request","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-09-16 18:06:46',
                'updated_at' => '2021-09-16 10:00:50',
            ),
            12 => 
            array (
                'id' => 13,
                'type' => '2fa_disable',
                'subject' => '2FA Disable',
                'content' => '<div style=" padding:10px 30px 10px; 
                <h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; color: rgb(0, 0, 0); font-size: 22px;">Your 2FA is disabled</h3>
                <h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
                Hi {{name}}, 
                </h3>                
                <h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
                Your 2FA is disabled because you forgot your password and App.&nbsp; Please contact with Admin in case of any concerns.</h3>
                </div>

                <div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
                <div style="margin-top: 30px;  font-size: 15px; color: #555;">
                <p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers</p>
                {{app_name}}
                </div>
                </div>',

                'info' => '{"name":"User full name","email":"Email Address","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-09-16 18:06:46',
                'updated_at' => '2021-09-16 10:00:50',
            ),
            13 => 
            array (
                'id' => 14,
                'type' => 'email_informed',
                'subject' => 'Email Change',
                'content' => '<div style=" padding:10px 30px 10px; 
                <h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; color: rgb(0, 0, 0); font-size: 22px;"> </h3>
                <h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
                Hi {{name}}, 
                </h3>                
                <h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
                Your Email is changed. The reset password link is sent to the new email address. Please contact with Admin in case of any concerns.</h3>
                </div>

                <div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
                <div style="margin-top: 30px;  font-size: 15px; color: #555;">
                <p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers</p>
                {{app_name}}
                </div>
                </div>',

                'info' => '{"name":"User full name","email":"Email Address","app_name":"Website name"}',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-09-16 18:06:46',
                'updated_at' => '2021-09-16 10:00:50',
            ),
        ));
        
        
    }
}


 