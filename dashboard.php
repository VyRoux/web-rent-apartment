<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}

// Include controller
require_once 'app/rentController.php';

// Buat instance controller
 $rentController = new rentController();

// Ambil data dari database
 $apartments = $rentController->getAllApartments();
 $transactions = $rentController->getAllTransactions();
 $users = $rentController->getAllUsers();

// Hitung statistik
 $totalApartments = count($apartments);
 $availableApartments = 0;
 $rentedApartments = 0;

foreach ($apartments as $apartment) {
    if ($apartment['status'] == 'available') {
        $availableApartments++;
    } else {
        $rentedApartments++;
    }
}

 $totalTransactions = count($transactions);
 $pendingTransactions = 0;
 $confirmedTransactions = 0;
 $completedTransactions = 0;

foreach ($transactions as $transaction) {
    if ($transaction['status'] == 'pending') {
        $pendingTransactions++;
    } else if ($transaction['status'] == 'confirmed') {
        $confirmedTransactions++;
    } else if ($transaction['status'] == 'completed') {
        $completedTransactions++;
    }
}

 $totalUsers = count($users);
 $regularUsers = 0;
 $adminUsers = 0;

foreach ($users as $user) {
    if ($user['role'] == 'user') {
        $regularUsers++;
    } else {
        $adminUsers++;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Sewa Apartment.id</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom dashboard style */
        body {
            padding-top: 50px;
        }

        .sidebar {
            position: fixed;
            top: 50px;
            bottom: 0;
            left: 0;
            width: 200px;
            padding: 20px;
            background-color: #f5f5f5;
            overflow-y: auto;
        }

        .main {
            margin-left: 220px;
            padding: 20px;
        }

        .placeholder img {
            background: #eee;
            border-radius: 50%;
            /* Membuat gambar menjadi bulat */
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #f0f0f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .stat-box {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        }
        
        .stat-box:hover {
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        }
        
        .stat-box i {
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .status-available {
            color: #5cb85c;
        }
        
        .status-rented {
            color: #d9534f;
        }
        
        .status-pending {
            color: #f0ad4e;
        }
        
        .status-confirmed {
            color: #5bc0de;
        }
        
        .status-completed {
            color: #5cb85c;
        }
        
        .table-actions {
            width: 120px;
        }
        
        .btn-action {
            padding: 3px 8px;
            margin: 0 2px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Sewa Apartment.id</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="#"><i class="fas fa-question-circle"></i> Help</a></li>
                <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i> Overview</a></li>
            <li><a href="#apartments"><i class="fas fa-building"></i> Apartments</a></li>
            <li><a href="#transactions"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
            <li><a href="#users"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="#"><i class="fas fa-file-export"></i> Export</a></li>
        </ul>
        <hr>
        <ul class="nav nav-pills nav-stacked">
            <li><a href="#"><i class="fas fa-plus"></i> Add Apartment</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Dashboard</h1>
        <p>Welcome back, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-box text-center">
                    <i class="fas fa-building text-primary"></i>
                    <h3><?php echo $totalApartments; ?></h3>
                    <p>Total Apartments</p>
                    <div class="progress">
                        <div class="progress-bar status-available" style="width: <?php echo $totalApartments > 0 ? ($availableApartments / $totalApartments) * 100 : 0; ?>%">
                            <?php echo $availableApartments; ?> Available
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box text-center">
                    <i class="fas fa-exchange-alt text-info"></i>
                    <h3><?php echo $totalTransactions; ?></h3>
                    <p>Total Transactions</p>
                    <div class="progress">
                        <div class="progress-bar status-completed" style="width: <?php echo $totalTransactions > 0 ? ($completedTransactions / $totalTransactions) * 100 : 0; ?>%">
                            <?php echo $completedTransactions; ?> Completed
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box text-center">
                    <i class="fas fa-users text-success"></i>
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Total Users</p>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo $totalUsers > 0 ? ($regularUsers / $totalUsers) * 100 : 0; ?>%">
                            <?php echo $regularUsers; ?> Regular Users
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box text-center">
                    <i class="fas fa-dollar-sign text-warning"></i>
                    <h3>Rp <?php echo number_format($completedTransactions * 1000000, 0, ',', '.'); ?></h3>
                    <p>Estimated Revenue</p>
                    <small>Based on completed transactions</small>
                </div>
            </div>
        </div>

        <!-- Section: Apartments -->
        <h2 id="apartments">Apartments</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Address</th>
                        <th>Price per Month</th>
                        <th>Status</th>
                        <th class="table-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($apartments as $apartment): ?>
                    <tr>
                        <td><?php echo $apartment['id']; ?></td>
                        <td><?php echo $apartment['name']; ?></td>
                        <td><?php echo $apartment['description']; ?></td>
                        <td><?php echo $apartment['address']; ?></td>
                        <td>Rp <?php echo number_format($apartment['price_per_month'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($apartment['status'] == 'available'): ?>
                                <span class="label label-success status-available">Available</span>
                            <?php else: ?>
                                <span class="label label-danger status-rented">Rented</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <button class="btn btn-sm btn-primary btn-action"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Section: Transactions -->
        <h2 id="transactions">Recent Transactions</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Apartment</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th class="table-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($transactions, 0, 5) as $transaction): ?>
                    <tr>
                        <td><?php echo $transaction['id']; ?></td>
                        <td><?php echo $transaction['username']; ?></td>
                        <td><?php echo $transaction['apartment_name']; ?></td>
                        <td><?php echo date('d M Y', strtotime($transaction['start_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($transaction['end_date'])); ?></td>
                        <td>Rp <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($transaction['status'] == 'pending'): ?>
                                <span class="label label-warning status-pending">Pending</span>
                            <?php elseif ($transaction['status'] == 'confirmed'): ?>
                                <span class="label label-info status-confirmed">Confirmed</span>
                            <?php elseif ($transaction['status'] == 'completed'): ?>
                                <span class="label label-success status-completed">Completed</span>
                            <?php else: ?>
                                <span class="label label-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <button class="btn btn-sm btn-primary btn-action"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Section: Users -->
        <h2 id="users">Recent Users</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered Date</th>
                        <th class="table-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($users, 0, 5) as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['full_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <?php if ($user['role'] == 'admin'): ?>
                                <span class="label label-danger">Admin</span>
                            <?php else: ?>
                                <span class="label label-primary">User</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td class="table-actions">
                            <button class="btn btn-sm btn-primary btn-action"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <script>
        // Smooth scrolling for sidebar links
        $(document).ready(function() {
            $('.sidebar a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            });
            
            // Update active sidebar link based on scroll position
            $(window).scroll(function() {
                var scrollPosition = $(window).scrollTop();
                
                $('.sidebar a[href^="#"]').each(function() {
                    var currentLink = $(this);
                    var refElement = $(currentLink.attr("href"));
                    
                    if (refElement.position().top - 80 <= scrollPosition && refElement.position().top + refElement.height() > scrollPosition) {
                        $('.sidebar a').removeClass("active");
                        currentLink.addClass("active");
                    }
                });
            });
        });
    </script>
</body>

</html>