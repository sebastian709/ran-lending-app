<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Password Reset</title>
   <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; background: #f2f4f6; font-family: Arial, sans-serif;">
   <div style="max-width: 600px; margin: auto; background: #fff; border-radius: 0px; overflow: hidden;">
      
      <!-- Header -->
      <div style="background: rgba(59, 130, 244, 0.9); padding: 20px; text-align: center; color: white;">
         <h1 style="font-family: 'Pacifico', cursive, Arial, sans-serif; font-size: 28px; margin: 0;">RAN Serenity</h1>
      </div>
      
      <!-- Main Content -->
      <div style="padding: 30px; text-align: left; color: #333;">
         <h1 style="color: #4A90E2; font-size: 20px; margin-top: 0;">Password Reset Requested</h1>
         <p>We received a request to reset your password for your RAN Serenity account.</p>
         <p>Use the OTP code below to proceed:</p>
         <div style="font-size: 50px; font-weight: bold; color: #4A90E2; letter-spacing: 4px; margin: 40px 0; text-align: center;">
            {{ $otp }}
         </div>
         <p>This one-time code remains valid for the next <strong>10 minutes</strong>.</p>
         <p>If you didn't ask for a password reset, you can safely ignore this message — your account will remain secure.</p>
         <p style="margin-top: 30px;">Take care,<br><strong>RAN Serenity Team</strong></p>
      </div>
      
      <!-- Ads Section -->
      <div style="padding: 20px; text-align: center; background: #f9f9f9;">
         <p style="color: #999; font-size: 14px;">Explore more from RAN Serenity:</p>
         <table align="center" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;">
            <tr>
               <td style="width: 50%; padding: 10px;" align="center">
                  <a href="/jewelry" target="_blank" style="text-decoration: none; color: inherit;">
                     <div style="background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 10px;">
                        <p style="margin: 10px 0 0; font-size: 14px; font-weight: bold;">RAN Serenity Jewelry</p>
                     </div>
                  </a>
               </td>
               <td style="width: 50%; padding: 10px;" align="center">
                  <a href="/travel-and-tours" target="_blank" style="text-decoration: none; color: inherit;">
                     <div style="background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 10px;">
                        <p style="margin: 10px 0 0; font-size: 14px; font-weight: bold;">RAN Serenity Travel and Tours</p>
                     </div>
                  </a>
               </td>
            </tr>
            <tr>
               <td style="width: 50%; padding: 10px;" align="center">
                  <a href="/hub" target="_blank" style="text-decoration: none; color: inherit;">
                     <div style="background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 10px;">
                        <p style="margin: 10px 0 0; font-size: 14px; font-weight: bold;">RAN Serenity Hub</p>
                     </div>
                  </a>
               </td>
               <td style="width: 50%; padding: 10px;" align="center">
                  <a href="/shop" target="_blank" style="text-decoration: none; color: inherit;">
                     <div style="background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 10px;">
                        <p style="margin: 10px 0 0; font-size: 14px; font-weight: bold;">RAN Serenity Shop</p>
                     </div>
                  </a>
               </td>
            </tr>
         </table>
      </div>
      
      <!-- Footer -->
      <div style="font-size: 12px; color: #aaa; text-align: center; padding: 20px;">
         You’re receiving this email because you have an account with <strong>RAN Serenity</strong>.<br>
         Prefer fewer emails? <a href="https://yourdomain.com/unsubscribe" style="color: #aaa; text-decoration: underline;">Adjust your preferences</a>.<br><br>
         &copy; {{ date('Y') }} RAN Serenity. All rights reserved.
      </div>
   </div>
</body>
</html>
