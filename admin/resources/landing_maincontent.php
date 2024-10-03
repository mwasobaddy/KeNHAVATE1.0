    <?php
    //Incubation (First Stage): Light Blue - Represents new beginnings and ideas.
    //Review (Second Stage): Green - Symbolizes growth and progress.
    //Review Done (Third Stage): Yellow - Represents caution and readiness.
    //Committee (Fourth Stage): Orange - Symbolizes energy and enthusiasm.
    //Board (Fifth Stage): Red - Often associated with authority and decision-making.
    ?>
    <div class="main_content">
        <div class="header_wrapper">
            <div class="header_title">
                <div class="menu_icon"><i class="fa-solid fa-bars"></i></div>
                <div class="menu_content">
                    <span>Research, Innovations & Knowledge Management</span>
                    <h2>Deputy Director's Dashboard</h2>
                </div>
            </div>
            <div class="user_info">
                <img src="./img/bg.png" alt="">
            </div>
        </div>



        <div class="card_container home_cards active" id="card_container_home_cards">
            <div class="date">
                <h3 class="main_title">Data Today</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            <div class="card_wrapper">
                <div class="payment_card light_yellow">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Unallocated Ideas
                            </span>
                            <span class="amount_value" id="unallocatedIdeasCount">
                            </span>
                        </div>
                        <i class="fa-solid fa-head-side-virus icon"></i>
                    </div>
                    <span class="card_details">Total Ideas Submitted: 
                        <span id="totalIdeasCount"></span>
                    </span>
                </div>
                <div class="payment_card light_grey">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Allocated Ideas
                            </span>
                            <span class="amount_value" id="allocatedIdeasCount">
                            </span>
                        </div>
                        <i class="fa-solid fa-users-viewfinder icon"></i>
                    </div>
                    <span class="card_details">Total Ideas Reviewed: 
                        <span id="reviewedIdeasCount"></span>
                    </span>
                </div>
                <div class="payment_card light_yellow">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Ideas at The Committee level
                            </span>
                            <span class="amount_value" id="committeeIdeasCount">
                            </span>
                        </div>
                        <i class="fa-solid fa-head-side-virus icon"></i>
                    </div>
                    <span class="card_details">Total Committee Members: 0
                        <?php
                            /*$stage = "board";
                            $key = 'my-KeNHAsecret-passkey';
                            $encryptedstage = encrypt($stage, $key);

                            $stmt_new_6 = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ?");
                            $stmt_new_6->bind_param("s", $encryptedstage);
                            $stmt_new_6->execute();
                            $result_new_6 = $stmt_new_6->get_result();

                            $totalIdeasSubmitted = $result_new_6->num_rows;
                            
                            if ($totalIdeasSubmitted === 0) {
                                echo "0";
                            } else {
                                echo $totalIdeasSubmitted;
                            }
                    
                            $stmt_new_6->close();*/
                        ?>
                    </span>
                </div>
                <div class="payment_card light_grey">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Ideas at The Board level
                            </span>
                            <span class="amount_value" id="boardAllIdeasCount">
                            </span>
                        </div>
                        <i class="fa-solid fa-users-line icon"></i>
                    </div>
                    <span class="card_details">Rejected at The Board Level: 
                        <span id="boardRejectedIdeasCount"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="tabular_wrapper all_idea_submitted active" style="padding-bottom: 50px;">
            <div class="date">
                <h3 class="main_title">Ideas Submitted</h3>
            </div>
            <div id="idea_details_form">
            </div>
            <div class="table_container" id="FirstIdeaTableDiv" style="width: 100%;">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Day Uploaded</th>
                            <th>Stage</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="FirstIdeaTable">
                    </tbody>
                    <tfoot id="FirstIdeaTableFooter">
                    </tfoot>
                    <div id="FirstIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
        </div>
        
        <div class="container mt-5 new_idea_tab_cards" style="background-color: #fff; border-radius: 10px; padding: 2rem; padding-bottom: 16px; padding-top: 18px;">
            <div class="date">
                <h3 class="main_title">Select to go to:</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            <div class="row" style="row-gap: 20px;">
                <div class="col-lg-4 col-md-6 col-xl-3 light_yellow_card card_new_idea">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Appoint Subject Matter Expert</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-3 light_grey_card card_done_task">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ideas reviewed and committed by Subject Matter Expert</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-3 light_yellow_card card_undone_task">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ideas that are still under-review by Subject Matter Expert</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Tasks</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-3 light_grey_card card_approved_committee">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Escalate & Update Author on Ideas at the Committee</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-3 light_yellow_card  card_approved_board">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Escalate & Update Author on Ideas at the Board</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabular_wrapper new_idea_tab">
            <div class="date">
                <h3 class="main_title">New Ideas Submitted By an Author</h3>
                <div class="alert alert-danger close_new_idea_tab">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <div id="idea_details_form2">
            </div>
            <div class="table_container"  id="SecondIdeaTableDiv" style="width: 100%;">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Day Uploaded</th>
                            <th style="min-width: 165px;">Apppoint Expert</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="SecondIdeaTable">
                    </tbody>
                    <tfoot id="SecondIdeaTableFooter">
                    </tfoot>
                    <div id="SecondIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div class="modal" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-modal="true" style="display: flex; background-color: #000000bd; align-items: center; justify-content: center;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">
                                <i class="fas fa-question-circle"></i> Confirm Action
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeBtn_a">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" id="informationSection">
                        </div>
                        <div class="modal-footer" style="display: flex; justify-content:center;" id="closeBtn_b">
                            <button type="button" class="btn btn-danger">
                                <i class="fas fa-check"></i> ok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabular_wrapper tasks_done">
            <div class="date">
                <h3 class="main_title">Tasks Done by Subject Matter Experts</h3>


                <div class="alert alert-danger close_tasks_done">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <div class="table_container" id="IdeasReviewedTable" style="width: 100%;">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Date Assigned</th>
                            <th>Date Committed</th>
                            <th>Status</th>
                            <th>Comment Type</th>
                            <th>Comment Text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ReviewedIdeaTable">
                    </tbody>
                    <tfoot id="ReviewedIdeaTableFooter">
                    </tfoot>
                    <div id="ReviewedIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="ReviewedIdeaForm">
            </div>
        </div>

        <div id="task_undone" class="tabular_wrapper task_undone">
            <div class="date">
                <h3 class="main_title">Tasks not Done by Subject Matter Expert</h3>
                <div class="alert alert-danger close_task_undone">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <div class="table_container" id="IdeasUnreviewedTable" style="width: 100%;">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Date Assigned</th>
                            <th>Date Committed</th>
                            <th>Status</th>
                            <th>Expert Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="UnreviewedIdeaTable">
                    </tbody>
                    <tfoot id="UnreviewedIdeaTableFooter">
                    </tfoot>
                    <div id="UnreviewedIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="UnreviewedIdeaForm">
            </div>
        </div>

        <div id="details_form_container" class="details_form_container">
            <div class="alert alert-danger close_details_form_container" style="position: relative; left: 94%; top: 7px; z-index: 5;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div id="detailsFormContainer" style="position: relative; top: -70px;">
            </div>
        </div>

        <div class="tabular_wrapper approved_committee">
            <div class="date">
                <h3 class="main_title" id="committeeTab">Committee Tab</h3>
                <div class="alert alert-danger close_approved_committee">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <!-- committee cards pending, rejected and approved-->
            <div id="CommitteeCard">
                <div class="row">
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="escalate">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Escalate Idea to The Committe</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="approval">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Update Idea Author on Committe Approval</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="rejection">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Update Idea Author on Committe Rejection</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="committeePending">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Pending Ideas at The Committe</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="committeeApproved">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Approved Ideas at The Committe</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="committeeRejected">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Rejected Ideas at The Committe</div>
                    </div>
                </div>
            </div>

            <!-- table and form to escalate and notify-->
            <div class="alert alert-danger CloseEscalateIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="EscalateIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="EscalateIdeaTable">
                    </tbody>
                    <tfoot id="EscalateIdeaTableFooter">
                    </tfoot>
                    <div id="EscalateIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="EscalateIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form to reject and notify-->
            <div class="alert alert-danger CloseRejectIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="RejectIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="RejectIdeaTable">
                    </tbody>
                    <tfoot id="RejectIdeaTableFooter">
                    </tfoot>
                    <div id="RejectIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="RejectIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form to approve and notify-->
            <div class="alert alert-danger CloseApproveIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="ApproveIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ApproveIdeaTable">
                    </tbody>
                    <tfoot id="ApproveIdeaTableFooter">
                    </tfoot>
                    <div id="ApproveIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="ApproveIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view pending-->
            <div class="alert alert-danger ClosePendingIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="PendingIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="PendingIdeaTable">
                    </tbody>
                    <tfoot id="PendingIdeaTableFooter">
                    </tfoot>
                    <div id="PendingIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="PendingIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view approved-->
            <div class="alert alert-danger CloseApprovedIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="ApprovedIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ApprovedIdeaTable">
                    </tbody>
                    <tfoot id="ApprovedIdeaTableFooter">
                    </tfoot>
                    <div id="ApprovedIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="ApprovedIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view rejected-->
            <div class="alert alert-danger CloseRejectedIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="RejectedIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="RejectedIdeaTable">
                    </tbody>
                    <tfoot id="RejectedIdeaTableFooter">
                    </tfoot>
                    <div id="RejectedIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="RejectedIdeaForm" style="width: 100%;">
            </div>
            
            <!-- show success and error messages-->
            <div id="PassMessageCommittee" class="table_container" style="width: 100%; background: #0000007d; padding: 55px; top: 0px; position: absolute; left: 0px; height: 100%; display: flex; justify-content: center; align-items: center; display:flex !important; ">
            </div>
        </div>

        <div class="tabular_wrapper approved_board">
            <div class="date">
                <h3 class="main_title" id="boardTab">Board Tab</h3>
                <div class="alert alert-danger close_approved_board">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <!-- board cards pending, rejected and approved-->
            <div id="BoardCard">
                <div class="row">
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="BoardEscalate">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Escalate Idea to The Board</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="BoardApproval">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Update Idea Author on Board Approval</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="BoardRejection">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to Update Idea Author on Board Rejection</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="BoardPending">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Pending Ideas at The Board</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_yellow_card" id="BoardApproved">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Approved Ideas at The Board</div>
                    </div>
                    <div class="col-sm-12 col-md-4 light_grey_card" id="BoardRejected">
                        <div class="card" style="height: 10vh; text-align: center; font-weight: bolder; display: flex; justify-content: center;">Click to view Rejected Ideas at The Board</div>
                    </div>
                </div>
            </div>

            

            <!-- table and form to escalate and notify-->
            <div class="alert alert-danger CloseEscalateBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="EscalateBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="EscalateBoardIdeaTable">
                    </tbody>
                    <tfoot id="EscalateBoardIdeaTableFooter">
                    </tfoot>
                    <div id="EscalateBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="EscalateBoardIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form to reject and notify-->
            <div class="alert alert-danger CloseRejectBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="RejectBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="RejectBoardIdeaTable">
                    </tbody>
                    <tfoot id="RejectBoardIdeaTableFooter">
                    </tfoot>
                    <div id="RejectBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="RejectBoardIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form to approve and notify-->
            <div class="alert alert-danger CloseApproveBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="ApproveBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ApproveBoardIdeaTable">
                    </tbody>
                    <tfoot id="ApproveBoardIdeaTableFooter">
                    </tfoot>
                    <div id="ApproveBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="ApproveBoardIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view pending-->
            <div class="alert alert-danger ClosePendingBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="PendingBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="PendingBoardIdeaTable">
                    </tbody>
                    <tfoot id="PendingBoardIdeaTableFooter">
                    </tfoot>
                    <div id="PendingBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="PendingBoardIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view approved-->
            <div class="alert alert-danger CloseApprovedBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="ApprovedBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="ApprovedBoardIdeaTable">
                    </tbody>
                    <tfoot id="ApprovedBoardIdeaTableFooter">
                    </tfoot>
                    <div id="ApprovedBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="ApprovedBoardIdeaForm" style="width: 100%;">
            </div>

            <!-- table and form view rejected-->
            <div class="alert alert-danger CloseRejectedBoardIdeaTable" style="display: none; position: relative; left: 94%; top: 7px; z-index: 5; position: absolute; left: 94%; top: 20px;">                
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="table_container" style="width: 100%;" id="RejectedBoardIdeaDiv" >
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Date Reviewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="RejectedBoardIdeaTable">
                    </tbody>
                    <tfoot id="RejectedBoardIdeaTableFooter">
                    </tfoot>
                    <div id="RejectedBoardIdeaTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                    </div>
                </table>
            </div>
            <div id="RejectedBoardIdeaForm" style="width: 100%;">
            </div>
            
            <!-- show success and error messages-->
            <div id="PassMessageBoard" class="table_container" style="width: 100%; background: #0000007d; padding: 55px; top: 0px; position: absolute; left: 0px; height: 100%; display: flex; justify-content: center; align-items: center; display:flex !important; ">
            </div>
        </div>
        
        <div class="container mt-5 message_cards" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="date">
                <h3 class="main_title">Message Tab</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            <div class="row" style="row-gap: 20px;">
                <div class="col-lg-4 col-md-6 col-xl-4 light_yellow_card card_message_dg">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Message the Director General</h5>
                            <p class="card-text">All messages sent via this portal will reach the Director's email.</p>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-4 light_grey_card card_message_committee">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Message Committee Member(s)</h5>
                            <p class="card-text">You can choose to message an Individual / the Whole committee.</p>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Ideas</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-xl-4 light_yellow_card card_message_experts">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Message Subject Matter Expert(s)</h5>
                            <p class="card-text">You can choose to message an Individual / all subject matter expert(s).</p>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Tasks</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container mt-5 message_dg" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="alert alert-danger close_message_dg" style="position: absolute; right: 11px; top: 11px;">
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>    
            <h2>Send Message to the Director General</h2>
            <form action="" method="post">
                <div class="sender_reciever" style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 35px;">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient:</label>
                        <input type="text" class="form-control" id="recipient" value="John Doe" readonly style="background-color: gainsboro;">
                    </div>
                    <div class="mb-3">
                        <label for="sender" class="form-label">From:</label>
                        <input type="text" class="form-control" id="sender" value="Jane Doe" readonly style="background-color: gainsboro;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea class="form-control" id="message" rows="5" required style="background-color: gainsboro;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
        
        <div class="container mt-5 message_committee" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="alert alert-danger close_message_committee">
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>    
            <h2>Send Message to the Committee Member(s)</h2>
            <form action="" method="post">
                <div class="sender_reciever" style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 35px;">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient:</label>
                        <input type="text" class="form-control" id="recipient" value="John Doe" readonly style="background-color: gainsboro;">
                    </div>
                    <div class="mb-3">
                        <label for="sender" class="form-label">From:</label>
                        <input type="text" class="form-control" id="sender" value="Jane Doe" readonly style="background-color: gainsboro;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea class="form-control" id="message" rows="5" required style="background-color: gainsboro;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
        
        <div class="container mt-5 message_expert" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="alert alert-danger close_message_expert">
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>    
            <h2>Send Message to the Subject Matter Expert(s)</h2>
            <form action="" method="post">
                <div class="sender_reciever" style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 35px;">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient:</label>
                        <select class="form-select" id="recipient" style="background-color: gainsboro;">
                            <option value="">Select Recipient</option>
                            <option value="All">All</option>
                            <option value="John Doe">John Doe</option>
                            <option value="Jane Smith">Jane Smith</option>
                            <option value="Alice Johnson">Alice Johnson</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sender" class="form-label">From:</label>
                        <input type="text" class="form-control" id="sender" value="Jane Doe" readonly style="background-color: gainsboro;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea class="form-control" id="message" rows="5" required style="background-color: gainsboro;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
        
        <div class="container mt-5 committee_cards" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="date">
                <h3 class="main_title">Users Tab</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            <div class="row" style="row-gap: 20px;">
                <div class="col-lg-6 col-md-6 col-xl-6 light_yellow_card card_view_committee_members">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">View & Manage Members of the Committee</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Tasks</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xl-6 light_grey_card card_create_new_committee">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Create & Manage Members of the Challenge Committee</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Tasks</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabular_wrapper committee_members">
            <div class="date">
                <h3 class="main_title">Committee Members</h3>
                <div class="alert alert-danger close_committee_members">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <div class="listedCommittees" id="listedCommittees">
                <div>
                    <div>
                        <div id="listedCommitteesHeader">
                        </div>
                        <div class="row" style="row-gap: 20px;" id="listedCommitteesBody">
                        </div>
                        <div class="clickedCommitteeTableDiv_1">
                            <div class="close_clickedCommitteeTableDiv_1"></div>
                            <div class="filter_search_deactivate">
                                <div style="margin-bottom: 15px;">
                                    <div class="search" style="width: 100%;">
                                        <div class="row justify-content-center" style="align-items: center;">
                                            <div class="col-sm-12 col-md-8">
                                                <div class="search-bar" style="display: flex; justify-content: center; align-items: center; gap: 7px; border: 1px solid black; border-radius: 15px;">
                                                    <input type="text" class="" placeholder="Search..." style="border: none; width: 90%; height: 20px; padding: 13px; margin: 5px; border-right: 1px solid black;">
                                                    <i class="fas fa-search search-icon" style="margin-right: 13px;"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4" style="padding-top: 10px; padding-bottom: 10px;" id="activate_or_deactivate"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table_container" id="committeeTableView" style="width: 100%;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Rank</th>
                                            <th>Gender</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="committeeTableViewBody"></tbody>
                                    <tfoot id="committeeTableViewBodyFooter"></tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="formEditDetails"></div>
                    </div>
                </div>
                <div id="paginationControlGrid"></div>
            </div>
            <div class="clicked_committee_table container mt-5" id="clickedCommitteeTableDiv">
                <h2 class="text-center mb-4">"Committee Name"Committee Members</h2>
                <div class="table-responsive">
                    <table class="table_container">
                        <thead>
                            <div id="clickedTableHeadSection" style="text-align: -webkit-right; background-color: snow; display: flex; justify-content: space-between; margin: 0px 0px 10px;">
                            </div>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Rank</th>
                                <th>Gender</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="clickedCommitteeTable">
                        </tbody>
                        <tfoot id="clickedCommitteeTableFooter">
                        </tfoot>
                        <div id="clickedCommitteeTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                        </div>
                    </table>
                </div>
            </div>
        </div>

        <div class="container mt-5 tabular_wrapper manage_committee">
            <div class="date">
                <h3 class="main_title">Choose to:</h3>
                <div class="alert alert-danger close_manage_committee">                
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
            <div class="table_container committee_cards_2" style="width: 100%;">
                <div class="row" style="justify-content: center;">
                    <div class="col-lg-6 col-md-6 col-xl-6 light_yellow_card create_new_committee_card">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" style="text-align: center;">Create a New Committee</h5>
                                <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">Select</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table_container" style="width: 100%;">
                <div class="create_new_committee_form container mt-5" style="width: 100%; max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                    <form id="createCommitteeForm">
                        <div style="text-align: center; justify-content: space-between; display: flex;">
                            <h2 style="width: 90%;">Create Committee</h2>
                            <div class="alert alert-danger close_create_committee">                
                                <button type="button" class="btn-close" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tableName" class="form-label">Committe Name</label>
                            <input type="text" class="form-control" id="committeName" name="committeName" placeholder="eg. Innovation_Managment" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiryDate" name="expiryDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="chairperson" class="form-label">Chairperson</label>
                            <input type="text" class="form-control" id="chairperson" name="chairperson" placeholder="eg. Eng. John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label for="secretary" class="form-label">Secretary</label>
                            <input type="text" class="form-control" id="secretary" name="secretary" placeholder="eg. Eng. Jane Doe" required>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                            <button type="submit" class="btn btn-primary" id="createCommitteeSubmit">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="create_new_committee_table container mt-5" id="newCommitteeTableDiv">
                    <h2 class="text-center mb-4">Committee Members</h2>
                    <div class="table-responsive">
                        <table class="table_container">
                            <thead>
                                <div id="tableHeadSection" style="text-align: -webkit-right; background-color: snow; display: flex; justify-content: space-between; margin: 0px 0px 10px;">
                                </div>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Rank</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="NewCommitteeTable">
                            </tbody>
                            <tfoot id="NewCommitteeTableFooter">
                            </tfoot>
                            <div id="NewCommitteeTablePagination" style="position: absolute; bottom: 0px; width: 100%; padding-bottom: 16px;">
                            </div>
                        </table>
                    </div>
                </div>

                
                <div class="create_new_committee_member_form_1 container mt-5" id="addMemberForm_1_Div" style="width: 100%; max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                    <form id="addMemberForm_1">
                        <h2>Add Committee Members</h2>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="eg. Eng. John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Committee Position:</label>
                            <input type="text" class="form-control" id="position" name="position" placeholder="eg. Chairperson" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Tel Number:</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="eg. 0712345678 / 011234567" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Work Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="eg. johndoe@kenha.co.ke" required>
                        </div>
                        <div class="mb-3">
                            <label for="rank" class="form-label">Organization Position:</label>
                            <input type="text" class="form-control" id="rank" name="rank" placeholder="eg. Deputy Director RI&KM" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" selected disabled>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                            <button type="submit" class="btn btn-primary" id="submitNewMemeber_1">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="container mt-5 board_cards" style="background-color: #fff; border-radius: 10px; padding: 2rem;">
            <div class="date">
                <h3 class="main_title">Challenges Tab</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            
            
            <div class="row row_challenges active" style="row-gap: 20px;">
                <div class="col-lg-6 col-md-6 col-xl-6 light_yellow_card post_challenge">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Post a New Challenge</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">Post Challenge</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xl-6 light_grey_card view_challenge">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Check Posted and Replied Challenges</h5>
                            <a class="btn btn-warning btn-animation animate__animated animate__rubberBand" style="background-color: #d8d801ed; align-self: center; position: relative; width: fit-content;">View Challenges</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form_post_challenge">
                <!--form for uploading challenge-->
                <div class="alert alert-danger close_challenges" style="position: absolute; right: 0px;">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>    
                <h2>Create a New Challenge</h2>
                <form id="challengeForm">
                    <div class="display_input_flex_row" style="display: flex; gap: 10%; ">
                        <div class="display_input_flex_column" style="display: flex; flex-direction: column; flex-wrap: wrap; gap: 10%; width: 45%;">
                            <div class="mb-3"style="width: 100%;">
                                <label for="title" class="form-label">Title:</label>
                                <input type="text" name="title" class="form-control" id="challenge_title" style="background-color: gainsboro;" placeholder="Title of the challenge (max 255 words)" required>
                            </div>
                            <div class="mb-3"style="width: 100%;">
                                <label for="datetime">Select Deadline (Date & Time)</label>
                                <input type="datetime-local" name="date" class="form-control" id="challenge_datetime" style="background-color: gainsboro;" placeholder="Select Date and Time" required>
                            </div>
                        </div>
                        <div class="mb-3" style="width: 45%;">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" id="challenge_description" rows="4" style="background-color: gainsboro;" placeholder="Brief description of what the challenge is about (max 255 words)" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pdfFile" class="form-label" style="color: red;">Upload a PDF file containing more details about the challenge e.g guidelines, images or what is expected from the user</label>
                        <input type="file" name="upload_challenge" id="upload_challenge" class="form-control" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" style="width: auto; background-color: gainsboro;">
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>

                
            <!--table for viewing challenge-->
            <div class="tabular_wrapper challenges_uploaded" style="flex-direction: column;" id="challenges_uploaded">
                <div class="date">
                    <h3 class="main_title">Challenges Uploaded by Department Staff</h3>
                    <div class="alert alert-danger close_challenges">
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                </div>
                <div class="table_container" style="width: 100%;">
                    <table id="FullChallengesTableResponse">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Author</th>
                                <th>Challenge Ref</th>
                                <th>Challenge Title</th>
                                <th>Challenge Description</th>
                                <th>Deadline</th>
                                <th>Attempts</th>
                                <th>Status</th>
                                <th>Day Uploaded</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="challengesTableResponse">
                        </tbody>
                        <tfoot id="challengesTableResponseFooter">
                        </tfoot>
                    </table>
                </div>
            </div>

                
            <!--table for viewing challenge-->
            <div class="tabular_wrapper" style="flex-direction: column;" id="SecondaryChallengesTableResponse">
                <div class="date">
                    <h3 class="main_title" id="challenge_number_title"></h3>
                    <div class="alert alert-danger" id="close_response_table" style="position: absolute; right: 0px; margin-right: 15px;">
                        <label>&#x2190;</label>
                    </div>
                </div>
                <div class="table_container" style="width: 100%;">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Challenge Description</th>
                                <th>Challenge Motivation</th>
                                <th>Challenge Cost Estimate</th>
                                <th>Upload Date</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="MoreChallengesTableResponse">
                        </tbody>
                        <tfoot id="MoreChallengesTableResponseFooter">
                        </tfoot>
                    </table>
                </div>
            </div>

            

            <div class="tabular_wrapper" style="flex-direction: column;" id="ViewResponseForm">
                <!--form for uploading challenge-->
                <div class="alert alert-danger" id="CloseViewResponseForm" style="position: absolute; right: 0px; margin-right: 15px;">
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div id="ResponseFormContent"></div>
                <form id="ResponseForm">
                </form>
            </div>

                                
            <!--table for viewing challenge1-->
            <div class="tabular_wrapper challenges_responses1" style="display: none;">
                <div class="date">
                    <h3 class="main_title">Respose to Challenge No.<span id="challenge_uuid_placeholder"></span></h3>
                    <div class="alert alert-danger close_challenges_responses1">
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                </div>
                <div class="table_container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>replied_challenges</th>
                                <th>Challenge Ref</th>
                                <th>Challenge Title</th>
                                <th>Challenge Description</th>
                                <th>Deadline</th>
                                <th>Day Uploaded</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $key = 'my-KeNHAsecret-passkey';

                                $stmt_7 = $con->prepare("SELECT * FROM posted_challenges");
                                $stmt_7->execute();
                                $result_7 = $stmt_7->get_result();

                                $counter = 1;
                                $_SESSION['total_count9'] = 0;

                                if ($result_7->num_rows === 0) {
                                    // No uploaded challenges found, display "Not found" message.
                                    echo "<tr>";
                                    echo "<td colspan='8' style='text-align: center; font-size: 16px; font-weight: bolder;'>No uploaded challenges!!</td>";
                                    echo "</tr>";
                                } else {
                                    while ($row_7 = $result_7->fetch_assoc()) {
                                        $db_en_staff_uuid = $row_7['author_uuid'];

                                        $stmt_8 = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.staff_uuid = ?");
                                        $stmt_8->bind_param("s", $db_en_staff_uuid);
                                        $stmt_8->execute();
                                        $result_8 = $stmt_8->get_result();

                                        if ($result_8->num_rows === 0) {
                                            // No staff found with this UUID, display "No name found."
                                            $db_en_staff_fname = "No name found";
                                            $db_en_staff_othernames = "";
                                        } else {
                                            $row_8 = $result_8->fetch_assoc();

                                            // Staff name
                                            $db_en_staff_fname = decrypt($row_8['first_name'], $key);
                                            $db_en_staff_othernames = decrypt($row_8['other_names'], $key);
                                        }

                                        echo "<tr>";
                                        echo "<td>" . $counter . "</td>";
                                        echo "<td>" . $db_en_staff_fname . " " . $db_en_staff_othernames . "</td>";
                                        echo "<td>" . decrypt($row_7['challenge_uuid'], $key) . "</td>";
                                        echo "<td>" . decrypt($row_7['title'], $key) . "</td>";
                                        echo "<td>" . decrypt($row_7['description'], $key) . "</td>";
                                        echo "<td>" . decryptData($row_7['deadline'], $key) . "</td>";
                                        echo "<td>" . $row_7['time_stamp'] . "</td>";

                                        echo "<td>";
                                            echo "<button class='view_btn' data-challenge-uuid='" . $row_7['challenge_uuid'] . "' class='btn btn-primary'>View&nbsp;Responses</button>";
                                        echo "</td>";

                                        $counter++;
                                        $stmt_8->close();
                                    }

                                    $_SESSION['total_count9'] = $counter - 1;
                                }

                                $stmt_7->close();
                            ?>



                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8">Total ksh. 5555</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>