<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pie Chart Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Pie Chart Canvas -->
                <canvas id="myPieChart"></canvas>
            </div>
            <div class="col-md-6">
                <!-- Voting Form -->
                <form id="votingForm">
                    <div class="mb-3">
                        <label for="vote" class="form-label">Vote</label>
                        <select class="form-select" id="vote" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Vote</button>
                </form>
            </div>
        </div>
    </div>

    <?php
        
        $yes_vote = "5";
        $yes_vote = "5";
        
        $yes_vote = $yes_vote + $yes_vote;

        echo $yes_vote;
    ?>




    <p>Upload ID: <span id="modalUploadId"></span></p>
    <iframe src="https://docs.google.com/viewer?url=/uploaded_ideas/Mutual.pdf&embedded=true" width="100%" height="600" frameborder="0"></iframe>









    <script>
        // Chart.js initialization
        const ctx = document.getElementById('myPieChart').getContext('2d');
        const myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Yes', 'No'],
                datasets: [{
                    data: [0, 0], // Initialize with initial vote counts
                    backgroundColor: ['#36a2eb', '#ff6384'],
                }]
            }
        });

        // Voting Form submission
        const votingForm = document.getElementById('votingForm');
        votingForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const selectedVote = document.getElementById('vote').value;

            // Update pie chart data
            const currentData = myPieChart.data.datasets[0].data;
            if (selectedVote === 'yes') {
                currentData[0]++;
            } else if (selectedVote === 'no') {
                currentData[1]++;
            }
            myPieChart.update();
        });
    </script>
</body>
</html>
