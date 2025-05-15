<?php
require "Config/session.php";
require "Config/connect.php";
if (!isset($_COOKIE['user'])) {
  header("Location: login2.0.php");
  exit();
}

$page_name="Transaction";
require "Required/Header.php";
?>

    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Transactions List</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Method</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount (RM)</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reference ID</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT 
                        t.*,
                        c.card_brand,
                        u.name,
                        u.email
                    FROM transactions t
                    JOIN cards c ON t.card_id = c.card_id
                    JOIN users u ON t.user_id = u.user_id;";
                    $stmt = sqlsrv_query($conn, $query);
                    if($stmt){
                      while($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['transaction_id'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $data['name'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['card_brand'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $data['amount'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">
                            <?php 
                                echo is_null($data['transaction_date']) 
                                    ? 'null' 
                                    : $data['transaction_date']->format('d/m/y');
                            ?>
                            </h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        <p class="text-xs font-weight-bold mb-0"><?php echo $data['transaction_reference'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <?php
                          if($data['transaction_status'] == 'success'){
                            echo '<span class="badge badge-sm bg-gradient-success data-id="'.$data['transaction_id'].'" data-name="success"">Success</span>';
                          }else if($data['transaction_status'] == 'failed'){
                            echo '<span class="badge badge-sm change bg-gradient-danger" data-id="'.$data['transaction_id'].'" data-name="failed">failed</span>';
                          }else if($data['transaction_status'] == 'pending'){
                            echo '<span class="badge badge-sm change bg-gradient-warning" data-id="'.$data['transaction_id'].'" data-name="pending" >pending</span>';
                          }
                        ?>
                        
                      </td>
                      
                    </tr>
                    <?php
                      }
                    }
                   ?>
                   
                    
                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    
    
    </div>
 
<?php

$script = <<<EOF
 $(document).on('dblclick', '.change', function() {
 var dataName = $(this).data('name');
 var dataId = $(this).data('id'); 
 var select = $('<select class="badge badge-sm select change text-bg-dark" data-id="' + dataId + '" data-name="'+dataName+'">')
                  .append('<option value="success">Success</option>')
                  .append('<option value="failed">Failed</option>')
                  .append('<option value="pending">Pending</option>');
                  if (dataName === 'pending') {
            select.addClass('bg-gradient-warning');  // Set bg-gradient-warning if the default is 'pending'
        } else if (dataName === 'success') {
            select.addClass('bg-gradient-success');  // Set bg-gradient-success if the default is 'success'
        } else if (dataName === 'failed') {
            select.addClass('bg-gradient-danger');  // Set bg-gradient-danger if the default is 'failed'
        }

  $(this).replaceWith(select);
  if (dataName) {
    select.val(dataName); // Set the value of the select dropdown to match data-name
  }
  select.focus();
});
function sendDataToApi(data) {
}
$(document).on('blur', '.select', function() {
        var element=$(this);
        var selectedValue = $(this).val(); // Get the selected value from the select dropdown
        var dataId = $(this).data('id'); 
        var dataName=$(this).data('name');
        var newSpan;
        var oldSpan =$('<span class="badge badge-sm ' + $(this).attr('class') + '" data-id="'+ dataId +'" data-name="'+ selectedValue +'">' + selectedValue.charAt(0).toUpperCase() + selectedValue.slice(1) + '</span>');
        
        if (selectedValue === dataName) {     
            $(this).replaceWith(oldSpan);
            return;
        }

        // Create a new span element based on the selected value
        if (selectedValue === 'success') {
            newSpan = $('<span class="badge badge-sm bg-gradient-success" data-id="'+dataId+'" data-name="success">Success</span>');
        } else if (selectedValue === 'failed') {
            newSpan = $('<span class="badge badge-sm change bg-gradient-danger" data-id="'+dataId+'" data-name="failed">Failed</span>');
        } else if (selectedValue === 'pending') {
            newSpan = $('<span class="badge badge-sm change bg-gradient-warning" data-id="'+dataId+'" data-name="pending">Pending</span>');
        }
        var fd = new FormData();
        fd.append('id',dataId);
        fd.append('status',selectedValue);

        $.ajax({
          url:      'API/changeStatus.php',
          type:     'POST',
          dataType: 'json',
          data:     fd,
          processData: false,
          contentType: false,
          success: function(responce){
             element.replaceWith(newSpan);
          },
          error: function(responce){
             element.replaceWith(oldSpan);
          }
        });
});
EOF;
require "Required/Footer.php";