$(document).ready(function () {

    var intervalId;

    //fetch total number of ideas
    function updateTotalIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=totalIdeas', // Replace with the actual server endpoint
            type: 'GET',
            success: function(data) {
                var dataArray = data[0]; // Access the first (and only) element of the array
                var TotalIdeas = dataArray.split('?')[0]; // Split by '?' and get the first part
                $('#totalIdeas').text(TotalIdeas);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    //fetch total number of pending ideas
    function updatePendingIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=pendingIdeas', // Replace with the actual server endpoint
            type: 'GET',
            success: function(data) {
                var dataArray1 = data[0]; // Access the first (and only) element of the array
                var TotalIdeas1 = dataArray1.split('?')[0]; // Split by '?' and get the first part

                // Update the respective HTML element with the extracted value
                $('#totalPending').text(TotalIdeas1);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    
    //fetch total number of committed ideas
    function updateCommittedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=committedIdeas', // Replace with the actual server endpoint
            type: 'GET',
            success: function(data) {
                var dataArray2 = data[0]; // Access the first (and only) element of the array
                var TotalIdeas2 = dataArray2.split('?')[0]; // Split by '?' and get the first part

                // Update the respective HTML element with the extracted value
                $('#totalCommitted').text(TotalIdeas2);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    //fetch and update table pending ideas
    // Store the existing idea UUIDs in an array
    var existingPendingIdeaUUIDs = [];

    function UpdateTablePendingIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=updateTablePendingIdeas',
            type: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('#allocatedIdeasTable').empty().append('<tr><td colspan="8" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#allocatedIdeasTableFooter').empty().append('<tr><td colspan="8">Total 0</td></tr>');
                } else {
                    // Clear the existing data in the table
                    $('#allocatedIdeasTable').empty();

                    var counter = 1;

                    // Iterate through the data
                    data.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingPendingIdeaUUIDs.indexOf(idea.upload_id);
                        if (index > -1) {
                            existingPendingIdeaUUIDs.splice(index, 1);
                        }

                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr data-idea-uuid="' + idea.upload_id + '">' +
                            '<td>' + counter + '</td>' +
                            '<td>' + idea.title + '</td>' +
                            '<td>' + idea.innovation_area + '</td>' +
                            '<td>' + idea.day_expert_appointed + '</td>' +
                            '<td>' +
                            '<a href="view_doc?file=' + idea.fileName + '" target="_blank" class="view_btn">view</a>' +
                            '<a class="comment_btn" data-idea-uuid="' + idea.upload_id + '">comment</a>' +
                            '</td>' +
                            '</tr>';
                        $('#allocatedIdeasTable').append(newRow);

                        // Add the idea UUID to the array of existing idea UUIDs
                        existingPendingIdeaUUIDs.push(idea.upload_id);

                        counter++;
                    });

                    var counter = counter - 1;

                    var footerCounter =
                        '<tr>' +
                        '<td colspan="8">Total ' + counter + '</td>' +
                        '</tr>';

                    $('#allocatedIdeasTableFooter').empty().append(footerCounter);

                    // Attach an event handler to the comment button for each new comment_btn
                    $('.comment_btn').off('click').on('click', function () {
                        // Extract the idea UUID from the data-idea-uuid attribute
                        var ideaUUID = $(this).data('idea-uuid');
                        // Fetch the idea details associated with this UUID using an AJAX request
                        $.ajax({
                            url: '/KeNHAVATE/fetch-sme-values?action=getIdeaDetails&ideaUUID=' + ideaUUID,
                            type: 'GET',
                            success: function (ideaDetails) {
                                $('#ideaDetailsContainer').css('display', 'flex').html(ideaDetails);
                                $('.all_pending_ideas').removeClass('active');
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    //fetch and update table allocated ideas
    // Store the existing idea UUIDs in an array
    var existingIdeaUUIDs = [];

    function UpdateTableAllocatedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=updateTableAllocatedIdeas',
            type: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('#pendingIdeasTable').empty().append('<tr><td colspan="8" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#pendingIdeasTableFooter').empty().append('<tr><td colspan="8">Total 0</td></tr>');
                } else {
                    var counter = 1;

                    // Iterate through the data
                    data.forEach(function (idea) {
                        // Check if the idea already exists in the table
                        if (existingIdeaUUIDs.includes(idea.upload_id)) {
                            // Update the existing row if the idea is already in the table
                            var row = $('#pendingIdeasTable').find('tr[data-idea-uuid="' + idea.upload_id + '"]');
                            row.find('td:eq(0)').text(counter);
                            row.find('td:eq(1)').text(idea.title);
                            row.find('td:eq(2)').text(idea.innovation_area);
                            row.find('td:eq(3)').text(idea.day_expert_appointed);
                            row.find('td:eq(4)').text(idea.day_expert_committed);
                            row.find('td:eq(5) label').attr('class', idea.status + '_sme').text(idea.status);
                            row.find('.view_btn').attr('href', 'view_doc?file=' + idea.fileName);
                        } else {
                            // Generate a new row and append it to the table
                            var newRow =
                                '<tr data-idea-uuid="' + idea.upload_id + '">' +
                                '<td>' + counter + '</td>' +
                                '<td>' + idea.title + '</td>' +
                                '<td>' + idea.innovation_area + '</td>' +
                                '<td>' + idea.day_expert_appointed + '</td>' +
                                '<td>' + idea.day_expert_committed + '</td>' +
                                '<td><label class="' + idea.status + '_sme">' + idea.status + '</label></td>' +
                                '<td>' +
                                '<a href="view_doc?file=' + idea.fileName + '" target="_blank" class="view_btn">view</a>' +
                                '</td>' +
                                '</tr>';
                            $('#pendingIdeasTable').append(newRow);
                        }

                        // Add the idea UUID to the array of existing idea UUIDs
                        existingIdeaUUIDs.push(idea.upload_id);

                        counter++;
                    });

                    var counter = counter - 1;

                    var footerCounter =
                        '<tr>' +
                        '<td colspan="8">Total ' + counter + '</td>' +
                        '</tr>';

                    $('#pendingIdeasTableFooter').empty().append(footerCounter);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }


    //fetch and update table committed ideas
    // Store the existing idea UUIDs in an array
    var existingCommittedIdeaUUIDs = [];

    function UpdateTableCommittedIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=updateTableCommittedIdeas',
            type: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('#committedIdeasTable').empty().append('<tr><td colspan="8" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#committedIdeasTableFooter').empty().append('<tr><td colspan="8">Total 0</td></tr>');
                } else {
                    $('#committedIdeasTable').empty();
                    var counter = 1;

                    // Iterate through the data
                    data.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingCommittedIdeaUUIDs.indexOf(idea.upload_id);
                        if (index > -1) {
                            existingCommittedIdeaUUIDs.splice(index, 1);
                        }
    
                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr data-idea-uuid="' + idea.upload_id + '">' +
                            '<td>' + counter + '</td>' +
                            '<td>' + idea.title + '</td>' +
                            '<td>' + idea.innovation_area + '</td>' +
                            '<td>' + idea.day_expert_appointed + '</td>' +
                            '<td>' + idea.day_expert_committed + '</td>' +
                            '<td>' + idea.comment_type + '</td>' +
                            '<td>' + idea.comment_text + '</td>' +
                            '<td>' +
                            '<a href="view_doc?file=' + idea.fileName + '" target="_blank" class="view_btn">view</a>' +
                            '</td>' +
                            '</tr>';
                        $('#committedIdeasTable').append(newRow);
    
                        // Add the idea UUID to the array of existing idea UUIDs
                        existingCommittedIdeaUUIDs.push(idea.upload_id);
    
                        counter++;
                    });

                    var counter = counter - 1;

                    var footerCounter =
                        '<tr>' +
                        '<td colspan="8">Total ' + counter + '</td>' +
                        '</tr>';

                    $('#committedIdeasTableFooter').empty().append(footerCounter);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }


    //fetch and update table history ideas
    // Store the existing idea UUIDs in an array

    var existingHistoryIdeaUUIDs = [];

    function UpdateTableHistoryIdeas() {
        $.ajax({
            url: '/KeNHAVATE/fetch-sme-values?action=updateTableHistoryIdeas',
            type: 'GET',
            success: function (data) {
                if (data.length === 0) {
                    $('#historyIdeasTable').empty().append('<tr><td colspan="8" style="text-align: center; font-weight: bolder;">No results found</td></tr>');
                    $('#historyIdeasTableFooter').empty().append('<tr><td colspan="8">Total 0</td></tr>');
                } else {
                    // Clear the existing data in the table
                    $('#historyIdeasTable').empty();

                    var counter = 1;

                    // Iterate through the data
                    data.forEach(function (idea) {
                        // Remove the idea from the array if it has been deleted
                        var index = existingHistoryIdeaUUIDs.indexOf(idea.upload_id);
                        if (index > -1) {
                            existingHistoryIdeaUUIDs.splice(index, 1);
                        }

                        // Generate a new row and append it to the table
                        var newRow =
                            '<tr data-idea-uuid="' + idea.upload_id + '">' +
                            '<td>' + counter + '</td>' +
                            '<td>' + idea.title + '</td>' +
                            '<td>' + idea.innovation_area + '</td>' +
                            '<td>' + idea.day_expert_appointed + '</td>' +
                            '<td>' + idea.day_expert_committed + '</td>' +
                            '<td>' + idea.comment_type + '</td>' +
                            '<td>' + idea.comment_text + '</td>' +
                            '<input type="hidden" class="idea-uuid" value="' + idea.upload_id + '">' +
                            '<td>' +
                            '<a href="view_doc?file=' + idea.fileName + '" target="_blank" class="view_btn">view</a>' +
                            '</td>' +
                            '</tr>';
                        $('#historyIdeasTable').append(newRow);

                        // Add the idea UUID to the array of existing idea UUIDs
                        existingHistoryIdeaUUIDs.push(idea.upload_id);

                        counter++;
                    });

                    var counter = counter - 1;

                    var footerCounter =
                        '<tr>' +
                        '<td colspan="8">Total ' + counter + '</td>' +
                        '</tr>';

                    $('#historyIdeasTableFooter').empty().append(footerCounter);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr);
                console.error(status);
                console.error(error);
            }
        });
    }


    

    // Event listener for the "submit comment" button
    $(document).on("click", "#ideaDetailsContainer button[name='submit_comment']", function (e) {
        e.preventDefault(); // Prevent the default form submission

        

        // Get comment data from the form
        var ideaUUID = $(this).data('idea-uuid');
        var commentType = $("#Comment_type").val();
        var commentText = $("#comment_textarea").val();
        
        // Perform an AJAX request to update_comment.php
        $.ajax({
            type: "POST",
            url: '/KeNHAVATE/fetch-sme-values?action=post_comment',
            data: {
                comment_type: commentType,
                comment_text: commentText,
                idea_uuid: ideaUUID // Pass the idea UUID to identify the idea
            },
            success: function (response) {
                // Handle the response from the server (e.g., show a success message)
                
                updateTotalIdeas();
                updatePendingIdeas();
                updateCommittedIdeas();
                UpdateTablePendingIdeas();
                UpdateTableAllocatedIdeas();
                UpdateTableCommittedIdeas();
                UpdateTableHistoryIdeas();

                $('#allocatedIdeasTable tr:first').remove();

                console.log(response);
                alert(response);

                document.getElementById('ideaDetailsContainer').style.display = 'none';
                document.querySelector('.all_pending_ideas').classList.add('active');

            },
            error: function () {
                // Handle errors (e.g., show an error message)
                alert("Error submitting the comment.");
            }
        });
    });
        

    // Update the content initially
    updateTotalIdeas();
    updatePendingIdeas();
    updateCommittedIdeas();
    UpdateTablePendingIdeas();
    UpdateTableAllocatedIdeas();
    UpdateTableCommittedIdeas();
    UpdateTableHistoryIdeas();

    // Periodically update the content every one minute (60000 milliseconds)
    setInterval(updateTotalIdeas, 60000);
    setInterval(updatePendingIdeas, 60000);
    setInterval(updateCommittedIdeas, 60000);
    setInterval(UpdateTablePendingIdeas, 60000);
    setInterval(UpdateTableAllocatedIdeas, 60000);
    setInterval(UpdateTableCommittedIdeas, 60000);
    setInterval(UpdateTableHistoryIdeas, 60000);
});
