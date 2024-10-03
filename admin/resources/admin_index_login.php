<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" style="justify-content: center;">
    <div class="modal-dialog modal-lg" style="min-width: 374px; align-items: center; display: flex;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #d8d801ed; justify-content: center;">
                <h5 class="modal-title" id="loginModalLabel" style="font-weight: 700;">sign into your account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 12px; background-color: white; border-radius: 50%; margin-right: 6px;"></button>
            </div>
            <div class="modal-body" style="display: flex; flex-direction: column; align-items: center; background-image: linear-gradient(to top, rgb(216 216 1 / 93%), 50%, rgb(241 241 241));">
                <form action="" method="post" style="display: flex; flex-direction: column;">
                    <div class="main_flex">
                        <div class="side_flex left">
                            <div class="mb-3" style="text-align: center; font-weight: 500;">
                                <label for="personalEmail" class="form-label" style="color: black;">Enter Personal Email <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="personalEmail" placeholder="Enter your email" required>
                                <span style="color: red;">An OTP code will be sent to your email!</span>
                            </div>
                            <!-- Add more inputs here -->
                        </div>
                    </div>
                    <!-- Add more columns for other inputs if needed -->
                    <button type="submit" class="btn btn-primary" style="align-self: center;">Sign Up</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Create one</a></p>
            </div>
        </div>
    </div>
</div>