

        <div id="payment-tab" class="content is-hidden">
        </div>


         <!--
        <div id="payment-tab" class="content is-hidden">
          <div class="box">
            <h1 class="title has-text-centered">Add Payment Method for Donation</h1>
            <form action="action/org_dashboard.php" method="POST">
              <div class="field">
                <label for="" class="label">Payment Method</label>
                <div class="control">
                  <div class="select">
                    <select name="addPayment" id="">
                      <option>Select Payment Method</option>
                      <option value="gcash">GCash</option>
                      <option value="maya">Maya</option>
                      <option value="paypal">PayPal</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Name</label>
                <div class="control">
                  <input type="text" class="input" name="addName">
                </div>
              </div>

              <div class="field">
                <label for="" class="label">Email (Paypal) or Phone Number (GCash, Maya) </label>
                <div class="control">
                  <input type="text" class="input" name="addDetails">
                </div>
              </div>

              <button class="button is-info" type="submit" name="addPaymentSubmit">Add Payment</button>
            </form>
          </div>
          <div class="box">
            <h1 class="title has-text-centered">Delete Payment Method for Donation</h1>
            <form method="POST" id="deletePaymentForm">

              <div class="field">
                <label for="" class="label">Payment Method Account List</label>
                <div class="control">
                  <div class="select">
                    <select name="delPaymentMethod" id="paymentSelect">
                      <option>Select Payment Account</option>
                      <?php 
                      /*
                        $payQuery = "SELECT * FROM `tblpayments` WHERE org_id = '$id'";
                        $payResult = mysqli_query($conn, $payQuery);

                        if ($payResult->num_rows > 0) {
                          while ($payacc = mysqli_fetch_assoc($payResult)) {
                              $accrow = json_decode($payacc["account_details"], true);

                          ?>
                        <option value="<?php echo $payacc["payment_id"]; ?>">
                          <?php echo "[" . $payacc["payment_id"] . "] (" . $payacc["method_type"] . ") " . $accrow['account_name'] . " : " . $accrow['account_value']; ?>
                        </option>
                      <?php
                          }
                        }*/
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <button class="button is-info" type="submit" name="delPaymentSubmit">Delete Payment</button>
            </form>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const form = document.getElementById('deletePaymentForm');
              const select = document.getElementById('paymentSelect');

              form.addEventListener('submit', (e) => {
                e.preventDefault();
                const selectedOption = select.value;

                if (selectedOption === 'Select Payment Account') {
                  Swal.fire('Error', 'Please select a payment account.', 'error');
                  return;
                }

                Swal.fire({
                  title: 'Confirmation',
                  text: 'Are you sure you want to delete this payment account?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!',
                  cancelButtonText: 'Cancel',
                }).then((result) => {
                  if (result.isConfirmed) {
                    // Create a new FormData object
                    const formData = new FormData();
                    formData.append('delPaymentMethod', selectedOption);

                    // Perform the deletion request using Axios
                    axios.post('action/org_dashboard.php', formData)
                      .then((response) => {
                        // Handle the response if needed
                        Swal.fire('Success', 'Payment account deleted successfully.', 'success');
                        const { success, message } = response.data;
                        if (success) {
                          Swal.fire('Success', 'Payment account deleted successfully.', 'success');
                        } else {
                          Swal.fire('Error', message, 'error');
                        }
                      })
                      .catch((error) => {
                        // Handle any errors
                        Swal.fire('Error', 'An error occurred while deleting the payment account.', 'error');
                      });
                  }
                });
              });
            });
          </script>
        </div>
  -->