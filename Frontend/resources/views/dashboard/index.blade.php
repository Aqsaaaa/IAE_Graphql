<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Vendor-Delivery-Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            background-color: #0e101c;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
        }

        h3 {
            color: #a3e635;
        }

        .btn-group .btn {
            border-radius: 50px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 12px #22d3ee;
        }

        table.dataTable {
            background-color: #1f2937;
            border-radius: 10px;
            overflow: hidden;
        }

        table.dataTable thead {
            background-color: #374151;
            color: #10b981;
        }

        table.dataTable tbody tr:hover {
            background-color: #2c3e50;
            cursor: pointer;
        }

        .modal-content {
            background-color: #1e293b;
            color: white;
        }

        .modal-header,
        .modal-footer {
            border-color: #4b5563;
        }

        #loading {
            text-align: center;
            padding: 2rem;
            font-size: 1.2rem;
            color: #22d3ee;
        }

        .add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(34, 211, 238, 0.5);
        }

        .form-control, .form-select {
            background-color: #1f2937;
            color: white;
            border: 1px solid #4b5563;
        }

        .form-control:focus, .form-select:focus {
            background-color: #1f2937;
            color: white;
            border-color: #22d3ee;
            box-shadow: 0 0 0 0.25rem rgba(34, 211, 238, 0.25);
        }

        .code-block {
            background-color: #1f2937;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            border: 1px solid #4b5563;
        }

        .copy-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #22d3ee;
            color: #0e101c;
            border: none;
            border-radius: 3px;
            padding: 2px 8px;
            font-size: 12px;
            cursor: pointer;
        }

        .copy-btn:hover {
            background-color: #0ea5e9;
        }

        .doc-section {
            margin-bottom: 30px;
        }

        .doc-section h4 {
            color: #a3e635;
            border-bottom: 1px solid #4b5563;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h3 class="mb-4">Dashboard Vendor-Delivery-Service</h3>

        <div class="btn-group mb-4">
            <button class="btn btn-outline-info" id="btn-vendor">Vendor Requests</button>
            <button class="btn btn-outline-success" id="btn-deliveries">Deliveries</button>
            <button class="btn btn-outline-warning" id="btn-memberships">Memberships</button>
            <button class="btn btn-outline-secondary" id="btn-readme">Read Me</button>
        </div>

        <div id="data-section">
            <div id="loading">Loading data...</div>
        </div>
    </div>

    <!-- Add Data Button -->
    <button class="btn btn-primary add-btn" id="btn-add-data">
        +
    </button>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre class="text-white" id="modal-content-json"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Data Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalTitle">Add New Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="addModalBody">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-add">Add Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentDataType = 'vendor-requests';
        const columnsMap = {
            'vendor-requests': [
                { data: 'id', title: 'ID' },
                { data: 'vendor_id', title: 'Vendor ID' },
                { data: 'ingredient_id', title: 'Ingredient ID' },
                { data: 'quantity', title: 'Quantity' },
                { data: 'status', title: 'Status' },
                { data: 'requested_at', title: 'Request At' },
                { data: 'estimated_arrival', title: 'Estimated Arrival' }
            ],
            'deliveries': [
                { data: 'id', title: 'ID' },
                { data: 'order_id', title: 'Order ID' },
                { data: 'delivery_status', title: 'Status' },
                { data: 'delivery_time', title: 'Time' },
                { data: 'current_location', title: 'Location' }
            ],
            'memberships': [
                { data: 'id', title: 'ID' },
                { data: 'user_id', title: 'User ID' },
                { data: 'points', title: 'Points' },
                { data: 'user.name', title: 'User Name' },
                { data: 'user.phone', title: 'User Phone' }
            ]
        };

        const formTemplates = {
            'vendor-requests': `
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor ID</label>
                    <input type="text" class="form-control" id="vendor_id" required>
                </div>
                <div class="mb-3">
                    <label for="ingredient_id" class="form-label">Ingredient ID</label>
                    <input type="text" class="form-control" id="ingredient_id" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="requested_at" class="form-label">Requested At</label>
                    <input type="datetime-local" class="form-control" id="requested_at" required>
                </div>
                <div class="mb-3">
                    <label for="estimated_arrival" class="form-label">Estimated Arrival</label>
                    <input type="datetime-local" class="form-control" id="estimated_arrival" required>
                </div>
            `,
            'deliveries': `
                <div class="mb-3">
                    <label for="order_id" class="form-label">Order ID</label>
                    <input type="text" class="form-control" id="order_id" required>
                </div>
                <div class="mb-3">
                    <label for="delivery_status" class="form-label">Status</label>
                    <select class="form-select" id="delivery_status" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="delivery_time" class="form-label">Delivery Time</label>
                    <input type="datetime-local" class="form-control" id="delivery_time" required>
                </div>
                <div class="mb-3">
                    <label for="current_location" class="form-label">Current Location</label>
                    <input type="text" class="form-control" id="current_location" required>
                </div>
            `,
            'memberships': `
                <div class="mb-3">
                    <label for="user_id" class="form-label">User ID</label>
                    <input type="text" class="form-control" id="user_id" required>
                </div>
                <div class="mb-3">
                    <label for="points" class="form-label">Points</label>
                    <input type="number" class="form-control" id="points" required>
                </div>
            `
        };

        function loadTable(type) {
            currentDataType = type;
            $('#data-section').html(`<div id="loading">Loading ${type.replace('-', ' ')}...</div>`);

            const url = `/dashboard/${type}`;
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    const columns = columnsMap[type];

                    $('#data-section').html(`
                        <table id="dataTable" class="display table table-bordered text-white w-100">
                            <thead>
                                <tr>${columns.map(col => `<th>${col.title}</th>`).join('')}</tr>
                            </thead>
                        </table>
                    `);

                    const table = $('#dataTable').DataTable({
                        data: data,
                        destroy: true,
                        columns: columns.map(col => ({
                            data: col.data,
                            title: col.title,
                            render: function (data, type, row) {
                                if (col.data.includes('.')) {
                                    return col.data.split('.').reduce((o, k) => (o || {})[k], row) ?? '-';
                                }
                                return data ?? '-';
                            }
                        }))
                    });

                    $('#dataTable tbody').on('click', 'tr', function () {
                        const rowData = table.row(this).data();
                        $('#modal-content-json').text(JSON.stringify(rowData, null, 2));
                        new bootstrap.Modal(document.getElementById('detailModal')).show();
                    });
                },
                error: function (xhr) {
                    $('#data-section').html(`<div class="alert alert-danger">Failed to load data from ${url}</div>`);
                }
            });
        }

        function showReadMe() {
            const readMeContent = `
                <div class="doc-section">
                    <h4>Deliveries Service - GraphQL API Documentation</h4>
                    
                    <h5>Query</h5>
                    <h6>Get all deliveries</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  getAllDeliveries {
    id
    order_id
    delivery_status
    delivery_time
    current_location
  }
}</pre>
                    </div>
                    
                    <h6>Get delivery by id</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  getDelivery(id:1){
    id
    order_id
    delivery_status
    delivery_time
    current_location
  }
}</pre>
                    </div>
                    
                    <h5>Mutations</h5>
                    <p><strong>createDelivery(input: DeliveryInput!): Delivery</strong><br>
                    Create a new delivery record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  createDelivery(input: {
    order_id: 1,
    delivery_status: "assigned",
    delivery_time: "2025-05-22T14:00:00Z",
    current_location: "Warehouse"
  }) {
    id
    order_id
    delivery_status
    delivery_time
    current_location
  }
}</pre>
                    </div>
                    
                    <p><strong>updateDelivery(id: ID!, input: DeliveryInput!): Delivery</strong><br>
                    Update an existing delivery record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  updateDelivery(id: 1, input: {
    delivery_status: "on the way",
    current_location: "On route"
  }) {
    id
    delivery_status
    current_location
  }
}</pre>
                    </div>
                    
                    <p><strong>deleteDelivery(id: ID!): Boolean</strong><br>
                    Delete a delivery record by ID.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  deleteDelivery(id: 1)
}</pre>
                    </div>
                    
                    <h5>Input Types</h5>
                    <p>DeliveryInput:</p>
                    <ul>
                        <li>order_id: Int!</li>
                        <li>delivery_status: String</li>
                        <li>delivery_time: String</li>
                        <li>current_location: String</li>
                    </ul>
                </div>
                
                <div class="doc-section">
                    <h4>Memberships Service - GraphQL API Documentation</h4>
                    
                    <h5>Query</h5>
                    <h6>Get by id</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>query getMembership($id : ID!){
  getMembership(id:$id) {
    id
    user_id
    points
    user{
      id
      name
      phone
    }
  }  
}</pre>
                    </div>
                    <p>Variables:</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  "id" : 1
}</pre>
                    </div>
                    
                    <h6>Get all members</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  getAllMemberships {
    id
    user {
      name
      id
      phone
    }
  }
}</pre>
                    </div>
                    
                    <h5>Mutations</h5>
                    <p><strong>createMembership(input: MembershipInput!): Membership</strong><br>
                    Create a new membership record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  createMembership(input: {
    user_id: 1,
    points: 100
  }) {
    id
    user_id
    points
  }
}</pre>
                    </div>
                    
                    <p><strong>updateMembership(id: ID!, input: MembershipInput!): Membership</strong><br>
                    Update an existing membership record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  updateMembership(id: 1, input: {
    points: 150
  }) {
    id
    points
  }
}</pre>
                    </div>
                    
                    <p><strong>deleteMembership(id: ID!): Boolean</strong><br>
                    Delete a membership record by ID.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  deleteMembership(id: 1)
}</pre>
                    </div>
                    
                    <h5>Input Types</h5>
                    <p>MembershipInput:</p>
                    <ul>
                        <li>user_id: Int!</li>
                        <li>points: Int</li>
                    </ul>
                </div>
                
                <div class="doc-section">
                    <h4>Vendor Requests Service - GraphQL API Documentation</h4>
                    
                    <h5>Query</h5>
                    <h6>Get vendor by id</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  getVendorRequest(id:1){
    status
    id
    ingredient_id
    estimated_arrival
    requested_at
  }
}</pre>
                    </div>
                    
                    <h6>Get all vendor request</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>{
  getAllVendorRequests{
    status
    id
    ingredient_id
    estimated_arrival
    requested_at
  }
}</pre>
                    </div>
                    
                    <h5>Mutations</h5>
                    <p><strong>createVendorRequest(input: VendorRequestInput!): VendorRequest</strong><br>
                    Create a new vendor request record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  createVendorRequest(input: {
    vendor_id: 1,
    ingredient_id: 2,
    quantity: 100,
    status: "requested",
    requested_at: "2025-05-22T14:00:00Z",
    estimated_arrival: "2025-05-25T14:00:00Z"
  }) {
    id
    vendor_id
    ingredient_id
    quantity
    status
    requested_at
    estimated_arrival
  }
}</pre>
                    </div>
                    
                    <p><strong>updateVendorRequest(id: ID!, input: VendorRequestInput!): VendorRequest</strong><br>
                    Update an existing vendor request record.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  updateVendorRequest(id: 1, input: {
    status: "on-delivery"
  }) {
    id
    status
  }
}</pre>
                    </div>
                    
                    <p><strong>deleteVendorRequest(id: ID!): Boolean</strong><br>
                    Delete a vendor request record by ID.</p>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        <pre>mutation {
  deleteVendorRequest(id: 1)
}</pre>
                    </div>
                    
                    <h5>Input Types</h5>
                    <p>VendorRequestInput:</p>
                    <ul>
                        <li>vendor_id: Int!</li>
                        <li>ingredient_id: Int!</li>
                        <li>quantity: Int!</li>
                        <li>status: String</li>
                        <li>requested_at: String</li>
                        <li>estimated_arrival: String</li>
                    </ul>
                </div>
            `;
            
            $('#data-section').html(readMeContent);
        }

        function copyToClipboard(button) {
            const codeBlock = button.parentElement;
            const code = codeBlock.querySelector('pre').textContent;
            navigator.clipboard.writeText(code).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy';
                }, 2000);
            });
        }

        function showAddModal() {
            $('#addModalTitle').text(`Add New ${currentDataType.replace('-', ' ')}`);
            $('#addModalBody').html(formTemplates[currentDataType]);
            $('#addModal').modal('show');
        }

        function submitAddForm() {
            let data = {};
            let url = '';
            
            switch(currentDataType) {
                case 'vendor-requests':
                    data = {
                        vendor_id: $('#vendor_id').val(),
                        ingredient_id: $('#ingredient_id').val(),
                        quantity: parseInt($('#quantity').val()),
                        status: $('#status').val(),
                        requested_at: $('#requested_at').val(),
                        estimated_arrival: $('#estimated_arrival').val()
                    };
                    url = '/dashboard/create-vendor-request';
                    break;
                case 'deliveries':
                    data = {
                        order_id: $('#order_id').val(),
                        delivery_status: $('#delivery_status').val(),
                        delivery_time: $('#delivery_time').val(),
                        current_location: $('#current_location').val()
                    };
                    url = '/dashboard/create-delivery';
                    break;
                case 'memberships':
                    data = {
                        user_id: $('#user_id').val(),
                        points: parseInt($('#points').val())
                    };
                    url = '/dashboard/create-membership';
                    break;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#addModal').modal('hide');
                    loadTable(currentDataType);
                    alert('Data added successfully!');
                },
                error: function(xhr) {
                    alert('Error adding data: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }

        // Button bindings
        $('#btn-vendor').click(() => loadTable('vendor-requests'));
        $('#btn-deliveries').click(() => loadTable('deliveries'));
        $('#btn-memberships').click(() => loadTable('memberships'));
        $('#btn-readme').click(showReadMe);
        $('#btn-add-data').click(showAddModal);
        $('#btn-submit-add').click(submitAddForm);

        // Initial load
        loadTable('vendor-requests');
    </script>
</body>

</html>