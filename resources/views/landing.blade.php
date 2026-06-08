<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>SyncLab - Sync Learning Laboratory</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #c084fc);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 12px;
        }

        .nav-logo span {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #2d3a4b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .nav-menu {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .nav-menu li a {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-menu li a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            transition: width 0.3s ease;
        }

        .nav-menu li a:hover::after,
        .nav-menu li a.active::after {
            width: 100%;
        }

        .nav-menu li a:hover {
            color: #818cf8;
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-login {
            padding: 8px 24px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            color: white;
            box-shadow: 0 4px 10px rgba(129, 140, 248, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(129, 140, 248, 0.4);
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1e293b;
            cursor: pointer;
        }

        .mobile-menu {
            position: fixed;
            top: 72px;
            left: 0;
            width: 100%;
            background: white;
            padding: 24px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 999;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        .mobile-menu ul {
            list-style: none;
            margin-bottom: 24px;
        }

        .mobile-menu ul li {
            margin: 16px 0;
        }

        .mobile-menu ul li a {
            text-decoration: none;
            color: #1e293b;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .mobile-buttons {
            display: flex;
            gap: 12px;
        }

        .mobile-buttons a {
            flex: 1;
            text-align: center;
        }

        /* Hero Section */
        .hero {
            position: relative;
            padding: 140px 32px 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        .hero-bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            pointer-events: none;
        }

        .hero-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .hero-content {
            flex: 1;
            min-width: 300px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.85rem;
            color: white;
            margin-bottom: 24px;
        }

        .hero-badge i {
            color: #facc15;
        }

        .hero-content h1 {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            color: white;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 48px;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 12px 28px;
            background: white;
            border-radius: 40px;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        .btn-secondary {
            padding: 12px 28px;
            background: transparent;
            border: 2px solid white;
            border-radius: 40px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .hero-stats {
            display: flex;
            gap: 40px;
        }

        .stat {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
        }

        .stat-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-image {
            flex: 1;
            min-width: 300px;
        }

        .hero-card-demo {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 28px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease;
        }

        .hero-card-demo:hover {
            transform: translateY(-5px);
        }

        .demo-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eef2ff;
            margin-bottom: 24px;
        }

        .demo-header i {
            font-size: 28px;
            color: #818cf8;
            background: rgba(129, 140, 248, 0.1);
            padding: 10px;
            border-radius: 16px;
        }

        .demo-header span {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .demo-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .demo-stat {
            flex: 1;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 1px solid #eef2ff;
        }

        .demo-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .demo-stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .demo-stat-icon i {
            font-size: 24px;
            color: white;
        }

        .demo-stat-number {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .demo-stat-label {
            font-size: 0.7rem;
            color: #64748b;
        }

        .demo-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .demo-footer-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #475569;
        }

        .demo-footer-item i {
            color: #10b981;
            font-size: 1rem;
        }

        .hero-wave {
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            z-index: 2;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-badge {
            display: inline-block;
            background: rgba(129, 140, 248, 0.15);
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.85rem;
            color: #818cf8;
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-header p {
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background: #f8fafc;
            border-radius: 24px;
            padding: 32px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #eef2ff;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.1);
            border-color: #818cf8;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(129, 140, 248, 0.3);
        }

        .feature-icon i {
            font-size: 32px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.5;
        }

        .courses {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 32px;
        }

        .course-card {
            background: white;
            border-radius: 28px;
            padding: 32px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2ff;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
            border-color: #818cf8;
        }

        .course-image {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(129, 140, 248, 0.3);
        }

        .course-image i {
            font-size: 32px;
            color: white;
        }

        .course-rating {
            color: #f59e0b;
            margin-bottom: 12px;
            font-size: 0.85rem;
        }

        .course-rating span {
            color: #64748b;
            margin-left: 8px;
        }

        .course-card h3 {
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .course-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .course-meta i {
            margin-right: 6px;
            color: #818cf8;
        }

        .course-card p {
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        /* Laboratories Section */
        .laboratories {
            padding: 80px 0;
            background: white;
        }

        .labs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 32px;
        }

        .lab-card {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 28px;
            padding: 32px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #eef2ff;
        }

        .lab-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
            border-color: #818cf8;
        }

        .lab-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(129, 140, 248, 0.3);
        }

        .lab-icon i {
            font-size: 32px;
            color: white;
        }

        .lab-card h3 {
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .lab-card p {
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .lab-stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 0.8rem;
            color: #475569;
        }

        .lab-stats i {
            color: #818cf8;
            margin-right: 6px;
        }

        .about {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .about-grid {
            display: flex;
            gap: 60px;
            align-items: center;
            flex-wrap: wrap;
        }

        .about-content {
            flex: 1;
        }

        .about-content h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .about-content p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .about-features {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .about-feature {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .about-feature i {
            font-size: 24px;
            color: #818cf8;
            background: rgba(129, 140, 248, 0.1);
            padding: 12px;
            border-radius: 16px;
        }

        .about-feature h4 {
            margin-bottom: 4px;
        }

        .about-feature p {
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        .about-image {
            flex: 1;
        }

        .about-stats-card {
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 32px;
            padding: 40px;
            color: white;
            box-shadow: 0 20px 35px rgba(129, 140, 248, 0.3);
        }

        .about-stat {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .about-stat:last-child {
            border-bottom: none;
        }

        .about-stat i {
            font-size: 32px;
        }

        .about-stat .stat-number {
            font-size: 1.3rem;
            font-weight: 700;
            display: block;
            color: white;
        }

        .about-stat .stat-label {
            font-size: 0.8rem;
            opacity: 0.9;
            color: white;
        }

        /* Contact Section */
        .contact {
            padding: 80px 0;
            background: white;
        }

        .contact-grid {
            display: flex;
            gap: 48px;
            flex-wrap: wrap;
        }

        .contact-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .contact-item {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: rgba(129, 140, 248, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-icon i {
            font-size: 24px;
            color: #818cf8;
        }

        .contact-item h4 {
            margin-bottom: 8px;
        }

        .contact-item p {
            color: #64748b;
            margin: 4px 0;
        }

        .contact-form {
            flex: 1;
            background: #f8fafc;
            padding: 32px;
            border-radius: 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            flex: 1;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1px solid #e2e8f0;
            border-radius: 60px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-group textarea {
            border-radius: 24px;
            resize: vertical;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .input-group textarea+i {
            top: 20px;
            transform: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border: none;
            border-radius: 60px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(129, 140, 248, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(129, 140, 248, 0.4);
        }

        .footer {
            background: #0f172a;
            color: #e2e8f0;
            padding: 60px 0 20px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand {
            text-align: left;
        }

        .footer-logo-img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .footer-brand h3 {
            margin-bottom: 12px;
        }

        .footer-brand p {
            color: #94a3b8;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #818cf8;
            transform: translateY(-3px);
        }

        .footer-links h4 {
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links ul li {
            margin-bottom: 12px;
        }

        .footer-links ul li a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: #818cf8;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.85rem;
        }

        @media (max-width: 1024px) {
            .courses-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {

            .nav-menu,
            .nav-buttons {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero {
                padding: 100px 20px 60px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-stats {
                flex-wrap: wrap;
                gap: 20px;
            }

            .section-header h2 {
                font-size: 1.6rem;
            }

            .form-row {
                flex-direction: column;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-brand {
                text-align: center;
            }

            .footer-brand .social-links {
                justify-content: center;
            }

            .courses-grid {
                grid-template-columns: 1fr;
            }

            .demo-stats {
                flex-direction: column;
            }

            .labs-grid {
                grid-template-columns: 1fr;
            }

            .about-grid {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons a {
                text-align: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .contact-grid {
                flex-direction: column;
            }

            .demo-footer {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="{{ asset('landing/logo.jpeg') }}" alt="SyncLab Logo" class="logo-img">
                <span>SyncLab</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#courses">Praktikum</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
            @guest
                <div class="nav-buttons">
                    <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                </div>
            @endguest
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="#home">Beranda</a></li>
            <li><a href="#features">Fitur</a></li>
            <li><a href="#courses">Praktikum</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#contact">Kontak</a></li>
        </ul>
        <div class="mobile-buttons">
            <a href="{{ route('login') }}" class="btn-login">Masuk</a>
        </div>
    </div>

    <section id="home" class="hero">
        <div class="hero-bg-overlay"></div>
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> Sync Learning Laboratory
                </div>
                <h1>Laboratorium <span class="gradient-text">Digital Terintegrasi</span></h1>
                <p>SyncLab adalah platform laboratorium digital yang terintegrasi untuk presensi, pretest, praktikum,
                    dan pelaporan kegiatan laboratorium secara online.</p>
                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn-primary">Mulai Sekarang <i
                            class="fas fa-arrow-right"></i></a>
                    <a href="#features" class="btn-secondary">Pelajari Lebih <i class="fas fa-play"></i></a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">{{ $mahasiswa }}+</span>
                        <span class="stat-label">Praktikan Aktif</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">{{ $praktikumCount }}</span>
                        <span class="stat-label">Mata Praktikum</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">{{ $laboratoriumCount }}</span>
                        <span class="stat-label">Laboratorium</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-card-demo">
                    <div class="demo-header">
                        <i class="fas fa-chart-line"></i>
                        <span>SyncLab Statistics</span>
                    </div>
                    <div class="demo-stats">
                        <div class="demo-stat">
                            <div class="demo-stat-icon">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <div class="demo-stat-number">Presensi</div>
                            <div class="demo-stat-label">Tingkat Kehadiran</div>
                        </div>
                        <div class="demo-stat">
                            <div class="demo-stat-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="demo-stat-number">Pretest</div>
                            <div class="demo-stat-label">Evaluasi Materi</div>
                        </div>
                        <div class="demo-stat">
                            <div class="demo-stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="demo-stat-number">Penilaian</div>
                            <div class="demo-stat-label">Nilai Laporan</div>
                        </div>
                    </div>
                    <div class="demo-footer">
                        <div class="demo-footer-item">
                            <i class="fas fa-check-circle"></i>
                            <span>8 Praktikum Tersedia</span>
                        </div>
                        <div class="demo-footer-item">
                            <i class="fas fa-users"></i>
                            <span>100+ Praktikan Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 64L60 69.3C120 75 240 85 360 80C480 75 600 53 720 48C840 43 960 53 1080 58.7C1200 64 1320 64 1380 64L1440 64L1440 120L1380 120C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120L0 120Z"
                    fill="white" />
            </svg>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Fitur Unggulan</span>
                <h2>Layanan Lengkap <span class="gradient-text">Laboratorium Digital</span></h2>
                <p>Berbagai fitur tersedia untuk mendukung kegiatan praktikum</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-fingerprint"></i></div>
                    <h3>Presensi Online</h3>
                    <p>Presensi praktikum secara digital dengan sistem real-time</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                    <h3>Pretest Praktikum</h3>
                    <p>Ikuti pretest sebelum memulai kegiatan praktikum</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-upload"></i></div>
                    <h3>Upload Laporan</h3>
                    <p>Upload laporan praktikum secara online</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-star"></i></div>
                    <h3>Penilaian</h3>
                    <p>Lihat nilai pretest dan laporan secara langsung</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Jadwal Praktikum</h3>
                    <p>Lihat dan pilih jadwal praktikum yang tersedia</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-history"></i></div>
                    <h3>Riwayat Praktikum</h3>
                    <p>Arsip lengkap riwayat praktikum dan nilai</p>
                </div>
            </div>
        </div>
    </section>

    <section id="courses" class="courses">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Praktikum Tersedia</span>
                <h2>{{ $praktikumCount }} <span class="gradient-text">Mata Praktikum</span></h2>
                <p>Bergabunglah dengan praktikan lainnya dalam kegiatan laboratorium</p>
            </div>
            <div class="courses-grid">
                @foreach ($praktikum as $prak)
                    <div class="course-card">
                        <div class="course-image"><i class="fas fa-code"></i></div>
                        <div class="course-rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><span>(128
                                Reviews)</span></div>
                        <h3>{{ $prak->nama_praktikum }}</h3>
                        <div class="course-meta"><span><i class="fas fa-flask"></i> {{ $prak->moduls->count() }}
                                Modul</span><span><i class="fas fa-users"></i> {{ $prak->praktikan_count ?? 0 }}
                                Praktikan</span></div>
                        <p>{{ $prak->deskripsi ?? 'Praktikum ini membahas konsep dasar dan implementasi praktis' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="laboratories">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Fasilitas</span>
                <h2><span class="gradient-text">{{ $laboratoriumCount }} Laboratorium</span> Unggulan</h2>
                <p>Fasilitas laboratorium lengkap untuk mendukung kegiatan praktikum</p>
            </div>
            <div class="labs-grid">
                @foreach ($laboratorium as $lab)
                    <div class="lab-card">
                        <div class="lab-icon"><i class="fas fa-network-wired"></i></div>
                        <h3>{{ $lab->nama_laboratorium }}</h3>
                        <p>{{ $lab->deskripsi ?? 'Fasilitas laboratorium terintegrasi untuk mendukung kegiatan akademik.' }}
                        </p>
                        <div class="lab-stats"><span><i class="fas fa-desktop"></i>{{ $lab->lokasi }}</span><span><i
                                    class="fas fa-users"></i> {{ $lab->kapasitas }} Seat</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <span class="section-badge">Tentang SyncLab</span>
                    <h2>Platform <span class="gradient-text">Laboratorium Modern</span></h2>
                    <p>SyncLab (Sync Learning Laboratory) adalah platform laboratorium digital yang terintegrasi untuk
                        mendukung kegiatan praktikum secara online. Kami menyediakan solusi lengkap untuk presensi,
                        pretest, pelaporan, dan penilaian praktikum.</p>
                    <div class="about-features">
                        <div class="about-feature"><i class="fas fa-shield-alt"></i>
                            <div>
                                <h4>Sistem Terintegrasi</h4>
                                <p>Terhubung dengan berbagai modul praktikum</p>
                            </div>
                        </div>
                        <div class="about-feature"><i class="fas fa-mobile-alt"></i>
                            <div>
                                <h4>Akses Mobile</h4>
                                <p>Dapat diakses dari berbagai perangkat</p>
                            </div>
                        </div>
                        <div class="about-feature"><i class="fas fa-headset"></i>
                            <div>
                                <h4>Dukungan 24/7</h4>
                                <p>Tim support siap membantu Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div class="about-stats-card">
                        <div class="about-stat"><i class="fas fa-flask"></i>
                            <div><span class="stat-number">8</span><span class="stat-label">Mata Praktikum</span>
                            </div>
                        </div>
                        <div class="about-stat"><i class="fas fa-file-alt"></i>
                            <div><span class="stat-number">100+</span><span class="stat-label">Laporan
                                    Terupload</span></div>
                        </div>
                        <div class="about-stat"><i class="fas fa-users"></i>
                            <div><span class="stat-number">100+</span><span class="stat-label">Praktikan Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Hubungi Kami</span>
                <h2>Ada Pertanyaan? <span class="gradient-text">Tim Kami Siap Membantu</span></h2>
                <p>Silakan hubungi kami melalui informasi kontak di bawah ini</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4>Alamat Laboratorium</h4>
                            <p>Gedung A10, Teknik Informatika, UNESA</p>
                            <p>Kampus Lidah, Surabaya</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4>Email</h4>
                            <p>info@synclab.ac.id</p>
                            <p>support@synclab.ac.id</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h4>Telepon</h4>
                            <p>(031) 1234 5678</p>
                            <p>+62 812 3456 7890</p>
                        </div>
                    </div>
                </div>
                <form class="contact-form">
                    <div class="form-row">
                        <div class="input-group"><input type="text" placeholder="Nama Lengkap"><i
                                class="fas fa-user"></i></div>
                        <div class="input-group"><input type="email" placeholder="Email"><i
                                class="fas fa-envelope"></i></div>
                    </div>
                    <div class="input-group"><input type="text" placeholder="Subjek"><i class="fas fa-tag"></i>
                    </div>
                    <div class="input-group">
                        <textarea rows="5" placeholder="Pesan"></textarea><i class="fas fa-comment"></i>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Pesan <i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="logo.jpeg" alt="SyncLab Logo" class="footer-logo-img">
                    <h3>SyncLab</h3>
                    <p>Sync Learning Laboratory - Platform laboratorium digital terintegrasi.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#courses">Praktikum</a></li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#">Presensi</a></li>
                        <li><a href="#">Pretest</a></li>
                        <li><a href="#">Upload Laporan</a></li>
                        <li><a href="#">Jadwal Praktikum</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Bantuan</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Panduan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 SyncLab - Sync Learning Laboratory. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        (function() {
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileToggle && mobileMenu) {
                mobileToggle.addEventListener('click', () => {
                    mobileMenu.classList.toggle('active');
                    const icon = mobileToggle.querySelector('i');
                    if (mobileMenu.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }
            const mobileLinks = document.querySelectorAll('.mobile-menu a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;
                    if (href.startsWith('#')) {
                        e.preventDefault();
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            const navbar = document.querySelector('.navbar');

            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;

                if (currentScroll > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                    navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.05)';
                }
            });

            const contactForm = document.querySelector('.contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    alert('📨 Pesan Anda telah terkirim! Tim kami akan segera menghubungi Anda.');
                    contactForm.reset();
                });
            }

            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-menu a');

            window.addEventListener('scroll', () => {
                let current = '';
                const scrollPosition = window.scrollY + 100;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    const href = link.getAttribute('href');
                    if (href === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll(
                '.feature-card, .course-card, .lab-card, .about-content, .about-image, .contact-info, .contact-form'
            ).forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        })();
    </script>
</body>

</html>
