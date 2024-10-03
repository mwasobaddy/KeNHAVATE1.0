<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #d8d801ed; justify-content: center;">
                    <h5 class="modal-title" id="signupModalLabel" style="font-weight: 700;">Create a new account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 12px; background-color: white; border-radius: 50%; margin-right: 6px;"></button>
                </div>
                <div class="modal-body" style="display: flex; flex-direction: column; align-items: center; background-image: linear-gradient(to top, rgb(216 216 1 / 93%), 50%, rgb(241 241 241));">
                    <form action="/KeNHAVATE/admin/auth_controller/admit_admins.php" method="post" style="display: flex; flex-direction: column;">
                        <h5 style="text-align: center;">
                            <span style="color: red;">
                                (N/B: You will be able to log in if your account is approved by the deputy director of Innovation and Research
                                Department!!)
                            </span>
                        </h5>
                        <div class="main_flex">
                            <div class="side_flex left" style="margin-right: 28px;">
                                <div class="mb-3">
                                    <label for="firstName" class="form-label">First Name <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="As per the ID" required>
                                </div>
                                <div class="mb-3">
                                    <label for="otherNames" class="form-label">Other Names <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="otherNames" name="otherNames" placeholder="Other Names">
                                </div>
                                <div class="mb-3">
                                    <label for="idNumber" class="form-label">ID Number <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="idNumber" name="idNumber" placeholder="Your ID Number">
                                </div>
                                <div class="mb-3">
                                    <label for="mobileNumber" class="form-label">Mobile Number <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" placeholder="Reachable Mobile Number">
                                </div>
                                <div class="mb-3">
                                    <label for="personalEmail" class="form-label">Personal Email <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="personalEmail" name="personalEmail" placeholder="e.g example@gmail.com">
                                </div>
                                <!-- Add more inputs here -->
                            </div>
                            <div class="side_flex right" style="margin-left: 28px;">
                                <div class="mb-3">
                                    <label for="kenhaEmail" class="form-label">KeNHA Email <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="kenhaEmail" name="kenhaEmail" placeholder="e.g name@kenha.co.ke" required>
                                </div>
                                <div class="mb-3">
                                    <label for="staffNumber" class="form-label">Staff Number <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="staffNumber" name="staffNumber" placeholder="Your Staff Number">
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <span style="color: red;">*</span></label>
                                    <select class="form-select" name="department" id="department">
                                        <option value="" disabled selected>Select Department</option>
                                        <option value="department1">Department 1</option>
                                        <option value="department2">Department 2</option>
                                        <option value="department3">Department 3</option>
                                        <!-- Add more departments as needed -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender <span style="color: red;">*</span></label>
                                    <select class="form-select" name="gender" id="department">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                        <!-- Add more departments as needed -->
                                    </select>
                                </div>
                                <!-- Add more inputs here -->
                            </div>
                        </div>
                        <!-- Add more columns for other inputs if needed -->
                        <button type="submit" class="btn btn-primary" style="align-self: center;" name="sign_up">Sign Up</button>
                    </form>
                    <p class="mt-3">Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a></p>
                </div>
            </div>
        </div>
    </div>