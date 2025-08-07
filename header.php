<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Diary Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Diary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-primary);
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color) !important;
        }

        .content-wrapper {
            flex: 1;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
        }

        .card {
            background: white;
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table thead th {
            background: var(--secondary-color);
            border: none;
            color: var(--text-primary);
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            margin: 0 0.125rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .welcome-section {
            text-align: center;
            padding: 3rem 0;
            color: white;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .welcome-section p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .entry-item {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .entry-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .entry-date {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .entry-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .entry-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-wrapper {
                padding: 1rem 0;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 50%;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .floating-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            color: white;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">
                    <i class="bi bi-journal-bookmark"></i> My Diary
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="entries.php">
                                <i class="bi bi-journal-text"></i> All Entries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_entry.php">
                                <i class="bi bi-plus-circle"></i> New Entry
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php endif; ?>
        
        <div class="content-wrapper"