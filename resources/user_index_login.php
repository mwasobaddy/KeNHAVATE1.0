<?php if ($signup_form_processed) { ?>
    <div class="overlay_login active">
        <form action="auth/verify" method="post" class="form_login">
            <div class="close_login">X</div>
            <h3>Sign into your account</h3>
            <input type="email" placeholder="Enter Email Address" name="Email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" value="<?php echo isset($input_values['Email']) ? $input_values['Email'] : ''; ?>" required style="margin-right: 10px; width: 100%;">
            <p class="note_better"><i>* An OTP code will be sent to your email!</i></p>
            <input type="submit" value="get&nbsp;code" name="get_code" class="verify_btn">
            <span class="stat1">You don't have an account yet? <a class="signup_btn">Create one now</a></span>
        </form>
    </div>
<?php } else{ ?>
    <div class="overlay_login">
        <form action="auth/verify" method="post" class="form_login">
            <div class="close_login">X</div>
            <h3>Sign into your account</h3>
            <input type="email" placeholder="Enter Email Address" name="Email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" value="<?php echo isset($input_values['Email']) ? $input_values['Email'] : ''; ?>" required style="margin-right: 10px; width: 100%;">
            <p class="note_better"><i>* An OTP code will be sent to your email!</i></p>
            <input type="submit" value="get&nbsp;code" name="get_code" class="verify_btn">
            <span class="stat1">You don't have an account yet? <a class="signup_btn">Create one now</a></span>
        </form>
</div>
<?php } ?>

<?php if ($first_form_processed) { ?>
    <div class="overlay_OTP active">
        <form action="auth/verify" method="post" class="form_login">
            <div class="close_OTP">X</div>
            <h3>Sign into your account</h3>
            <input type="text" placeholder="Enter code here" name="OTP_code" pattern="^[0-9]+$" required style="text-align: center;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" maxlength="6" required>
            <p class="note_better"><i>Re-send code in<span id="countdown">100</span>sec</i></p>
            <input type="submit" value="resend" name="resend" class="sign_in_btn" id="resendBtn">
            <input type="submit" value="sign&nbsp;in" name="sign_in" class="sign_in_btn">
            <span class="stat1">You don't have an account yet? <a class="signup_btn">Create one now</a></span>
        </form>
    </div>
<?php } else{ ?> 
    <div class="overlay_OTP">
        <form action="auth/verify" method="post" class="form_login">
            <div class="close_OTP">X</div>
            <h3>Sign into your account</h3>
            <input type="text" placeholder="Enter code here" name="OTP_code" pattern="^[0-9]+$" required style="text-align: center;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" maxlength="6" required>
            <p class="note_better"><i>Re-send code in<span id="countdown">100</span>sec</i></p>
            <input type="submit" value="resend" name="resend" class="sign_in_btn" id="resendBtn">
            <input type="submit" value="sign&nbsp;in" name="sign_in" class="sign_in_btn">
            <span class="stat1">You don't have an account yet? <a class="signup_btn">Create one now</a></span>
        </form>
    </div>
<?php } ?>