<div class="container mt-5 mb-5" style="margin-top: 0px!important; box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 10px; background-color: rgb(255, 255, 255); border-radius: 10px;">
  <h2 style="text-align: center;">SUBMIT AN IDEA</h2>
  <form id="upload_idea_form" action="submit-idea" method="post" style="align-items: center; display: flex; flex-direction: column;" enctype="multipart/form-data">
    <div class="flex_side">
        <div class="row idea_form_row" style="flex-wrap: wrap;">
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="ideaTitle" class="form-label" style="color: #1e1e1e; font-weight: 600;">Idea Title(<span id="charCount" style="color: red;">25 Characters Remaining</span>)</label>
                    <input type="text" class="form-control" id="ideaTitle" name="ideaTitle" value="<?php echo isset($input_values['ideaTitle']) ? $input_values['ideaTitle'] : ''; ?>" required>
                </div>
            </div>
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="innovationAreas" class="form-label" style="color: #1e1e1e; font-weight: 600;">Innovation Areas</label>
                    <select class="form-select" id="innovationAreas" name="innovationAreas"required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="Customer Delivery Service" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Customer Delivery Service' ? 'selected' : ''; ?>>CUSTOMER DELIVERY SERVICE</option>
                        <option value="Quality and Safety" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Quality and Safety' ? 'selected' : ''; ?>>QUALITY AND SAFETY</option>
                        <option value="Road Construction Materials" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Road Construction Materials' ? 'selected' : ''; ?>>ROAD CONSTRUCTION MATERIALS</option>
                        <option value="Road Construction Technologies" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Road Construction Technologies' ? 'selected' : ''; ?>>ROAD CONSTRUCTION TECHNOLOGIES</option>
                        <option value="Climate Resilience" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Climate Resilience' ? 'selected' : ''; ?>>CLIMATE RESILIENCE</option>
                        <option value="Value for Money" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Value for Money' ? 'selected' : ''; ?>>VALUE FOR MONEY</option>
                        <option value="Revenue Generation" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'Revenue Generation' ? 'selected' : ''; ?>>REVENUE GENERATION</option>
                        <option value="Other" <?php echo isset($input_values['innovationAreas']) && $input_values['innovationAreas'] === 'OTHER' ? 'selected' : ''; ?>>OTHER</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="flex_side">
        <div class="row">
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="briefDescription" class="form-label" style="color: #1e1e1e; font-weight: 600;">Brief Description(<span style="color: red;"><span id="briefDescriptionCount">200</span>characters remaining</span>)</label>
                    <textarea class="form-control" id="briefDescription" name="briefDescription" rows="4" required><?php echo isset($input_values['briefDescription']) ? $input_values['briefDescription'] : ''; ?></textarea>
                </div>
            </div>
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="problemStatement" class="form-label" style="color: #1e1e1e; font-weight: 600;">Problem Statement(<span style="color: red;"><span id="problemStatementCount">200</span>characters remaining</span>)</label>
                    <textarea class="form-control" id="problemStatement" name="problemStatement" rows="4" required><?php echo isset($input_values['problemStatement']) ? $input_values['problemStatement'] : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="flex_side">
        <div class="row">
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="proposedSolution" class="form-label" style="color: #1e1e1e; font-weight: 600;">Proposed Solution(<span style="color: red;"><span id="proposedSolutionCount">300</span>characters remaining</span>)</label>
                    <textarea class="form-control" id="proposedSolution" name="proposedSolution" rows="4" required><?php echo isset($input_values['proposedSolution']) ? $input_values['proposedSolution'] : ''; ?></textarea>
                </div>
            </div>
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="costBenefitAnalysis" class="form-label" style="color: #1e1e1e; font-weight: 600;">Benefit Analysis (BA) (<span style="color: red;"><span id="costBenefitAnalysisCount" style="color: red;">300</span>characters remaining</span>)</label>
                    <textarea class="form-control" id="costBenefitAnalysis" name="costBenefitAnalysis" rows="4"><?php echo isset($input_values['costBenefitAnalysis']) ? $input_values['costBenefitAnalysis'] : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="flex_side">
        <div class="row">
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3" style="display: flex; flex-direction: row-reverse; gap: 10px; height: unset; width: unset; justify-content: flex-end; align-items: flex-start;">
                    <label for="authorsInterests" class="form-label" style="color: #1e1e1e; font-weight: 600;">I affirm that this idea is entirely my own and has not been plagiarized.</label>
                    <input type="checkbox" class="form-check-input" id="authorsInterests" name="authorsInterests" value = 'on' style="padding: 0px; width: 20px !important; height: 20px; margin: 0px;" <?php echo isset($input_values['authorsInterests']) && $input_values['authorsInterests'] === 'on' ? 'checked' : ''; ?> required>
                </div>
            </div>
            <div class="col-md-4 change_width" style="width: 50%;">
                <div class="mb-3">
                    <label for="uploadFile" class="form-label" style="color: #1e1e1e; font-weight: 600;">Upload the Proposal File (PDF Max 20MB)</label>
                    <input type="file" class="form-control" id="uploadFile" name="uploadFile" accept=".pdf" required>
                    <span style="color: red;">Note: Any document submitted containing your name will be regarded as null</span>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" name="upload_idea" class="btn btn-primary" style="margin-bottom: 25px;" id="upload_idea_time_out">Submit</button>
  </form>
</div>