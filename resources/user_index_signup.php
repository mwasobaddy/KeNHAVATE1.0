<?php if ($dont_have_an_account) { ?>
    <div class="overlay_signup active">
        <form action="auth/verify" method="post" class="form_signup">
            <div class="close_signup">X</div>
            <h3>Create a new account</h3>
            <p class="note_better" style="margin-bottom: 8px;"><i>All Fields Marked With ' * ' Are Mandatory!</i></p>
            <div class="input_a">
                <input type="text" placeholder=" * Enter Full Names * " name="Names" pattern="^[a-zA-Z\s]+$" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');"  style="margin-right: 10px;" value="<?php echo isset($input_values['Names']) ? $input_values['Names'] : ''; ?>" required>
                <input type="text" placeholder=" * Enter ID Number * " name="Id_Number"  pattern="^[0-9]+$" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="8" value="<?php echo isset($input_values['Id_Number']) ? $input_values['Id_Number'] : ''; ?>" required>
            </div>
            <div class="input_b">
                <input type="email" placeholder=" * Enter Email Address * " name="Email" pattern="^[a-zA-Z0-9]+@(gmail\.com|yahoo\.com)$"  style="margin-right: 10px; width: 56%;" value="<?php echo isset($input_values['Email']) ? $input_values['Email'] : ''; ?>" required>
                <div class="mobile_sub">
                    <select id="Code" name="Count_Code" class="code" required>
                        <option value="+2547" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '+2547' ? 'selected' : ''; ?>>+2547</option>
                        <option value="07" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '07' ? 'selected' : ''; ?>>07</option>
                        <option value="01" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '01' ? 'selected' : ''; ?>>01</option>
                    </select>
                    <input type="text" placeholder=" * Enter Mobile Number * " name="Mobile_Number" pattern="^[0-9]+$" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="8"  style="width: 74%;" value="<?php echo isset($input_values['Mobile_Number']) ? $input_values['Mobile_Number'] : ''; ?>" required>
                </div>
            </div>
            <div class="input_c">
                <select id="Gender" name="Gender" class="gender" required>
                    <option value=""> * Select Gender * </option>
                    <option value="Male" <?php echo isset($input_values['Gender']) && $input_values['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($input_values['Gender']) && $input_values['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <input type="submit" value="sign&nbsp;up" name="sign_up" class="sign_up_btn">
            <span class="stat1">Already have an account? <a class="signin_btn">Log In</a></span>
        </form>
    </div>
<?php } else{ ?>
    <div class="overlay_signup">
        <form action="auth/verify" method="post" class="form_signup">
            <div class="close_signup">X</div>
            <h3>Create a new account</h3>
            <p class="note_better" style="margin-bottom: 8px;"><i>All Fields Marked With ' * ' Are Mandatory!</i></p>
            <div class="input_a">
                <input type="text" placeholder=" * Enter Full Names * " name="Names" pattern="^[a-zA-Z\s]+$" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');"  style="margin-right: 10px;" value="<?php echo isset($input_values['Names']) ? $input_values['Names'] : ''; ?>" required>
                <input type="text" placeholder=" * Enter ID Number * " name="Id_Number"  pattern="^[0-9]+$" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="8" value="<?php echo isset($input_values['Id_Number']) ? $input_values['Id_Number'] : ''; ?>" required>
            </div>
            <div class="input_b">
                <input type="email" placeholder=" * Enter Email Address * " name="Email" pattern="^[a-zA-Z0-9]+@(gmail\.com|yahoo\.com)$"  style="margin-right: 10px; width: 56%;" value="<?php echo isset($input_values['Email']) ? $input_values['Email'] : ''; ?>" required>
                <div class="mobile_sub">
                    <select id="Code" name="Count_Code" class="code" required>
                        <option value="+2547" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '+2547' ? 'selected' : ''; ?>>+2547</option>
                        <option value="07" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '07' ? 'selected' : ''; ?>>07</option>
                        <option value="01" <?php echo isset($input_values['Count_Code']) && $input_values['Count_Code'] === '01' ? 'selected' : ''; ?>>01</option>
                    </select>
                    <input type="text" placeholder=" * Enter Mobile Number * " name="Mobile_Number" pattern="^[0-9]+$" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="8"  style="width: 74%;" value="<?php echo isset($input_values['Mobile_Number']) ? $input_values['Mobile_Number'] : ''; ?>" required>
                </div>
            </div>
            <div class="input_c">
                <select id="Gender" name="Gender" class="gender" required>
                    <option value=""> * Select Gender * </option>
                    <option value="Male" <?php echo isset($input_values['Gender']) && $input_values['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($input_values['Gender']) && $input_values['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <input type="submit" value="sign&nbsp;up" name="sign_up" class="sign_up_btn">
            <span class="stat1">Already have an account? <a class="signin_btn">Log In</a></span>
        </form>
    </div>
<?php } ?>