$(document).ready(function () {


    
    $("#SecondaryChallengesTableResponse").hide();
    $("#ViewResponseForm").hide();
    $("#idea_details_form").hide();
    $("#idea_details_form2").hide();
    $("#confirmationModal").hide();
    $("#CommitteeCard").show();
    $("#EscalateIdeaDiv").hide();
    $("#PassMessageCommittee").hide();
    $("#RejectIdeaDiv").hide();
    $("#ApproveIdeaDiv").hide();
    $("#PendingIdeaDiv").hide();
    $("#ApprovedIdeaDiv").hide();
    $("#RejectedIdeaDiv").hide();
    $("#BoardCard").show();
    $("#EscalateBoardIdeaDiv").hide();
    $("#PassMessageBoard").hide();
    $("#RejectBoardIdeaDiv").hide();
    $("#ApproveBoardIdeaDiv").hide();
    $("#PendingBoardIdeaDiv").hide();
    $("#ApprovedBoardIdeaDiv").hide();
    $("#RejectedBoardIdeaDiv").hide();
    $("#newCommitteeTableDiv").hide();
    $("#addMemberForm_1_Div").hide();
    $(".manage_committee").hide();
    $(".create_new_committee_form ").hide();
    $("#clickedCommitteeTableDiv").hide();
    $(".clickedCommitteeTableDiv_1").hide();

    $('#SecondaryChallengesTableResponse').on('click', '#close_response_table', function () {
        $("#SecondaryChallengesTableResponse").hide();
        $("#challenges_uploaded").show();
    });

    $('#ViewResponseForm').on('click', '#CloseViewResponseForm', function () {
        $("#SecondaryChallengesTableResponse").show();
        $("#ViewResponseForm").hide();
    });
    
    $('.view_details').click(function () {
        var Upload_id = $(this).data('upload-id');

        // Make an AJAX request to fetch details for the selected row
        $.ajax({
            type: 'POST',
            url: '/KeNHAVATE/view-table-details', // Replace with the actual URL of your PHP script
            data: { upload_id: Upload_id }, // Send the row identifier to your PHP script
            success: function (response) {
                // Create a dynamic form with the details received from the server
                $('#details_form_container').addClass('active');
                $('#task_undone').removeClass('active');
                $('#detailsFormContainer').html(response);
            },
            error: function (errorResponse) {
                // Handle errors, e.g., show an error message
                console.error(errorResponse);
            }
        });
    });

    $('#challengeForm').on('submit', function (event) {
        event.preventDefault();
        // Prevent the default form submission
    
        var formData = new FormData(this);
        formData.append('action', 'post_challenge');
    
        $.ajax({
            type: 'POST',
            url: '/KeNHAVATE/post-challenge',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                UpdateTableChallenges();
                alert(response);
                $('#challengeForm')[0].reset(); // Reset the form
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('An error occurred while creating the challenge.');
            }
        });
    });

    
    function get_Rows(callback) {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=GetRows',
            type: 'GET',
            success: function (data) {
                if (data.message === 'Success') {
                    var rowsData = data.data;
                    //console.table(rowsData);
                    if (typeof callback === 'function') {
                        callback(rowsData);
                    }
                } else {
                    console.log("Error: " + data.message);
                    if (typeof callback === 'function') {
                        callback([]);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
                if (typeof callback === 'function') {
                    callback([]);
                }
            }
        });
    }



    var existingChallenges = [];

    function UpdateTableChallenges() {
        get_Rows(function (rowsData) {            
            $.ajax({
                url: '/KeNHAVATE/fetch-responses?action=UpdateTableChallenges',
                type: 'GET',
                success: function (data) {
                    if (data.length === 0) {
                        $('#challengesTableResponse').empty().append('<tr><td colspan="10" style="text-align: center; font-weight: bolder;">No Challenges Posted Yet</td></tr>');
                        $('#challengesTableResponseFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                    } 
                    else {
                        $('#challengesTableResponse').empty();
                        var counter = 1;

                        // Iterate through the data
                        data.forEach(function (idea) {
                            // Remove the idea from the array if it has been deleted
                            var index = existingChallenges.indexOf(idea.challenge_uuid);
                            if (index > -1) {
                                existingChallenges.splice(index, 1);
                            }

                            // Find the corresponding row data from the array obtained in get_Rows
                            var rowData = rowsData.find(function (row) {
                                return row.challenge_uuid.trim().toLowerCase() === idea.challenge_uuid.trim().toLowerCase();
                            });
                    
                            // If the row data is found, use the count; otherwise, set count to 0
                            var count = rowData ? rowData.count : 0;
        
                            // Generate a new row and append it to the table
                            var newRow =
                                '<tr>' +
                                    '<td>' + counter + '</td>' +
                                    '<td>' + idea.first_name + ' ' + idea.other_names + '</td>' +
                                    '<td>' + idea.challenge_uuid + '</td>' +
                                    '<td>' + idea.title + '</td>' +
                                    '<td>' + idea.description + '</td>' +
                                    '<td>' + idea.deadline + '</td>' +
                                    '<td style="text-align: center;">' + count + '</td>' +
                                    '<td><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                    '<td>' + idea.day_uploaded + '</td>' +
                                    '<td><div data-idea-uuid="' + idea.challenge_uuid + '" id = "view_all_response"  style = "background-color: #6060ec; color: white; padding: 9px 5px; border-radius: 8px; text-align: center;">view attempts</div></td>' +
                                '</tr>';
                            $('#challengesTableResponse').append(newRow);
        
                            // Add the idea UUID to the array of existing idea UUIDs
                            existingChallenges.push(idea.challenge_uuid);
        
                            counter++;
                        });

                        var counter = counter - 1;

                        var footerCounter =
                            '<tr>' +
                                '<td colspan="10">Total ' + counter + '</td>' +
                            '</tr>';

                        $('#challengesTableResponseFooter').empty().append(footerCounter);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // Log the full server response
                    console.error(error); // Log the error message
                }
            });
        });
    }
    
    $('#challengesTableResponse').on('click', '#view_all_response', function () {
        $("#SecondaryChallengesTableResponse").show();
        $("#challenges_uploaded").hide();
        $("#ViewResponseForm").hide();
        var challengeId = $(this).data('idea-uuid');

        $('#challenge_number_title').empty().append('Attempts to Challenge NO.'+ challengeId +'');

        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateTableChallengesViewMore&challengeId=' + encodeURIComponent(challengeId),
            type: 'GET',
            success: function (data) {
                console.table(data);
                if (!Array.isArray(data) || data.length === 0) {
                    $('#MoreChallengesTableResponse').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No Attempts Made To Challenge No. ' + challengeId + '</td></tr>');
                    $('#MoreChallengesTableResponseFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
                else {
                    $('#MoreChallengesTableResponse').empty();
                    var counter = 1;
                
                    // Iterate through the data
                    data.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingChallenges.indexOf(idea.challenge_uuid);
                        if (index > -1) {
                            existingChallenges.splice(index, 1);
                        }
                
                        // Generate a new row and append it to the table body
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.desc_solution + '</td>' +
                                '<td>' + idea.desc_motivation + '</td>' +
                                '<td>' + idea.desc_cost_estimate + '</td>' +
                                '<td>' + idea.time_uploaded + '</td>' +
                                '<td>' + idea.email + '</td>' +
                                '<td><div data-idea-uuid="' + idea.upload_id + '" style = "background-color: #6060ec; color: white; padding: 9px 5px; border-radius: 8px; text-align: center;" id = "view_challenge_details">view details</div></td>' +
                            '</tr>';
                        $('#MoreChallengesTableResponse').append(newRow);
                
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingChallenges.push(idea.challenge_uuid);
                
                        counter++;
                    });
                
                    // Calculate the total counter
                    var totalCounter = counter - 1;
                
                    // Generate the footer row and append it to the table footer
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="6">Total ' + totalCounter + '</td>' +
                        '</tr>';
                    $('#MoreChallengesTableResponseFooter').empty().append(footerCounter);
                }
                
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('#MoreChallengesTableResponse').on('click', '#view_challenge_details', function () {
        $("#SecondaryChallengesTableResponse").hide();
        $("#challenges_uploaded").hide();
        $("#ViewResponseForm").show();
        var upload_id = $(this).data('idea-uuid');
        var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormChallengesViewMore&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ResponseForm').empty();
                    $('#ResponseFormContent').empty();    
                    // Generate a new row and append it to the table body
                    var newContent = 
                        '<h5 style="text-align: center;">Challenge Title: '+ idea.challenge_title +'</h5>' +
                        '<h5 style="text-align: center;">Challenge Description</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.challenge_desc +'</div>';
                    var newRow =
                        '<h5 style="text-align: center;">Attempt Information</h5>' +
                        '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Solution</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.desc_solution + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Motivation</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.desc_motivation + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Estimate</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.desc_cost_estimate + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Upload Date</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.time_uploaded + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: center;">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#ResponseForm').append(newRow);
                    $('#ResponseFormContent').append(newContent);
                } else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });





    var existingIdeas = [];
    var currentPage = 1;
    var itemsPerPage = 10;
    
    function FirstIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFirstIdeaTable&page=' + currentPage + '&itemsPerPage=' + itemsPerPage,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;
                    // Clear the existing data in the table
                    $('#FirstIdeaTable').empty();
            
                    var counter = (currentPage - 1) * itemsPerPage + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                            '<td>' + counter + '</td>' +
                            '<td>' + idea.problem_statement + '</td>' +
                            '<td>' + idea.innovation_area + '</td>' +
                            '<td>' + idea.day_user_uploaded + '</td>' +
                            '<td style="text-align: center;"><div class = "' + idea.stage + '">' + idea.stage + '</div></td>' +
                            '<td style="text-align: center;"><div class = "' + idea.stage + '">' + idea.status + '</div></td>' +
                            '<td><div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="FirstIdeaTable_view_more">view&nbsp;details</div</td>' +
                            '</tr>';
                        $('#FirstIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#FirstIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControls(data.totalPages);
                    
                }
                else{
                    $('#FirstIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#FirstIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(status); // Log the error message
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControls(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePage(-1)" ' + (currentPage === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPage + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePage(1)" ' + (currentPage === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#FirstIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePage = function(direction) {
        currentPage += direction;
        FirstIdeaTable(); // Call the function that makes the AJAX request
    };

    $('#FirstIdeaTableDiv').on('click', '#FirstIdeaTable_view_more', function () {
        $("#card_container_home_cards").hide();
        $("#idea_details_form").show();
        $("#FirstIdeaTableDiv").hide();
        var idea_uuid = $(this).data('idea-uuid');
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormIdeasViewMore&idea_uuid=' + encodeURIComponent(idea_uuid),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#idea_details_form').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Expert Appointed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.expert_uuid + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded Idea PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#idea_details_form').append(newRow);
                } else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });   
    
    $('#idea_details_form').on('click', '#FormBackButton', function () {
        $("#card_container_home_cards").show();
        $("#idea_details_form").hide();
        $("#FirstIdeaTableDiv").show();
    });





    var existingIdeasTable2 = [];
    var currentPageTable2 = 1;
    var itemsPerPageTable2 = 10;
    
    function SecondIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateSecondIdeaTable&page2=' + currentPageTable2 + '&itemsPerPage2=' + itemsPerPageTable2,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;
                    // Clear the existing data in the table
                    $('#SecondIdeaTable').empty();
            
                    var counter = (currentPageTable2 - 1) * itemsPerPageTable2 + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingIdeasTable2.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingIdeasTable2.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                            '<td>' + counter + '</td>' +
                            '<td>' + idea.problem_statement + '</td>' +
                            '<td>' + idea.innovation_area + '</td>' +
                            '<td>' + idea.day_user_uploaded + '</td>' +
                            '<td>unassigned</td>' +
                            '<td><div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="SecondIdeaTable_view_more">view&nbsp;details</div</td>' +
                            '</tr>';
                        $('#SecondIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingIdeasTable2.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#SecondIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsTable2(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#SecondIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#SecondIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsTable2(totalPages) {
        var paginationControlsTable2 =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageTable2(-1)" ' + (currentPageTable2 === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageTable2 + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageTable2(1)" ' + (currentPageTable2 === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#SecondIdeaTablePagination').empty().append(paginationControlsTable2);
    }
    
    window.changePageTable2 = function(direction) {
        currentPageTable2 += direction;
        SecondIdeaTable(); // Call the function that makes the AJAX request
    };
    



    
    var adminDetailsArray = [];

    function GetAllAdminDetails() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=GetAllAdminDetails',
            type: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    //$('#challengesTableResponse').empty().append('<tr><td colspan="10" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    //$('#challengesTableResponseFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                } 
                else {
                    $('#challengesTableResponse').empty();
                        
                    adminDetailsArray = [];
                    data.forEach(function (AdminData) {
                        adminDetailsArray.push({
                            AdminKeNHAEmail: AdminData.KeNHA_email,
                            AdminPersonalEmail: AdminData.personal_email,
                            AdminDirectorate: AdminData.directorate,
                            AdminStaffUuid: AdminData.staff_uuid,
                            AdminFirstName: AdminData.first_name,
                            AdminOtherNames: AdminData.other_names
                        });
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    $('#SecondIdeaTableDiv').on('click', '#SecondIdeaTable_view_more', function () {
        GetAllAdminDetails();
        $('#idea_details_form2').empty();
        $("#idea_details_form2").show();
        $("#SecondIdeaTableDiv").hide();
        $(".close_new_idea_tab").hide();
        var idea_uuid = $(this).data('idea-uuid');
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormIdeasViewMore&idea_uuid=' + encodeURIComponent(idea_uuid),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#idea_details_form').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;" id="ExpertSelect">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#idea_details_form2').append(newRow);


                    if (idea.expert_uuid === 'unassigned') {
                        let dropdownOptions = null;
                        if (dropdownOptions === null) {
                    
                            // Clear existing dropdown options
                            $('#ExpertSelect select').empty();
                    
                            const dropdownOptions = adminDetailsArray.map(function (admin) {
                                return '<option value="'+ admin.AdminStaffUuid +'">'+ admin.AdminFirstName +' '+ admin.AdminOtherNames +' ('+ admin.AdminDirectorate +')</option>' +
                                    '<div role="separator" class="dropdown-divider"></div>';
                            }).join('');
                    
                            // Append new dropdown options
                            $('#ExpertSelect select').append('<option value="" disabled="" selected="">Select expert</option>' + dropdownOptions);
                    
                            const newExpert =
                                '<form id="submitSelectedExpert">' +
                                    '<h5>Appoint Expert</h5>' +
                                    '<div style="margin-bottom: .5rem; display: flex; justify-content: center; column-gap: 15px;" id="ExpertSelect">' +
                                        '<select class="form-select" required="" autocompleted="">' +
                                            '<option value="" disabled="" selected="">Select expert</option>' +
                                            dropdownOptions +
                                        '</select>' +
                                        '<a class="btn btn-primary submit_expert" id="submit_expert" style="width: fit-content;">Appoint</a>' +
                                    '</div>' +
                                '</form>';
                    
                            $('#ExpertSelect').append(newExpert);
                    
                            // Attach event handler after appending the new dropdown
                            $('#ExpertSelect #submit_expert').on('click', function () {
                                // Extract the selected email from the dropdown
                                var staff_uuid = $('#ExpertSelect select').val();

                                if (staff_uuid !== null) {
                                    // Disable the button and change text
                                    $(this).prop('disabled', true).text('Appointing...').css('background-color', '#bed8ff');
                                    // Perform a separate AJAX call for submitting the selected email
                                    $.ajax({
                                        url: '/KeNHAVATE/fetch-responses?action=AppointExpert&staff_uuid=' + encodeURIComponent(staff_uuid) + '&idea_uuid=' + encodeURIComponent(idea_uuid),
                                        type: 'GET',
                                        success: function (response) {
                                            if (response.length > 0) {
                                                $("#confirmationModal").show();
                                                var newData = response[0]; 
                                                startUpdate();
                        
                                                const newExpert =
                                                '<div class="col-md-6">' +
                                                    '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                                        '<h5 style="text-align: center;">Appointed Expert</h5>' +
                                                        '<div>Name: ' + newData.first_name +' ' + newData.other_names + '</div>' +
                                                        '<div style="margin-bottom: .5rem;">Email: ' + newData.KeNHA_email +'</div>' +
                                                    '</div>' +
                                                '</div>';
                        
                                                $('#ExpertSelect').empty();
                                                $('#ExpertSelect').append(newExpert);

                                                const info =
                                                '<p style="font-size: 17px;">You have appointed ' + newData.first_name +' ' + newData.other_names + ' as the subject matter expert and emails have been sent to notify the idea author, subject matter expert and in your mail box</p>';
                        
                                                $('#informationSection').empty();
                                                $('#informationSection').append(info);
                                            }
                                        },
                                        error: function (xhr, status, error) {
                                            console.error('Error:', error);
                                        }
                                    });
                                } else {
                                    $('#ExpertSelect select').css('border', '2px solid red');
                                    alert("choose a subject expert on the dropdown before clicking appoint");
                                }
                            });
                        }
                    }
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });   
    
    $('#idea_details_form2').on('click', '#FormBackButton', function () {
        startUpdate();
        $("#idea_details_form2").hide();
        $("#SecondIdeaTableDiv").show();
        $(".close_new_idea_tab").show();
    });

    
    $('#closeBtn_a').click(function () {
        $("#confirmationModal").hide();
    });

    $('#closeBtn_b').click(function () {
        $("#confirmationModal").hide();
    });

    //DD cards
    function CardUnallocatedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardUnallocatedIdeas',
            type: 'GET',
            success: function (data) {
                $('#unallocatedIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardTotalIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardTotalIdeas',
            type: 'GET',
            success: function (data) {
                $('#totalIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardAllocatedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardAllocatedIdeas',
            type: 'GET',
            success: function (data) {
                $('#allocatedIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardReviewedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardReviewedIdeas',
            type: 'GET',
            success: function (data) {
                $('#reviewedIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardCommiteeIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardCommitteeIdeas',
            type: 'GET',
            success: function (data) {
                $('#committeeIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardBoardAllIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCardBoardAllIdeas',
            type: 'GET',
            success: function (data) {
                $('#boardAllIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }

    function CardBoardRejectedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=boardRejectedIdeasCard',
            type: 'GET',
            success: function (data) {
                $('#boardRejectedIdeasCount').text(data[0].numRows);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }





    var existingReviewedIdeas = [];
    var currentPageReviewedIdeas = 1;
    var itemsPerPageReviewedIdeas = 10;
    
    function ReviewedIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateReviewedIdeaTable&page=' + currentPageReviewedIdeas + '&itemsPerPage=' + itemsPerPageReviewedIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
    
                // Check if data.items exists and is not empty
                if (Array.isArray(data.items) && data.items.length > 0) {
                    var dataArray = data.items;
    
                    // Clear the existing data in the table
                    $('#ReviewedIdeaTable').empty();
    
                    var counter = (currentPageReviewedIdeas - 1) * itemsPerPageReviewedIdeas + 1;
    
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingReviewedIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingReviewedIdeas.splice(index, 1);
                        }
    
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.day_expert_appointed + '</td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td><div class="' + idea.comment_type + '">' + idea.comment_type + '</div></td>' +
                                '<td>' + idea.comment_text + '</td>' +
                                '<td><div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="ReviewedIdeaTable_view_more">view&nbsp;details</div</td>' +
                            '</tr>';
                        $('#ReviewedIdeaTable').append(newRow);
    
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingReviewedIdeas.push(idea.idea_uuid);
    
                        counter++;
                    });
    
                    var counter = counter - 1;
    
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of ' + data.totalRows + '</td>' +
                        '</tr>';
    
                    $('#ReviewedIdeaTableFooter').empty().append(footerCounter);
    
                    // Add pagination controls
                    addPaginationControlsReviewedIdeaTable(data.totalPages);
                } else {
                    // Handle case where no data items are returned
                    $('#ReviewedIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#ReviewedIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsReviewedIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageReviewedIdeaTable(-1)" ' + (currentPageReviewedIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageReviewedIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageReviewedIdeaTable(1)" ' + (currentPageReviewedIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#ReviewedIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageReviewedIdeaTable = function(direction) {
        currentPageReviewedIdeas += direction;
        ReviewedIdeaTable(); // Call the function that makes the AJAX request
    };

    $('#ReviewedIdeaTable').on('click', '#ReviewedIdeaTable_view_more', function () {
        $("#IdeasReviewedTable").hide();
        $("#ReviewedIdeaForm").show();
        $(".close_tasks_done").hide();
        var upload_id = $(this).data('idea-uuid');
        var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormReviewedIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ReviewedIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: center;">' +
                                    '<h5>By clicking this, you confirm that the idea is viable to the committee level</h5>' +
                                    '<a class="btn btn-warning" style="margin-top: 20px; width: fit-content;">>Escalate To The Committee</a>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#ReviewedIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });  
    
    $('#ReviewedIdeaForm').on('click', '#FormBackButton', function () {
        $("#ReviewedIdeaForm").hide();
        $("#IdeasReviewedTable").show();
        $(".close_tasks_done").show();
    });

    

    var existingUnreviewedIdeas = [];
    var currentPageUnreviewedIdeas = 1;
    var itemsPerPageUnreviewedIdeas = 10;
    
    function UnreviewedIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateUnreviewedIdeaTable&page=' + currentPageUnreviewedIdeas + '&itemsPerPage=' + itemsPerPageUnreviewedIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#UnreviewedIdeaTable').empty();
            
                    var counter = (currentPageUnreviewedIdeas - 1) * itemsPerPageUnreviewedIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingUnreviewedIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingUnreviewedIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.day_expert_appointed + '</td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.first_name + ' ' + idea.other_names + '</td>' +
                                '<td><div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="UnreviewedIdeaTable_view_more">view&nbsp;details</div</td>' +
                            '</tr>';
                        $('#UnreviewedIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingUnreviewedIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#UnreviewedIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsUnreviewedIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#UnreviewedIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#UnreviewedIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsUnreviewedIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageUnreviewedIdeaTable(-1)" ' + (currentPageUnreviewedIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageUnreviewedIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageUnreviewedIdeaTable(1)" ' + (currentPageUnreviewedIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#UnreviewedIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageUnreviewedIdeaTable = function(direction) {
        currentPageUnreviewedIdeas += direction;
        UnreviewedIdeaTable(); // Call the function that makes the AJAX request
    };

    $('#UnreviewedIdeaTable').on('click', '#UnreviewedIdeaTable_view_more', function () {
        $("#IdeasUnreviewedTable").hide();
        $("#UnreviewedIdeaForm").show();
        $(".close_task_undone").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormReviewedIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ReviewedIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                    '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="">Send Reminer</a>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#UnreviewedIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });  
    
    $('#UnreviewedIdeaForm').on('click', '#FormBackButton', function () {
        $("#ReviewedIdeaForm").hide();
        $("#IdeasReviewedTable").show();
        $(".close_tasks_done").show();
    });





    $('#escalate').click(function () {
        $("#CommitteeCard").hide();
        $("#EscalateIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".CloseEscalateIdeaTable").show();
        $('#committeeTab').append(': Table to Escalate Ideas to the Committee');
    });
    $('#approval').click(function () {
        $("#CommitteeCard").hide();
        $("#ApproveIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".CloseApproveIdeaTable").show();
        $('#committeeTab').append(': Table to Approve Ideas at the Committee');
    });
    $('#rejection').click(function () {
        $("#CommitteeCard").hide();
        $("#RejectIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".CloseRejectIdeaTable").show();
        $('#committeeTab').append(': Table to Reject Ideas at the Committee');
    });
    $('#committeePending').click(function () {
        $("#CommitteeCard").hide();
        $("#PendingIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".ClosePendingIdeaTable").show();
        $('#committeeTab').append(': Table for Pending Ideas at the Committee');
    });
    $('#committeeApproved').click(function () {
        $("#CommitteeCard").hide();
        $("#ApprovedIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".CloseApprovedIdeaTable").show();
        $('#committeeTab').append(': Table for Approved Ideas by the Committee');
    });
    $('#committeeRejected').click(function () {
        $("#CommitteeCard").hide();
        $("#RejectedIdeaDiv").show();
        $(".close_approved_committee").hide();
        $(".CloseRejectedIdeaTable").show();
        $('#committeeTab').append(': Table for Rejected Ideas by the Committee');
    });


    

    var existingEscalateIdeas = [];
    var currentPageEscalateIdeas = 1;
    var itemsPerPageEscalateIdeas = 10;
    
    function EscalateIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateEscalateIdeaTable&page=' + currentPageEscalateIdeas + '&itemsPerPage=' + itemsPerPageEscalateIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#EscalateIdeaTable').empty();
            
                    var counter = (currentPageEscalateIdeas - 1) * itemsPerPageEscalateIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingEscalateIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingEscalateIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="EscalateIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-warning" data-idea-uuid="' + idea.idea_uuid + '" id="btn_escalate2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">escalate&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#EscalateIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingEscalateIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#EscalateIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsEscalateIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#EscalateIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#EscalateIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsEscalateIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageEscalateIdeaTable(-1)" ' + (currentPageEscalateIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageEscalateIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageEscalateIdeaTable(1)" ' + (currentPageEscalateIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#EscalateIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageEscalateIdeaTable = function(direction) {
        currentPageEscalateIdeas += direction;
        EscalateIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#EscalateIdeaTable_view_more', function () {
        $("#EscalateIdeaDiv").hide();
        $("#EscalateIdeaForm").show();
        $(".CloseEscalateIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormEscalateIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#EscalateIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseEscalateIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-warning" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_escalate">Escalate To The Committee</a>'+
                            '</div>' +
                        '</div>';
                    $('#EscalateIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.CloseEscalateIdeaTable', function () {
        $("#EscalateIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .CloseEscalateIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#EscalateIdeaForm').on('click', '#FormBackButton', function () {
        $("#EscalateIdeaForm").hide();
        $("#EscalateIdeaDiv").show();
        $(".CloseEscalateIdeaTable").show();
    });

    $('.approved_committee').on('click', '#btn_escalate, #btn_escalate2', function () {
        $("#btn_escalate, #btn_escalate2").prop("disabled", true);
        $("#btn_escalate, #btn_escalate2").html("Processing<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#ffd966");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_EscalateIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#EscalateIdeaForm").hide();
                    $("#EscalateIdeaDiv").show();
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageCommittee').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageCommittee').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '#btn_close_success, #btn_close_error', function () {
        $("#PassMessageCommittee").hide();
    });

    


    var existingRejectIdeas = [];
    var currentPageRejectIdeas = 1;
    var itemsPerPageRejectIdeas = 10;
    
    function RejectIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateRejectIdeaTable&page=' + currentPageRejectIdeas + '&itemsPerPage=' + itemsPerPageRejectIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#RejectIdeaTable').empty();
            
                    var counter = (currentPageRejectIdeas - 1) * itemsPerPageRejectIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingRejectIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingRejectIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="RejectIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-danger" data-idea-uuid="' + idea.idea_uuid + '" id="btn_reject2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">reject&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#RejectIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingRejectIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#RejectIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsRejectIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#RejectIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#RejectIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsRejectIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageRejectIdeaTable(-1)" ' + (currentPageRejectIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageRejectIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageRejectIdeaTable(1)" ' + (currentPageRejectIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#RejectIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageRejectIdeaTable = function(direction) {
        currentPageRejectIdeas += direction;
        RejectIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#RejectIdeaTable_view_more', function () {
        $("#RejectIdeaDiv").hide();
        $("#RejectIdeaForm").show();
        $(".CloseRejectIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormRejectIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#RejectIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseRejectIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-danger" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_reject">Reject & Notify Author</a>'+
                            '</div>' +
                        '</div>';
                    $('#RejectIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.CloseRejectIdeaTable', function () {
        $("#RejectIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .CloseRejectIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#RejectIdeaForm').on('click', '#FormBackButton', function () {
        $("#RejectIdeaForm").hide();
        $("#RejectIdeaDiv").show();
        $(".CloseRejectIdeaTable").show();
    });

    $('.approved_committee').on('click', '#btn_reject, #btn_reject2', function () {
        $("#btn_reject, #btn_reject2").prop("disabled", true);
        $("#btn_reject, #btn_reject2").html("Rejecting<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#dc3545ad");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_RejectIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#RejectIdeaForm").hide();
                    $("#RejectIdeaDiv").show();
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageCommittee').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageCommittee').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });

    


    var existingApproveIdeas = [];
    var currentPageApproveIdeas = 1;
    var itemsPerPageApproveIdeas = 10;
    
    function ApproveIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateApproveIdeaTable&page=' + currentPageApproveIdeas + '&itemsPerPage=' + itemsPerPageApproveIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#ApproveIdeaTable').empty();
            
                    var counter = (currentPageApproveIdeas - 1) * itemsPerPageApproveIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingApproveIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingApproveIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="ApproveIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-success" data-idea-uuid="' + idea.idea_uuid + '" id="btn_approve2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">approve&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#ApproveIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingApproveIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#ApproveIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsApproveIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#ApproveIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#ApproveIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsApproveIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageApproveIdeaTable(-1)" ' + (currentPageApproveIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageApproveIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageApproveIdeaTable(1)" ' + (currentPageApproveIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#ApproveIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageApproveIdeaTable = function(direction) {
        currentPageApproveIdeas += direction;
        ApproveIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#ApproveIdeaTable_view_more', function () {
        $("#ApproveIdeaDiv").hide();
        $("#ApproveIdeaForm").show();
        $(".CloseApproveIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormApproveIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ApproveIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseApproveIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-success" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_approve">Approve & Notify Author</a>'+
                            '</div>' +
                        '</div>';
                    $('#ApproveIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.CloseApproveIdeaTable', function () {
        $("#ApproveIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .CloseApproveIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#ApproveIdeaForm').on('click', '#FormBackButton', function () {
        $("#ApproveIdeaForm").hide();
        $("#ApproveIdeaDiv").show();
        $(".CloseApproveIdeaTable").show();
    });

    $('.approved_committee').on('click', '#btn_approve, #btn_approve2', function () {
        $("#btn_approve, #btn_approve2").prop("disabled", true);
        $("#btn_approve, #btn_approve2").html("approving<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#198754a1");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_ApproveIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#ApproveIdeaForm").hide();
                    $("#ApproveIdeaDiv").show();
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageCommittee').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageCommittee').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });

    


    var existingPendingIdeas = [];
    var currentPagePendingIdeas = 1;
    var itemsPerPagePendingIdeas = 10;
    
    function PendingIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdatePendingIdeaTable&page=' + currentPagePendingIdeas + '&itemsPerPage=' + itemsPerPagePendingIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#PendingIdeaTable').empty();
            
                    var counter = (currentPagePendingIdeas - 1) * itemsPerPagePendingIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingPendingIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingPendingIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="PendingIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#PendingIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingPendingIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#PendingIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsPendingIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#PendingIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#PendingIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsPendingIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePagePendingIdeaTable(-1)" ' + (currentPagePendingIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPagePendingIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePagePendingIdeaTable(1)" ' + (currentPagePendingIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#PendingIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePagePendingIdeaTable = function(direction) {
        currentPagePendingIdeas += direction;
        PendingIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#PendingIdeaTable_view_more', function () {
        $("#PendingIdeaDiv").hide();
        $("#PendingIdeaForm").show();
        $(".ClosePendingIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormPendingIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#PendingIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger ClosePendingIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded Idea PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#PendingIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.ClosePendingIdeaTable', function () {
        $("#PendingIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .ClosePendingIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#PendingIdeaForm').on('click', '#FormBackButton', function () {
        $("#PendingIdeaForm").hide();
        $("#PendingIdeaDiv").show();
        $(".ClosePendingIdeaTable").show();
    });
    
    


    var existingApprovedIdeas = [];
    var currentPageApprovedIdeas = 1;
    var itemsPerPageApprovedIdeas = 10;
    
    function ApprovedIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateApprovedIdeaTable&page=' + currentPageApprovedIdeas + '&itemsPerPage=' + itemsPerPageApprovedIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#ApprovedIdeaTable').empty();
            
                    var counter = (currentPageApprovedIdeas - 1) * itemsPerPageApprovedIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingApprovedIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingApprovedIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="ApprovedIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#ApprovedIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingApprovedIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#ApprovedIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsApprovedIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#ApprovedIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#ApprovedIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsApprovedIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageApprovedIdeaTable(-1)" ' + (currentPageApprovedIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageApprovedIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageApprovedIdeaTable(1)" ' + (currentPageApprovedIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#ApprovedIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageApprovedIdeaTable = function(direction) {
        currentPageApprovedIdeas += direction;
        ApprovedIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#ApprovedIdeaTable_view_more', function () {
        $("#ApprovedIdeaDiv").hide();
        $("#ApprovedIdeaForm").show();
        $(".CloseApprovedIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormApprovedIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ApprovedIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseApprovedIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#ApprovedIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.CloseApprovedIdeaTable', function () {
        $("#ApprovedIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .CloseApprovedIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#ApprovedIdeaForm').on('click', '#FormBackButton', function () {
        $("#ApprovedIdeaForm").hide();
        $("#ApprovedIdeaDiv").show();
        $(".CloseApprovedIdeaTable").show();
    });

    
    
    

    var existingRejectedIdeas = [];
    var currentPageRejectedIdeas = 1;
    var itemsPerPageRejectedIdeas = 10;
    
    function RejectedIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateRejectedIdeaTable&page=' + currentPageRejectedIdeas + '&itemsPerPage=' + itemsPerPageRejectedIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#RejectedIdeaTable').empty();
            
                    var counter = (currentPageRejectedIdeas - 1) * itemsPerPageRejectedIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingRejectedIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingRejectedIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="RejectedIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#RejectedIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingRejectedIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#RejectedIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsRejectedIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#RejectedIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#RejectedIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsRejectedIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageRejectedIdeaTable(-1)" ' + (currentPageRejectedIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageRejectedIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageRejectedIdeaTable(1)" ' + (currentPageRejectedIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#RejectedIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageRejectedIdeaTable = function(direction) {
        currentPageRejectedIdeas += direction;
        RejectedIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_committee').on('click', '#RejectedIdeaTable_view_more', function () {
        $("#RejectedIdeaDiv").hide();
        $("#RejectedIdeaForm").show();
        $(".CloseRejectedIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormRejectedIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#RejectedIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseRejectedIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#RejectedIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_committee').on('click', '.CloseRejectedIdeaTable', function () {
        $("#RejectedIdeaDiv").hide();
        $("#CommitteeCard").show();
        $(".approved_committee .CloseRejectedIdeaTable").hide();
        $(".close_approved_committee").show();
        $('#committeeTab').text('Committee Tab');
    });
    
    $('#RejectedIdeaForm').on('click', '#FormBackButton', function () {
        $("#RejectedIdeaForm").hide();
        $("#RejectedIdeaDiv").show();
        $(".CloseRejectedIdeaTable").show();
    });

    
    $('#BoardEscalate').click(function () {
        $("#BoardCard").hide();
        $("#EscalateBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".CloseEscalateBoardIdeaTable").show();
        $('#boardTab').append(': Table to Escalate Ideas to the Board');
    });
    $('#BoardApproval').click(function () {
        $("#BoardCard").hide();
        $("#ApproveBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".CloseApproveBoardIdeaTable").show();
        $('#boardTab').append(': Table to Approve Ideas at the Board');
    });
    $('#BoardRejection').click(function () {
        $("#BoardCard").hide();
        $("#RejectBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".CloseRejectBoardIdeaTable").show();
        $('#boardTab').append(': Table to Reject Ideas at the Board');
    });
    $('#BoardPending').click(function () {
        $("#BoardCard").hide();
        $("#PendingBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".ClosePendingBoardIdeaTable").show();
        $('#boardTab').append(': Table for Pending Ideas at the Board');
    });
    $('#BoardApproved').click(function () {
        $("#BoardCard").hide();
        $("#ApprovedBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".CloseApprovedBoardIdeaTable").show();
        $('#boardTab').append(': Table for Approved Ideas by the Board');
    });
    $('#BoardRejected').click(function () {
        $("#BoardCard").hide();
        $("#RejectedBoardIdeaDiv").show();
        $(".close_approved_board").hide();
        $(".CloseRejectedBoardIdeaTable").show();
        $('#boardTab').append(': Table for Rejected Ideas by the Board');
    });


    

    var existingEscalateBoardIdeas = [];
    var currentPageEscalateBoardIdeas = 1;
    var itemsPerPageEscalateBoardIdeas = 10;
    
    function EscalateBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateEscalateBoardIdeaTable&page=' + currentPageEscalateBoardIdeas + '&itemsPerPage=' + itemsPerPageEscalateBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#EscalateBoardIdeaTable').empty();
            
                    var counter = (currentPageEscalateBoardIdeas - 1) * itemsPerPageEscalateBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingEscalateBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingEscalateBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="EscalateBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-warning" data-idea-uuid="' + idea.idea_uuid + '" id="btn_board_escalate2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">escalate&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#EscalateBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingEscalateBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#EscalateBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsEscalateBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#EscalateBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#EscalateBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsEscalateBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageEscalateBoardIdeaTable(-1)" ' + (currentPageEscalateBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageEscalateBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageEscalateBoardIdeaTable(1)" ' + (currentPageEscalateBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#EscalateBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageEscalateBoardIdeaTable = function(direction) {
        currentPageEscalateBoardIdeas += direction;
        EscalateBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#EscalateBoardIdeaTable_view_more', function () {
        $("#EscalateBoardIdeaDiv").hide();
        $("#EscalateBoardIdeaForm").show();
        $(".CloseEscalateBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormEscalateBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#EscalateBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseEscalateBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-warning" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_board_escalate">Escalate To The Board</a>'+
                            '</div>' +
                        '</div>';
                    $('#EscalateBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.CloseEscalateBoardIdeaTable', function () {
        $("#EscalateBoardIdeaDiv").hide();
        $("#BoardCard").show();
        //check this from here
        $(".approved_board .CloseEscalateBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#EscalateBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#EscalateBoardIdeaForm").hide();
        $("#EscalateBoardIdeaDiv").show();
        $(".CloseEscalateBoardIdeaTable").show();
    });

    $('.approved_board').on('click', '#btn_board_escalate, #btn_board_escalate2', function () {
        $("#btn_board_escalate, #btn_board_escalate2").prop("disabled", true);
        $("#btn_board_escalate, #btn_board_escalate2").html("Processing<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#ffd966");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_EscalateBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#EscalateBoardIdeaForm").hide();
                    $("#EscalateBoardIdeaDiv").show();
                    $('#PassMessageBoard').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageBoard').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageBoard').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageBoard').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '#btn_close_success, #btn_close_error', function () {
        $("#PassMessageBoard").hide();
    });



    var existingRejectBoardIdeas = [];
    var currentPageRejectBoardIdeas = 1;
    var itemsPerPageRejectBoardIdeas = 10;
    
    function RejectBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateRejectBoardIdeaTable&page=' + currentPageRejectBoardIdeas + '&itemsPerPage=' + itemsPerPageRejectBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#RejectBoardIdeaTable').empty();
            
                    var counter = (currentPageRejectBoardIdeas - 1) * itemsPerPageRejectBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingRejectBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingRejectBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="RejectBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-danger" data-idea-uuid="' + idea.idea_uuid + '" id="btn_reject2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">reject&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#RejectBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingRejectBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#RejectBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsRejectBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#RejectBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#RejectBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsRejectBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageRejectBoardIdeaTable(-1)" ' + (currentPageRejectBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageRejectBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageRejectBoardIdeaTable(1)" ' + (currentPageRejectBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#RejectBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageRejectBoardIdeaTable = function(direction) {
        currentPageRejectBoardIdeas += direction;
        RejectBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#RejectBoardIdeaTable_view_more', function () {
        $("#RejectBoardIdeaDiv").hide();
        $("#RejectBoardIdeaForm").show();
        $(".CloseRejectBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormRejectBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#RejectBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseRejectBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-danger" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_reject">Reject & Notify Author</a>'+
                            '</div>' +
                        '</div>';
                    $('#RejectBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.CloseRejectBoardIdeaTable', function () {
        $("#RejectBoardIdeaDiv").hide();
        $("#BoardCard").show();
        $(".approved_board .CloseRejectBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#RejectBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#RejectBoardIdeaForm").hide();
        $("#RejectBoardIdeaDiv").show();
        $(".CloseRejectBoardIdeaTable").show();
    });

    $('.approved_board').on('click', '#btn_reject, #btn_reject2', function () {
        $("#btn_reject, #btn_reject2").prop("disabled", true);
        $("#btn_reject, #btn_reject2").html("Rejecting<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#dc3545ad");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_RejectBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#RejectBoardIdeaForm").hide();
                    $("#RejectBoardIdeaDiv").show();
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageCommittee').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageCommittee').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });

    

    var existingApproveBoardIdeas = [];
    var currentPageApproveBoardIdeas = 1;
    var itemsPerPageApproveBoardIdeas = 10;
    
    function ApproveBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateApproveBoardIdeaTable&page=' + currentPageApproveBoardIdeas + '&itemsPerPage=' + itemsPerPageApproveBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#ApproveBoardIdeaTable').empty();
            
                    var counter = (currentPageApproveBoardIdeas - 1) * itemsPerPageApproveBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingApproveBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingApproveBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="ApproveBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                    '<div class="btn btn-success" data-idea-uuid="' + idea.idea_uuid + '" id="btn_board_approve2" style="text-decoration: none; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px;">approve&nbsp;&&nbsp;notify</div>'+
                                '</td>' +
                            '</tr>';
                        $('#ApproveBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingApproveBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#ApproveBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsApproveBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#ApproveBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#ApproveBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsApproveBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageApproveBoardIdeaTable(-1)" ' + (currentPageApproveBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageApproveBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageApproveBoardIdeaTable(1)" ' + (currentPageApproveBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#ApproveBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageApproveBoardIdeaTable = function(direction) {
        currentPageApproveBoardIdeas += direction;
        ApproveBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#ApproveBoardIdeaTable_view_more', function () {
        $("#ApproveBoardIdeaDiv").hide();
        $("#ApproveBoardIdeaForm").show();
        $(".CloseApproveBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormApproveBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ApproveBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseApproveBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">view PDF</a>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-success" style="margin-top: 20px; width: fit-content;" data-idea-uuid="' + idea.idea_uuid + '" id="btn_board_approve">Approve & Notify Author</a>'+
                            '</div>' +
                        '</div>';
                    $('#ApproveBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.CloseApproveBoardIdeaTable', function () {
        $("#ApproveBoardIdeaDiv").hide();
        $("#BoardCard").show();
        $(".approved_board .CloseApproveBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#ApproveBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#ApproveBoardIdeaForm").hide();
        $("#ApproveBoardIdeaDiv").show();
        $(".CloseApproveBoardIdeaTable").show();
    });

    $('.approved_board').on('click', '#btn_board_approve, #btn_board_approve2', function () {
        $("#btn_board_approve, #btn_board_approve2").prop("disabled", true);
        $("#btn_board_approve, #btn_board_approve2").html("approving<i class=\"fa-solid fa-spinner fa-spin-pulse\"></i>").css("background-color", "#198754a1");
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateDB_ApproveBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.log("Escalation response received:", data);
                if (data.message === 'Successful') {
                    console.log("Escalation successful!");
                    startUpdate();
                    $("#ApproveBoardIdeaForm").hide();
                    $("#ApproveBoardIdeaDiv").show();
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                        '<div id="successMessage" style="background-color: #c9f7e1; border: 2px solid #47d1a8; color: #1e6f5c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                            '<i class="fas fa-check-circle" style="font-size: 24px; color: #155724; margin-right: 10px;"></i>' +
                            '<h3 style="font-size: 24px; margin-bottom: 10px;">Success!</h3>' +
                            '<p style="font-size: 18px;">Your submission was successful.</p>' +
                            '<button id="btn_close_success" style="background-color: #47d1a8; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                        '</div>';
                    $('#PassMessageCommittee').append(newRow);
                } else {
                    console.error("Escalation failed:", data.message);
                    $('#PassMessageCommittee').empty().show();
            
                    var newRow =
                    '<div id="errorMessage" style="background-color: #fbc9c9; border: 2px solid #e05a5a; color: #7d1c1c; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: center;">' +
                        '<i class="fa-solid fa-circle-xmark" style="font-size: 24px; color: #721c24; margin-right: 10px;"></i>' +
                        '<h3 style="font-size: 24px; margin-bottom: 10px;">Error!</h3>' +
                        '<p style="font-size: 18px;">There was an error submitting your request. Please try again later.</p>' +
                        '<button id="btn_close_error" style="background-color: #e05a5a; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>' +
                    '</div>';
                    $('#PassMessageCommittee').append(newRow);
                }
            },
            
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });


    

    

    var existingPendingBoardIdeas = [];
    var currentPagePendingBoardIdeas = 1;
    var itemsPerPagePendingBoardIdeas = 10;
    
    function PendingBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdatePendingBoardIdeaTable&page=' + currentPagePendingBoardIdeas + '&itemsPerPage=' + itemsPerPagePendingBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#PendingBoardIdeaTable').empty();
            
                    var counter = (currentPagePendingBoardIdeas - 1) * itemsPerPagePendingBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingPendingBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingPendingBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="PendingBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#PendingBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingPendingBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#PendingBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsPendingBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#PendingBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#PendingBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsPendingBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePagePendingBoardIdeaTable(-1)" ' + (currentPagePendingBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPagePendingBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePagePendingBoardIdeaTable(1)" ' + (currentPagePendingBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#PendingBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePagePendingBoardIdeaTable = function(direction) {
        currentPagePendingBoardIdeas += direction;
        PendingBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#PendingBoardIdeaTable_view_more', function () {
        $("#PendingBoardIdeaDiv").hide();
        $("#PendingBoardIdeaForm").show();
        $(".ClosePendingBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormPendingBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#PendingBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger ClosePendingBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded Idea PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#PendingBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.ClosePendingBoardIdeaTable', function () {
        $("#PendingBoardIdeaDiv").hide();
        $("#BoardCard").show();
        $(".approved_board .ClosePendingBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#PendingBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#PendingBoardIdeaForm").hide();
        $("#PendingBoardIdeaDiv").show();
        $(".ClosePendingBoardIdeaTable").show();
    });


    
    
    
    var existingApprovedBoardIdeas = [];
    var currentPageApprovedBoardIdeas = 1;
    var itemsPerPageApprovedBoardIdeas = 10;
    
    function ApprovedBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateApprovedBoardIdeaTable&page=' + currentPageApprovedBoardIdeas + '&itemsPerPage=' + itemsPerPageApprovedBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#BoardIdeaTable').empty();
            
                    var counter = (currentPageApprovedBoardIdeas - 1) * itemsPerPageApprovedBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingApprovedBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingApprovedBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="ApprovedBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#ApprovedBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingApprovedBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#ApprovedBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsApprovedBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#ApprovedBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#ApprovedBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsApprovedBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageApprovedBoardIdeaTable(-1)" ' + (currentPageApprovedBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageApprovedBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageApprovedBoardIdeaTable(1)" ' + (currentPageApprovedBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#ApprovedBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageApprovedBoardIdeaTable = function(direction) {
        currentPageApprovedBoardIdeas += direction;
        ApprovedBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#ApprovedBoardIdeaTable_view_more', function () {
        $("#ApprovedBoardIdeaDiv").hide();
        $("#ApprovedBoardIdeaForm").show();
        $(".CloseApprovedBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormApprovedBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#ApprovedBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseApprovedBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#ApprovedBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.CloseApprovedBoardIdeaTable', function () {
        $("#ApprovedBoardIdeaDiv").hide();
        $("#BoardCard").show();
        $(".approved_board .CloseApprovedBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#ApprovedBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#ApprovedBoardIdeaForm").hide();
        $("#ApprovedBoardIdeaDiv").show();
        $(".CloseApprovedBoardIdeaTable").show();
    });



    var existingRejectedBoardIdeas = [];
    var currentPageRejectedBoardIdeas = 1;
    var itemsPerPageRejectedBoardIdeas = 10;
    
    function RejectedBoardIdeaTable() {
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateRejectedBoardIdeaTable&page=' + currentPageRejectedBoardIdeas + '&itemsPerPage=' + itemsPerPageRejectedBoardIdeas,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#RejectedBoardIdeaTable').empty();
            
                    var counter = (currentPageRejectedBoardIdeas - 1) * itemsPerPageRejectedBoardIdeas + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingRejectedBoardIdeas.indexOf(idea.idea_uuid);
                        if (index > -1) {
                            existingRejectedBoardIdeas.splice(index, 1);
                        }
            
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.problem_statement + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_user_uploaded + '</td>' +
                                '<td style="text-align: center;"><div class = "' + idea.status + '">' + idea.status + '</div></td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-idea-uuid="' + idea.idea_uuid + '" id="RejectedBoardIdeaTable_view_more">view&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#RejectedBoardIdeaTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingRejectedBoardIdeas.push(idea.idea_uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#RejectedBoardIdeaTableFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsRejectedBoardIdeaTable(data.totalPages);
                }
                else{
                    // Handle empty data
                    $('#RejectedBoardIdeaTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#RejectedBoardIdeaTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    function addPaginationControlsRejectedBoardIdeaTable(totalPages) {
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePageRejectedBoardIdeaTable(-1)" ' + (currentPageRejectedBoardIdeas === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageRejectedBoardIdeas + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePageRejectedBoardIdeaTable(1)" ' + (currentPageRejectedBoardIdeas === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';
    
        $('#RejectedBoardIdeaTablePagination').empty().append(paginationControls);
    }
    
    window.changePageRejectedBoardIdeaTable = function(direction) {
        currentPageRejectedBoardIdeas += direction;
        RejectedBoardIdeaTable(); // Call the function that makes the AJAX request
    };

    $('.approved_board').on('click', '#RejectedBoardIdeaTable_view_more', function () {
        $("#RejectedBoardIdeaDiv").hide();
        $("#RejectedBoardIdeaForm").show();
        $(".CloseRejectedBoardIdeaTable").hide();
        var upload_id = $(this).data('idea-uuid');
        //var openFile = upload_id + '.pdf';
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateFormRejectedBoardIdeas&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (data.length > 0) {
                    var idea = data[0]; // Access the first element of the array
                    $('#RejectedBoardIdeaForm').empty();    
                    // Generate a new row and append it to the table body
                    var openFile = idea.upload_id + '.pdf';
                    var newRow =
                        '<div class="alert alert-danger CloseRejectedBoardIdeaForm" style="display: none;">' +            
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                        '<h5 style="text-align: center;">Idea Information</h5>' +
                        '<h5 style="text-align: center;">Idea Title: '+ idea.title +'</h5>' +
                        '<h5 style="text-align: center;">Submission Date</h5>' +
                        '<div style="text-align: center; margin-bottom: 55px;">'+ idea.day_user_uploaded +'</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Description</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.brief_description + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Problem Statement</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.problem_statement + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Described Cost Benefit Analysis</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.cost_benefit_analysis + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Stage</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.stage + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Idea Status</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.status + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Subject Matter Expert</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.first_name + ' ' + idea.other_names + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Expert Committed</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_expert_committed + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Committee Approval</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                                    '<h5 style="text-align: center;">Day Committee Approved</h5>' +
                                    '<div style="margin-bottom: .5rem;">' + idea.day_committee_approved + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="display: flex; justify-content: space-between;;">' +
                                '<a class="btn btn-primary" style="margin-top: 20px; width: fit-content;" id="FormBackButton">&larr; Back</a>' +
                                '<a href="view_doc?file='+ openFile +'" target="_blank" class="btn btn-primary" style="margin-top: 20px; width: fit-content;">Click to view Uploaded PDF</a>' +
                            '</div>' +
                        '</div>';
                    $('#RejectedBoardIdeaForm').append(newRow);
                    
                } 
                else {
                    alert("This Data Appears to be Deleted");
                    console.log("This Data Appears to be Deleted");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });
    
    $('.approved_board').on('click', '.CloseRejectedBoardIdeaTable', function () {
        $("#RejectedBoardIdeaDiv").hide();
        $("#BoardCard").show();
        $(".approved_board .CloseRejectedBoardIdeaTable").hide();
        $(".close_approved_board").show();
        $('#boardTab').text('Board Tab');
    });
    
    $('#RejectedBoardIdeaForm').on('click', '#FormBackButton', function () {
        $("#RejectedBoardIdeaForm").hide();
        $("#RejectedBoardIdeaDiv").show();
        $(".CloseRejectedBoardIdeaTable").show();
    });


    

        
    $('#createCommitteeForm').submit(function (event) {
        event.preventDefault();
        // If clicked, disable the button
        $('#createCommitteeSubmit').prop('disabled', true);
        $('#createCommitteeSubmit').text('creating...');
        // Get form data
        var formData = {
            committeName: $('#committeName').val(),
            expiryDate: $('#expiryDate').val(),
            chairperson: $('#chairperson').val(),
            secretary: $('#secretary').val()
        };
    
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=createNewCommittee',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.startsWith("Success")) {
                    $('#createCommitteeSubmit').prop('disabled', false);
                    $('#createCommitteeSubmit').text('Submit');

                    $("#newCommitteeTableDiv").show();
                    $(".create_new_committee_form ").hide();
                    $("#createCommitteeForm")[0].reset();
                    alert(response);
                    FetchNewCommitteeTable();



                } else {
                    $('#createCommitteeSubmit').prop('disabled', false);
                    $('#createCommitteeSubmit').text('Submit');
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                $('#createCommitteeSubmit').prop('disabled', false);
                $('#createCommitteeSubmit').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    });


    var existingNewCommitteeTable = [];
    var currentPageNewCommitteeTable = 1;
    var itemsPerPageNewCommitteeTable = 10;

    function FetchNewCommitteeTable(){
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=FetchNewCommitteeTable&page=' + currentPageNewCommitteeTable + '&itemsPerPage=' + itemsPerPageNewCommitteeTable,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#NewCommitteeTable').empty();
                    $('#tableHeadSection').empty();
            
                    var counter = (currentPageNewCommitteeTable - 1) * itemsPerPageNewCommitteeTable + 1;
            
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingNewCommitteeTable.indexOf(idea.uuid);
                        if (index > -1) {
                            existingNewCommitteeTable.splice(index, 1);
                        }
                        
                        // Generate a new section head at the table header
                        var newTableHeadSection =
                            '<div> Expiry Date: ' + data.expiry_date + '</div>' +
                            '<div>' +
                                '<i class="fa-solid fa-circle" style="color: #54ff3d;"></i>status: ' + data.status +
                            '</div>' +
                            '<div data-database-table-name="' + data.table_name + '" style="background-color: blue; padding: 5px 13px; border-radius: 5px; color: white; width: fit-content;" id="addMembers_1">' +
                                '<i class="fa-solid fa-circle-plus"></i> add members' +
                            '</div>';
                        $('#tableHeadSection').append(newTableHeadSection);


                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.name + '</td>' +
                                '<td>' + idea.position + '</td>' +
                                '<td>' + idea.tel + '</td>' +
                                '<td>' + idea.email + '</td>' +
                                '<td>' + idea.rank + '</td>' +
                                '<td>' + idea.gender + '</td>' +
                                '<td>'+
                                '<div class="view_btn" data-idea-uuid="' + idea.uuid + '" id="">Edit&nbsp;details</div>'+
                                '<div class="view_btn" data-idea-uuid="' + idea.uuid + '" id="">Remove&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#NewCommitteeTable').append(newRow);
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingNewCommitteeTable.push(idea.uuid);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#NewCommitteeTableFooter').empty().append(footerCounter);
                }
                else{
                    
                    $('#tableHeadSection').empty();
                        
                    // Generate a new section head at the table header
                    var newTableHeadSection =
                        '<div> Expiry Date:' + data.expiry_date + '</div>' +
                        '<div>' +
                            '<i class="fa-solid fa-circle" style="color: #54ff3d;"></i>status: ' + data.status +
                        '</div>' +
                        '<div data-database-table-name="' + data.table_name + '" style="background-color: blue; padding: 5px 13px; border-radius: 5px; color: white; width: fit-content;" id="addMembers_1">' +
                            '<i class="fa-solid fa-circle-plus"></i> add members' +
                        '</div>';
                    $('#tableHeadSection').append(newTableHeadSection);

                    
                    // Handle empty data
                    $('#NewCommitteeTable').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No Members found</td></tr>');
                    $('#NewCommitteeTableFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    }
    
    let table_name;

    $('#tableHeadSection').on('click', '#addMembers_1', function () {
        $("#newCommitteeTableDiv").hide();
        $("#addMemberForm_1_Div").show();

        table_name = $(this).data('database-table-name');
    });    
    
    $('#addMemberForm_1').submit(function (event) {
        event.preventDefault();
        // If clicked, disable the button
        $('#submitNewMemeber_1').prop('disabled', true);
        $('#submitNewMemeber_1').text('adding...');

        console.log(table_name);

        // Get form data
        var formData = {
            name: $('#name').val(),
            position: $('#position').val(),
            mobile: $('#mobile').val(),
            email: $('#email').val(),
            rank: $('#rank').val(),
            gender: $('#gender').val()
        };        
    
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=addNewMember_1&table_name=' + encodeURIComponent(table_name),
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.startsWith("Success")) {
                    $('#submitNewMemeber_1').prop('disabled', false);
                    $('#submitNewMemeber_1').text('Submit');
                    $("#addMemberForm_1")[0].reset();
                    alert(response);

                    //FetchNewCommitteeTable();
                } else {
                    $('#submitNewMemeber_1').prop('disabled', false);
                    $('#submitNewMemeber_1').text('Submit');
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_1').prop('disabled', false);
                $('#submitNewMemeber_1').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    });

    $('#addMemberForm_1').on('click', '#backButton', function () {
        FetchNewCommitteeTable();
        
        $("#newCommitteeTableDiv").show();
        $("#addMemberForm_1_Div").hide();
    });
    
    $('.card_create_new_committee').click(function () {
        $(".manage_committee").show();
        $(".committee_cards ").removeClass('active');
    });
    
    $('.create_new_committee_card').click(function () {
        $(".create_new_committee_form").show();
        $(".committee_cards_2").hide();
        $(".close_manage_committee").hide();
    });

    $('#createCommitteeForm').on('click', '#backButton, .close_create_committee', function () {
        $(".create_new_committee_form").hide();
        $(".committee_cards_2").show();
        $(".close_manage_committee").show();
    });
    
    $('.manage_committee').on('click', '.close_manage_committee', function () {
        $(".manage_committee").hide();
        $(".committee_cards").addClass('active');
    });


    
    
    var existingNewCommitteeGrid = [];
    var currentPageNewCommitteeGrid = 1;
    var itemsPerPageNewCommitteeGrid = 12;
    var sortOrder = 'latest';

    function ListExistingCommittee() {
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=ListExistingCommittee&page=' + currentPageNewCommitteeGrid + '&itemsPerPage=' + itemsPerPageNewCommitteeGrid,
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }

                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    existingNewCommitteeGrid = data;
                    console.table(data.items);
                    displayCommittees(existingNewCommitteeGrid);
                } else {
                    // Handle empty data
                    $('#listedCommitteesBody').empty().append('<div class="col-12 text-center font-weight-bold">No Members found</div>');
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_1').prop('disabled', false);
                $('#submitNewMemeber_1').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(xhr.responseText);
            }
        });
    }

    function displayCommittees(dataObject) {
        var dataArray = dataObject.items;
        var totalRows = dataObject.totalRows;
        $('#listedCommitteesHeader').empty();
        $('#listedCommitteesBody').empty();

        var newHeaderRow =
            '<div>' +
                '<div>' +
                    '<h2>Committee History</h2>' +
                '</div>' +
            '</div>' +
            '<div>' +
                '<div class="row justify-content-center" style="align-items: center;">' +
                    '<div class="col-sm-12 col-md-8">' +
                        '<div class="search-bar" style="display: flex; justify-content: center; align-items: center; gap: 7px; border: 1px solid black; border-radius: 15px;">' +
                            '<input type="text" id="searchInput_1" class="" placeholder="Search..." style="border: none; width: 90%; height: 20px; padding: 13px; margin: 5px; border-right: 1px solid black;">' +
                            '<i class="fas fa-search search-icon" style="margin-right: 13px;"></i>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-sm-12 col-md-4" style="padding-top: 10px; padding-bottom: 10px;">' +
                        '<div class="filter dropdown">' +
                            '<button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown_1" data-bs-toggle="dropdown" aria-expanded="false">' +
                                '<i class="fas fa-filter"></i> Filter' +
                            '</button>' +
                            '<ul class="dropdown-menu" aria-labelledby="filterDropdown">' +
                                '<li><a class="dropdown-item" data-sort="newest">Newest</a></li>' +
                                '<li><a class="dropdown-item" data-sort="oldest">Oldest</a></li>' +
                                '<li><a class="dropdown-item" data-sort="a-z">A-Z</a></li>' +
                                '<li><a class="dropdown-item" data-sort="z-a">Z-A</a></li>' +
                            '</ul>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        $('#listedCommitteesHeader').append(newHeaderRow);

        var counter = (currentPageNewCommitteeGrid - 1) * itemsPerPageNewCommitteeGrid + 1;

        dataArray.forEach(function (idea) {
            var tableName = idea.table_name;

            // Split the string into an array of words using the underscore as the delimiter
            var words = tableName.split('_');

            // Check if the word "committee" appears more than once
            var committeeCount = words.filter(function(word) {
                return word.toLowerCase() === 'committee';
            }).length;

            // If "committee" appears more than once, remove the first occurrence
            if (committeeCount > 1) {
                var firstIndex = words.findIndex(function(word) {
                    return word.toLowerCase() === 'committee';
                });
                words.splice(firstIndex, 1);
            }

            // Capitalize the first letter of each word and join them back together with a space
            var formattedTableName = words.map(function(word) {
                return word.charAt(0).toUpperCase() + word.slice(1);
            }).join(' ');

            var statusColor;
            if (idea.status === 'active') {
                statusColor = '<i class="fa-solid fa-circle-dot" style="color: #00ff00;"></i>';
            } else {
                statusColor = '<i class="fa-solid fa-circle-dot" style="color: #ff0000;"></i>';
            }

            // Generate a new row and append it to the table
            var newRow =
                '<div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6">' +
                    '<div style="background-color: #ffff0096; border-radius: 8px; padding: 5px 10px;">' +
                        '<div>' +
                            '<h5 style="display: flex; align-items: center; justify-content: center;">' +
                                '<div>' + counter + '.&nbsp</div>' +
                                '<u><div style="text-align: center;">' + formattedTableName + '</div></u>' +
                            '</h5>' +
                        '</div>' +
                        '<div style="display: flex; flex-direction: row; justify-content: space-around;">' +
                            '<div>' +
                                '<b><div>Created On:</div></b>' +
                                '<div>' + idea.creation_date + '</div>' +
                            '</div>' +
                            '<div>' +
                                '<b><div>To Expire On:</div></b>' +
                                '<div>' + idea.expiry_date + '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div style="display: flex; justify-content: center;">' +
                            '<b><div style="display: flex; align-items: center;">Status (' + statusColor + '):&nbsp;</div></b>' +
                            '<div>' + idea.status + '</div>' +
                        '</div>' +
                        '<div>' +
                            '<div class="view_btn" data-idea-name="' + tableName + '" id="listedCommitteeCard_view_more" style="text-decoration: none; color: white; background-color: #6060ec; width: 100%; display: flex; justify-content: center; padding: 3px; margin-bottom: 8px; border-radius: 5px; margin: 10px 0px;">View&nbsp;Committee Members</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            $('#listedCommitteesBody').append(newRow);

            counter++;
        });

        // Handle no results found
        if (dataArray.length === 0) {
            $('#listedCommitteesBody').html('<div class="no-results" style="padding: 20px 15px; text-align: center; font-weight: 700; font-size: 20px;">"No results found for your search."</div>');
        }


        // Function to handle search input
        var searchTimeout; 
        $('#searchInput_1').on('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Set a timeout to execute search after the user has finished typing
            searchTimeout = setTimeout(function() {
                var query = $(this).val().toLowerCase();
                console.log(query);
                var filteredData = existingNewCommitteeGrid.items.filter(function(item) {
                    // Split the query into individual words
                    var queryWords = query.split(' ');
                    // Check if each word is contained in the table_name
                    return queryWords.every(function(word) {
                        return item.table_name.toLowerCase().includes(word);
                    });
                });
                displayCommittees({ items: filteredData, totalRows: filteredData.length });
            }.bind(this), 1500); // Adjust the delay (in milliseconds) according to your preference
        });
        
        $('.dropdown-item').on('click', function() {
            sortOrder = $(this).data('sort');
            var sortedData = sortCommittees(existingNewCommitteeGrid.items, sortOrder);
            displayCommittees(existingNewCommitteeGrid);
        });
        

        var footerCounter =
            '<tr>' +
            '<td colspan="9">Total ' + (counter - 1) + ' of ' + dataArray.totalRows + '</td>' +
            '</tr>';

        $('#NewCommitteeGridFooter').empty().append(footerCounter);

        // Add pagination controls
        var totalPages = Math.ceil(totalRows / itemsPerPageNewCommitteeGrid);
        addPaginationControlsNewCommitteeGrid(totalPages);
    }
    
    function sortCommittees(dataArray, sortOrder) {
        return dataArray.sort(function(a, b) {
            switch (sortOrder) {
                case 'newest':
                    return new Date(b.creation_date) - new Date(a.creation_date);
                case 'oldest':
                    return new Date(a.creation_date) - new Date(b.creation_date);
                case 'a-z':
                    return a.table_name.localeCompare(b.table_name);
                case 'z-a':
                    return b.table_name.localeCompare(a.table_name);
                default:
                    return 0;
            }
        });
    }    

    function addPaginationControlsNewCommitteeGrid(totalPages) {
        console.log(totalPages);
        var paginationControls =
            '<div class="pagination" style="justify-content: center;">' +
                '<button id="prevPage" onclick="changePage(-1)" ' + (currentPageNewCommitteeGrid === 1 ? 'disabled' : '') + '>&lt;&lt; Previous&nbsp;</button>' +
                '<span> Page ' + currentPageNewCommitteeGrid + ' of ' + totalPages + ' </span>' +
                '<button id="nextPage" onclick="changePage(1)" ' + (currentPageNewCommitteeGrid === totalPages ? 'disabled' : '') + '>&nbsp;Next &gt;&gt;</button>' +
            '</div>';

        $('#paginationControlGrid').empty().append(paginationControls);
    }

    window.changePage = function(direction) {
        currentPageNewCommitteeGrid += direction;
        ListExistingCommittee(); // Call the function that makes the AJAX request
    };


    //open committee table
    
    var existingNewCommitteeNewGrid = [];
    var currentPageNewCommitteeNewGrid = 1;
    var itemsPerPageNewCommitteeNewGrid = 12;
    $('#listedCommitteesBody').on('click', '#listedCommitteeCard_view_more', function () {
        $("#listedCommitteesBody").hide();
        $(".clickedCommitteeTableDiv_1").show();
        $('#listedCommitteesHeader').empty();
        $(".committee_members .date").hide();
        $('#activate_or_deactivate').empty();

        var upload_id = $(this).data('idea-name');

        var tableName = upload_id

        // Split the string into an array of words using the underscore as the delimiter
        var words = tableName.split('_');
        
        // Check if the word "committee" appears more than once
        var committeeCount = words.filter(function(word) {
            return word.toLowerCase() === 'committee';
        }).length;
        
        // If "committee" appears more than once, remove the first occurrence
        if (committeeCount > 1) {
            var firstIndex = words.findIndex(function(word) {
                return word.toLowerCase() === 'committee';
            });
            words.splice(firstIndex, 1);
        }
        
        // Capitalize the first letter of each word and join them back together with a space
        var formattedTableName = words.map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');

        var newHeaderRow =
            '<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">' +
                '<div>' +
                    '<h2 style="margin-left: 15px;">Table : ' + formattedTableName + '</h2>' +
                '</div>' +
                '<div class="alert alert-danger close_committee_members">' +
                    '<button type="button" class="btn-close" aria-label="Close"></button>' +
                '</div>' +
            '</div>';
        $('#listedCommitteesHeader').append(newHeaderRow);

        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=FetchClickedCommitteeTable&page=' + currentPage + '&itemsPerPage=' + itemsPerPage + '&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                console.table(data);
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;

                    // Clear the existing data in the table
                    $('#committeeTableViewBody').empty();
            
                    var counter = (currentPageNewCommitteeNewGrid - 1) * itemsPerPageNewCommitteeNewGrid + 1;

                    
            
                    var counter = (currentPageNewCommitteeNewGrid - 1) * itemsPerPageNewCommitteeNewGrid + 1;
    
                    
                    var status_and_add_button;
                    if (data.status === 'active') {

                        var status_and_add_button =
                            '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                                '<span class="badge bg-danger" style="font-size: 15px;" data-table-name="' + tableName + '" id="deactivateTable">Deactivate</span>'+
                                '<span class="badge bg-primary" style="font-size: 15px;" data-table-name="' + tableName + '" id="addMember_2">Add Member</span>' +
                            '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    } else {
                        
                        var status_and_add_button =
                        '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                            '<span class="badge bg-success" style="font-size: 15px;" data-table-name="' + tableName + '" id="activateTable">Activate</span>'+
                        '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    }

                    
                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingNewCommitteeNewGrid.indexOf(idea.id);
                        if (index > -1) {
                            existingNewCommitteeNewGrid.splice(index, 1);
                        }

                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.name + '</td>' +
                                '<td>' + idea.position + '</td>' +
                                '<td>' + idea.tel + '</td>' +
                                '<td>' + idea.email + '</td>' +
                                '<td>' + idea.rank + '</td>' +
                                '<td>' + idea.gender + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-member-uuid="' + idea.uuid + '" data-table-name="' + tableName + '" data-member-name = "' + idea.name + '" data-member-position = "' + idea.position + '" data-member-tel = "' + idea.tel + '" data-member-email = "' + idea.email + '" data-member-rank = "' + idea.rank + '" id="editMembersDetails">Edit&nbsp;details</div>'+
                                    '<div class="view_btn" data-total-counter="' + data.totalRows + '" data-previous-counter="' + (counter - 1) + '" data-member-uuid="' + idea.uuid + '" data-table-name="' + tableName + '" id="removeMemberDetails">Remove&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#committeeTableViewBody').append(newRow);

            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingNewCommitteeNewGrid.push(idea.id);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#committeeTableViewBodyFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsNewCommitteeGrid(data.totalPages);
                }
                else{
                    var status_and_add_button;
                    if (data.status === 'active') {

                        var status_and_add_button =
                            '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                                '<span class="badge bg-danger" style="font-size: 15px;" data-table-name="' + tableName + '" id="deactivateTable">Deactivate</span>'+
                                '<span class="badge bg-primary" style="font-size: 15px;" data-table-name="' + tableName + '" id="addMember_2">Add Member</span>' +
                            '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    } else {
                        
                        var status_and_add_button =
                        '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                            '<span class="badge bg-success" style="font-size: 15px;" data-table-name="' + tableName + '" id="activateTable">Activate</span>'+
                        '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    }
                    
                    // Handle empty data
                    $('#committeeTableViewBody').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No Members found</td></tr>');
                    $('#committeeTableViewBodyFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_1').prop('disabled', false);
                $('#submitNewMemeber_1').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                console.log('An error occurred: ' + error); // Display the error message
            }
        });
    });

    //close table committee
    $('#listedCommittees').on('click', '.close_committee_members', function () {
        $("#listedCommitteesBody").show();
        $(".clickedCommitteeTableDiv_1").hide();
        $('#listedCommitteesHeader').empty();
        $(".committee_members .date").show();
        

        
        var newHeaderRow =
            '<div>' +
                '<div>' +
                    '<h2>Committee History</h2>' +
                '</div>' +
                '<div>close btn</div>' +
            '</div>' +
            '<div>' +
                '<div>search</div>' +
                '<div>filter</div>' +
            '</div>';
        $('#listedCommitteesHeader').append(newHeaderRow);
        ListExistingCommittee();
    });

    //remove committee member
    $('#listedCommittees').on('click', '#removeMemberDetails', function () {

        var table_name = $(this).data('table-name');
        var member_uuid = $(this).data('member-uuid');
        var previous_counter = $(this).data('previous-counter');
        var total_counter = $(this).data('total-counter');
        var row = $(this).closest('tr');

        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=RemoveCommitteeMember&table_name=' + table_name + '&member_uuid=' + member_uuid,
            type: 'GET',
            success: function (data) {
                var totalCounter = total_counter - 1;
                row.remove();
        
                var footerCounter =
                    '<tr>' +
                    '<td colspan="9">Total ' + previous_counter + ' of '+ totalCounter +'</td>' +
                    '</tr>';
        
                $('#committeeTableViewBodyFooter').empty().append(footerCounter);
                
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); // Log the full server response
                console.error(error); // Log the error message
            }
        });
    });

    //edit committee member
    $('#listedCommittees').on('click', '#editMembersDetails', function () {
        $("#listedCommitteesHeader").hide();
        $(".clickedCommitteeTableDiv_1").hide();
        $("#formEditDetails").show();

        var member_name = $(this).data('member-name');
        var member_position = $(this).data('member-position');
        var member_tel = $(this).data('member-tel');
        var member_email = $(this).data('member-email');
        var member_rank = $(this).data('member-rank');
        var member_uuid = $(this).data('member-uuid');
        var tableName = $(this).data('table-name');

        $('#formEditDetails').empty();
        var form = 
            '<div class="container mt-5" id="addMemberForm_2_Div" style="width: 100%; max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">' +
                '<form id="addMemberForm_2">' +
                    '<div style="display: flex; justify-content: space-between;">' +
                        '<h2>Edit Member Form</h2>' +
                        '<div class="alert alert-danger close_edit_committee_member_details">' +
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="name" class="form-label">Name:</label>' +
                        '<input type="text" class="form-control" id="name" name="name" value="' + member_name + '" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="position" class="form-label">Committee Position:</label>' +
                        '<input type="text" class="form-control" id="position" name="position" value="' + member_position + '" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="mobile" class="form-label">Tel Number:</label>' +
                        '<input type="text" class="form-control" id="mobile" name="mobile" value="' + member_tel + '" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="email" class="form-label">Work Email:</label>' +
                        '<input type="email" class="form-control" id="email" name="email" value="' + member_email + '" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="rank" class="form-label">Organization Position:</label>' +
                        '<input type="text" class="form-control" id="rank" name="rank" value="' + member_rank + '" required>' +
                        '<input type="hidden" id="table_name" name="table_name" value="' + tableName + '">' +
                    '</div>' +
                    '<div>' +
                        '<button type="button" class="btn btn-secondary" id="backButtonForm_2">Back</button>' +
                        '<button type="submit" class="btn btn-primary" data-member-uuid="' + member_uuid + '" data-table-name="' + tableName + '" id="submitNewMemeber_2">Submit</button>' +
                    '</div>' +
                '</form>'+
            '</div>';

        $('#formEditDetails').append(form);
    });

    // Handle form submission
    $('#formEditDetails').on('submit', '#addMemberForm_2', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = $(this).serializeArray();
        var member_uuid = $('#submitNewMemeber_2').data('member-uuid');
        var table_name = $('#submitNewMemeber_2').data('table-name'); // Assuming the table name is stored here
    
        formData.push({ name: 'member_uuid', value: member_uuid });
        formData.push({ name: 'table_name', value: table_name });

        $('#submitNewMemeber_2').prop('disabled', true);
        $('#submitNewMemeber_2').text('updating...');

        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=UpdateCommitteeMember',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Member details updated successfully');
                
                $("#listedCommitteesHeader").show();
                $(".clickedCommitteeTableDiv_1").show();
                $("#formEditDetails").hide();
                
                $('#submitNewMemeber_2').prop('disabled', false);
                $('#submitNewMemeber_2').text('Submit');

                fetchCommitteeTable(table_name);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while updating member details');
            }
        });
    });

    function fetchCommitteeTable(table_name) {
        $("#listedCommitteesBody").hide();
        $(".clickedCommitteeTableDiv_1").show();
        $('#listedCommitteesHeader').empty();
        $(".committee_members .date").hide();
        $('#activate_or_deactivate').empty();
    
        var upload_id = table_name;
        var tableName = upload_id
    
        // Split the string into an array of words using the underscore as the delimiter
        var words = table_name.split('_');
        
        // Check if the word "committee" appears more than once
        var committeeCount = words.filter(function(word) {
            return word.toLowerCase() === 'committee';
        }).length;
        
        // If "committee" appears more than once, remove the first occurrence
        if (committeeCount > 1) {
            var firstIndex = words.findIndex(function(word) {
                return word.toLowerCase() === 'committee';
            });
            words.splice(firstIndex, 1);
        }
        
        // Capitalize the first letter of each word and join them back together with a space
        var formattedTableName = words.map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');
    
        var newHeaderRow =
            '<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">' +
                '<div>' +
                    '<h2 style="margin-left: 15px;">Table : ' + formattedTableName + '</h2>' +
                '</div>' +
                '<div class="alert alert-danger close_committee_members">' +
                    '<button type="button" class="btn-close" aria-label="Close"></button>' +
                '</div>' +
            '</div>';
        $('#listedCommitteesHeader').append(newHeaderRow);
    
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=FetchClickedCommitteeTable&page=' + currentPage + '&itemsPerPage=' + itemsPerPage + '&upload_id=' + encodeURIComponent(upload_id),
            type: 'GET',
            success: function (data) {
                if (!data) {
                    console.error('Data is undefined or null');
                    return;
                }
    
                if (typeof data !== 'object') {
                    console.error('Data is not an object:', data);
                    return;
                }
                if (Array.isArray(data.items) && data.items.length > 0) {
                    // Get the values of the object (array of ideas)
                    var dataArray = data.items;
    
                    // Clear the existing data in the table
                    $('#committeeTableViewBody').empty();
            
                    var counter = (currentPageNewCommitteeNewGrid - 1) * itemsPerPageNewCommitteeNewGrid + 1;
    
                    
                    var status_and_add_button;
                    if (data.status === 'active') {

                        var status_and_add_button =
                            '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                                '<span class="badge bg-danger" style="font-size: 15px;" data-table-name="' + tableName + '" id="deactivateTable">Deactivate</span>'+
                                '<span class="badge bg-primary" style="font-size: 15px;" data-table-name="' + tableName + '" id="addMember_2">Add Member</span>' +
                            '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    } else {
                        
                        var status_and_add_button =
                        '<div style="display: flex; flex-direction: row; gap: 5px; align-items: center;">' +
                            '<span class="badge bg-success" style="font-size: 15px;" data-table-name="' + tableName + '" id="activateTable">Activate</span>'+
                        '</div>';

                        $('#activate_or_deactivate').append(status_and_add_button);
                    }

                    // Iterate through the dataArray
                    dataArray.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingNewCommitteeNewGrid.indexOf(idea.id);
                        if (index > -1) {
                            existingNewCommitteeNewGrid.splice(index, 1);
                        }
    
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr>' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.name + '</td>' +
                                '<td>' + idea.position + '</td>' +
                                '<td>' + idea.tel + '</td>' +
                                '<td>' + idea.email + '</td>' +
                                '<td>' + idea.rank + '</td>' +
                                '<td>' + idea.gender + '</td>' +
                                '<td>'+
                                    '<div class="view_btn" data-member-uuid="' + idea.uuid + '" data-table-name="' + tableName + '" data-member-name = "' + idea.name + '" data-member-position = "' + idea.position + '" data-member-tel = "' + idea.tel + '" data-member-email = "' + idea.email + '" data-member-rank = "' + idea.rank + '" id="editMembersDetails">Edit&nbsp;details</div>'+
                                    '<div class="view_btn" data-total-counter="' + data.totalRows + '" data-previous-counter="' + (counter - 1) + '" data-member-uuid="' + idea.uuid + '" data-table-name="' + tableName + '" id="removeMemberDetails">Remove&nbsp;details</div>'+
                                '</td>' +
                            '</tr>';
                        $('#committeeTableViewBody').append(newRow);
    
            
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingNewCommitteeNewGrid.push(idea.id);
            
                        counter++;
                    });
            
                    var counter = counter - 1;
            
                    var footerCounter =
                        '<tr>' +
                        '<td colspan="9">Total ' + counter + ' of '+ data.totalRows +'</td>' +
                        '</tr>';
            
                    $('#committeeTableViewBodyFooter').empty().append(footerCounter);
            
                    // Add pagination controls
                    addPaginationControlsNewCommitteeGrid(data.totalPages);
                }
                else{
                    
                    // Handle empty data
                    $('#committeeTableViewBody').empty().append('<tr><td colspan="9" style="text-align: center; font-weight: bolder;">No Members found</td></tr>');
                    $('#committeeTableViewBodyFooter').empty().append('<tr><td colspan="9">Total 0</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_1').prop('disabled', false);
                $('#submitNewMemeber_1').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    }

    //close edit committee member form
    $('#formEditDetails').on('click', '.close_edit_committee_member_details, #backButtonForm_2', function () {   
        $("#listedCommitteesHeader").show();
        $(".clickedCommitteeTableDiv_1").show();
        $("#formEditDetails").hide();
    });

    //add member second form
    $('.clickedCommitteeTableDiv_1').on('click', '#addMember_2', function () {
        $("#listedCommitteesHeader").hide();
        $(".clickedCommitteeTableDiv_1").hide();
        $("#formEditDetails").show();

        
        var tableName = $(this).data('table-name');

        $('#formEditDetails').empty();
        var form =
            '<div class="container mt-5" id="addMemberForm_3_Div" style="width: 100%; max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">' +
                '<form id="addMemberForm_3">' +
                    '<div style="display: flex; justify-content: space-between;">' +
                        '<h2>Add Committee Members</h2>' +
                        '<div class="alert alert-danger close_add_committee_member_3">' +
                            '<button type="button" class="btn-close" aria-label="Close"></button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="name" class="form-label">Name:</label>' +
                        '<input type="text" class="form-control" id="name" name="name" placeholder="eg. Eng. John Doe" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="position" class="form-label">Committee Position:</label>' +
                        '<input type="text" class="form-control" id="position" name="position" placeholder="eg. Chairperson" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="mobile" class="form-label">Tel Number:</label>' +
                        '<input type="text" class="form-control" id="mobile" name="mobile" placeholder="eg. 0712345678 / 011234567" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="email" class="form-label">Work Email:</label>' +
                        '<input type="email" class="form-control" id="email" name="email" placeholder="eg. johndoe@kenha.co.ke" required>' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="rank" class="form-label">Organization Position:</label>' +
                        '<input type="text" class="form-control" id="rank" name="rank" placeholder="eg. Deputy Director RI&KM" required>' +
                        '<input type="hidden" id="table_name" name="table_name" data-table-name="' + tableName + '">' +
                    '</div>' +
                    '<div class="mb-3">' +
                        '<label for="gender" class="form-label">Gender:</label>' +
                        '<select class="form-select" id="gender" name="gender" required>' +
                            '<option value="" selected disabled>Select gender</option>' +
                            '<option value="Male">Male</option>' +
                            '<option value="Female">Female</option>' +
                        '</select>' +
                    '</div>' +
                    '<div>' +
                        '<button type="button" class="btn btn-secondary" id="backButtonForm_3">Back</button>' +
                        '<button type="submit" class="btn btn-primary" data-table-name="' + tableName + '" id="submitNewMemeber_3">Submit</button>' +
                    '</div>' +
                '</form>' +
            '</div>' ;


        $('#formEditDetails').append(form);
    });
    
    //handle submission for the second form
    $('#formEditDetails').on('submit', '#addMemberForm_3', function(event) {
        event.preventDefault();

        var table_name = $('#submitNewMemeber_3').data('table-name');
        
        console.log(this);

        // If clicked, disable the button
        $('#submitNewMemeber_3').prop('disabled', true);
        $('#submitNewMemeber_3').text('adding...');

        console.log(table_name);

        // Get form data
        var formData = {
            name: $('#name').val(),
            position: $('#position').val(),
            mobile: $('#mobile').val(),
            email: $('#email').val(),
            rank: $('#rank').val(),
            gender: $('#gender').val()
        };        
    
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=addNewMember_1&table_name=' + encodeURIComponent(table_name),
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.startsWith("Success")) {
                    $('#submitNewMemeber_3').prop('disabled', false);
                    $('#submitNewMemeber_3').text('Submit');
                    $("#addMemberForm_1")[0].reset();
                    alert(response);
                    $('#formEditDetails').hide();
                    fetchCommitteeTable(table_name);

                    //FetchNewCommitteeTable();
                } else {
                    $('#submitNewMemeber_3').prop('disabled', false);
                    $('#submitNewMemeber_3').text('Submit');
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_3').prop('disabled', false);
                $('#submitNewMemeber_3').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    });
    
    //close add committee member form
    $('#formEditDetails').on('click', '.close_add_committee_member_3, #backButtonForm_3', function () {   
        $("#listedCommitteesHeader").show();
        $(".clickedCommitteeTableDiv_1").show();
        $("#formEditDetails").hide();
    });

    //deactivate committee
    $('#activate_or_deactivate').on('click', '#deactivateTable', function () {

        
        var table_name = $(this).data('table-name');
        var status = 'inactive';
                
    
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=changeTableStatus&table_name=' + encodeURIComponent(table_name) + '&status=' + encodeURIComponent(status),
            type: 'GET',
            success: function(response) {
                if (response.startsWith("Success")) {
                    alert(response);
                    fetchCommitteeTable(table_name);

                } else {
                    $('#submitNewMemeber_3').prop('disabled', false);
                    $('#submitNewMemeber_3').text('Submit');
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_3').prop('disabled', false);
                $('#submitNewMemeber_3').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    });

    //deactivate committee
    $('#activate_or_deactivate').on('click', '#activateTable', function () {

        
        var table_name = $(this).data('table-name');
        var status = 'active';
                
    
        // Send AJAX request
        $.ajax({
            url: '/KeNHAVATE/fetch-responses?action=changeTableStatus&table_name=' + encodeURIComponent(table_name) + '&status=' + encodeURIComponent(status),
            type: 'GET',
            success: function(response) {
                if (response.startsWith("Success")) {
                    alert(response);
                    fetchCommitteeTable(table_name);

                } else {
                    $('#submitNewMemeber_3').prop('disabled', false);
                    $('#submitNewMemeber_3').text('Submit');
                    alert(response);
                }
            },
            error: function(xhr, status, error) {
                $('#submitNewMemeber_3').prop('disabled', false);
                $('#submitNewMemeber_3').text('Submit');
                // Handle error
                console.error(xhr.responseText);
                alert(response);
            }
        });
    });

    

    














    function startUpdate() {
        UpdateTableChallenges();
        FirstIdeaTable();
        SecondIdeaTable();
        CardUnallocatedIdeas();
        CardTotalIdeas();
        CardAllocatedIdeas();
        CardReviewedIdeas();
        CardCommiteeIdeas();
        CardBoardAllIdeas();
        CardBoardRejectedIdeas();
        ReviewedIdeaTable();
        UnreviewedIdeaTable();
        EscalateIdeaTable();
        ApproveIdeaTable();
        RejectIdeaTable();
        PendingIdeaTable();
        ApprovedIdeaTable();
        RejectedIdeaTable();
        EscalateBoardIdeaTable();
        ApproveBoardIdeaTable();
        RejectBoardIdeaTable();
        PendingBoardIdeaTable();
        ApprovedBoardIdeaTable();
        RejectedBoardIdeaTable();
        FetchNewCommitteeTable();
        ListExistingCommittee();
    }
    
    
    startUpdate();

    
    setInterval(UpdateTableChallenges, 60000);
    setInterval(FirstIdeaTable, 60000);
    setInterval(SecondIdeaTable, 60000);
    setInterval(get_Rows, 60000);
    setInterval(ReviewedIdeaTable, 60000);
    setInterval(UnreviewedIdeaTable, 60000);
    setInterval(EscalateIdeaTable, 60000);
    setInterval(ApproveIdeaTable, 60000);
    setInterval(RejectIdeaTable, 60000);
    setInterval(PendingIdeaTable, 60000);
    setInterval(ApprovedIdeaTable, 60000);
    setInterval(RejectedIdeaTable, 60000);
    setInterval(EscalateBoardIdeaTable, 60000);
    setInterval(ApproveBoardIdeaTable, 60000);
    setInterval(RejectBoardIdeaTable, 60000);
    setInterval(PendingBoardIdeaTable, 60000);
    setInterval(ApprovedBoardIdeaTable, 60000);
    setInterval(RejectedBoardIdeaTable, 60000);
    setInterval(FetchNewCommitteeTable, 60000);
});