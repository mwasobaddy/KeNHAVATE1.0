<div class="main_content">
        <div class="header_wrapper">
            <div class="header_title">
                <div class="menu_icon"><i class="fa-solid fa-bars"></i></div>
                <div class="menu_content">
                    <span>Subject Matter</span>
                    <h2>Expert</h2>
                </div>
            </div>
            <div class="user_info">
                <div class="search_box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="search">
                </div>
                <img src="./img/climate.jpg" alt="">
            </div>
        </div>



        <div class="card_container home_cards active">
            <div class="date">
                <h3 class="main_title">Data Today</h3>
                <p><?php echo date('D, F j, Y'); ?></p>
            </div>
            <div class="card_wrapper">
                <div class="payment_card light_yellow">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Your Total Pending Reviews
                            </span>
                            <span class="amount_value" id="totalPending">
                            </span>
                        </div>
                        <i class="fas fa-chart-bar icon"></i> 
                    </div>
                    <span class="card_details">Total Ideas Reviewed by You:<span id="totalCommitted"></span></span>
                </div>
                <div class="payment_card light_grey">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Total Reviews Assigned to You
                            </span>
                            <span class="amount_value" id="totalIdeas">
                            </span>
                        </div>
                        <i class="fas fa-exclamation-circle icon"></i>
                    </div>
                    <span class="card_details">Total Ideas Pending: 5,555</span>
                </div>
            </div>
        </div>

        <div class="tabular_wrapper all_allocated_ideas">
            <div class="date">
                <h3 class="main_title">All Allocated Ideas</h3>
            </div>
            <div class="table_container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Day Assigned</th>
                            <th>Day Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pendingIdeasTable">
                    </tbody>
                    <tfoot id="pendingIdeasTableFooter">
                    </tfoot>
                </table>
            </div>
        </div>

        <div id="ideaDetailsContainer" class="ideaDetailsContainer" style="position: relative; background-color: #fff; margin-top: 1rem; border-radius: 10px; padding: 2rem; display: none; max-height: fit-content; overflow: hidden;">
        </div>

        <div class="tabular_wrapper all_pending_ideas">
            <div class="date">
                <h3 class="main_title">All Pending Ideas</h3>
            </div>
            <div class="table_container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Day Assigned</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="allocatedIdeasTable">
                    </tbody>
                    <tfoot id="allocatedIdeasTableFooter">
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="tabular_wrapper all_committed_ideas">
            <div class="date">
                <h3 class="main_title">Committed Ideas</h3>
            </div>
            <div class="table_container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Day Assigned</th>
                            <th>Day Committed</th>
                            <th>Comment Type</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="committedIdeasTable">
                    </tbody>
                    <tfoot id="committedIdeasTableFooter">
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="tabular_wrapper all_history_ideas">
            <div class="date">
                <h3 class="main_title">Your Ideas History</h3>
            </div>
            <div class="table_container">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Problem Statement/Title</th>
                            <th>Innovation Area</th>
                            <th>Time Assigned</th>
                            <th>Time Committed</th>
                            <th>Comment Type</th>
                            <th>Comment</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="historyIdeasTable">
                    </tbody>
                    <tfoot id="historyIdeasTableFooter">
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="container mt-5 message_dd" style="background-color: #fff; border-radius: 10px; padding: 2rem;"> 
            <h2>Send Message to the Deputy Director R&I</h2>
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
        


    </div>