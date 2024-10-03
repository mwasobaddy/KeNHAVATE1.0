<div class="container mt-5" style="margin-top: 0px!important; overflow: auto; background-color: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 10px; border-radius: 10px;">
  <h2 style="text-align: center;">My Submissions</h2>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">No.</th>
        <th scope="col">Title</th>
        <th scope="col">Date</th>
        <th scope="col">Stage</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $db_uuid = $_SESSION['uuid'];

        try {
          $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE user_uuid = ?");
          $stmt->bind_param("s", $db_uuid);
          $stmt->execute();
          $result = $stmt->get_result();

          $counter = 1;

          if ($result->num_rows === 0) {
            // No results found, display "Not found" message.
            echo "<tr>";
            echo "<td colspan='8' style='text-align: center; font-size: 16px; font-weight: bolder;'>No pending ideas!!</td>";
            echo "</tr>";
          } else {
            while ($row = $result->fetch_assoc()) {
              $key = 'my-KeNHAsecret-passkey';
              echo "<tr>";
              echo "<td>" . $counter . "</td>";
              echo "<td>" . $row['title'] . "</td>";
              echo "<td>" . $row['day_user_uploaded'] . "</td>";
              echo "<td>" . decrypt($row['stage'], $key) . "</td>";
              echo "<td>" . decrypt($row['status'], $key) . "</td>";
              echo "</tr>";

              $counter++;
            }
            
            // Set the total_count session variable outside the loop
            $_SESSION['total_count'] = $counter - 1;
          }
          $stmt->close();
        } catch (Exception $e) {
          // Handle any exceptions that may occur during database operations
          $_SESSION['error_message'] = "An error occurred: " . $e->getMessage();
        }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="7">Total submissions
          <?php
            if(isset($_SESSION['total_count'])) {
              echo $_SESSION['total_count'];
              unset($_SESSION['total_count']);
            }
            else {
              echo "0";
            }
          ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>