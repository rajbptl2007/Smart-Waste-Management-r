<?php
// landing.php - Premium Landing Page
// Smart Waste Collection Management System
// This file is the NEW entry point. Original main.php is untouched.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Collection Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ============================================
           CSS RESET & ROOT VARIABLES
        ============================================ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --emerald: #10b981;
            --emerald-dark: #059669;
            --emerald-light: #34d399;
            --forest: #065f46;
            --forest-light: #064e3b;
            --white: #ffffff;
            --dark: #111827;
            --dark-gray: #1f2937;
            --gray: #374151;
            --gray-light: #6b7280;
            --gray-lighter: #9ca3af;
            --soft-blue: #3b82f6;
            --soft-blue-light: #60a5fa;
            --bg-light: #f0fdf4;
            --bg-gradient: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0f9ff 100%);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.25);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.04);
            --shadow-2xl: 0 25px 50px rgba(0,0,0,0.15);
            --border-radius: 16px;
            --border-radius-lg: 24px;
            --border-radius-xl: 32px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: var(--bg-gradient);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ============================================
           SCROLLBAR STYLING
        ============================================ */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--emerald);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--emerald-dark);
        }

        /* ============================================
           UTILITY CLASSES
        ============================================ */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.06));
            border: 1px solid rgba(16,185,129,0.25);
            color: var(--emerald-dark);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.2;
            color: var(--dark);
            margin-bottom: 16px;
        }

        .section-title span {
            background: linear-gradient(135deg, var(--emerald), var(--soft-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--gray-light);
            max-width: 600px;
            line-height: 1.7;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-header .section-subtitle {
            margin: 0 auto;
        }

        /* ============================================
           BUTTON STYLES
        ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s, opacity 0.6s;
            opacity: 0;
        }

        .btn:active::after {
            width: 300px;
            height: 300px;
            opacity: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            color: var(--white);
            box-shadow: 0 8px 25px rgba(16,185,129,0.35);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(16,185,129,0.45);
            background: linear-gradient(135deg, var(--emerald-light), var(--emerald));
        }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            color: var(--white);
            border: 2px solid rgba(255,255,255,0.4);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .btn-outline {
            background: transparent;
            color: var(--emerald);
            border: 2px solid var(--emerald);
        }

        .btn-outline:hover {
            background: var(--emerald);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16,185,129,0.3);
        }

        /* ============================================
           ANIMATIONS
        ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes ripple {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(4); opacity: 0; }
        }

        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        .animate-fade-left {
            animation: fadeInLeft 0.8s ease forwards;
        }

        .animate-fade-right {
            animation: fadeInRight 0.8s ease forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-2 {
            animation: float2 8s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse 3s ease-in-out infinite;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }
        .delay-400 { transition-delay: 0.4s; }
        .delay-500 { transition-delay: 0.5s; }
        .delay-600 { transition-delay: 0.6s; }
        .delay-700 { transition-delay: 0.7s; }
        .delay-800 { transition-delay: 0.8s; }

        /* ============================================
           NAVBAR
        ============================================ */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 20px 0;
            transition: var(--transition-slow);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            padding: 12px 0;
            border-bottom: 1px solid rgba(16,185,129,0.1);
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(16,185,129,0.4);
            transition: var(--transition);
        }

        .navbar-logo:hover .navbar-logo-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .navbar-logo-text {
            display: flex;
            flex-direction: column;
        }

        .navbar-logo-text span:first-child {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }

        .navbar.scrolled .navbar-logo-text span:first-child {
            color: var(--dark);
        }

        .navbar-logo-text span:last-child {
            font-size: 0.7rem;
            color: var(--emerald);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }

        .navbar-menu a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
        }

        .navbar.scrolled .navbar-menu a {
            color: var(--gray);
        }

        .navbar-menu a::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--emerald);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .navbar-menu a:hover::after {
            width: 60%;
        }

        .navbar-menu a:hover {
            color: var(--emerald);
            background: rgba(16,185,129,0.08);
        }

        .navbar-btn {
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark)) !important;
            color: white !important;
            padding: 10px 24px !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(16,185,129,0.4) !important;
        }

        .navbar-btn::after {
            display: none !important;
        }

        .navbar-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16,185,129,0.5) !important;
            background: linear-gradient(135deg, var(--emerald-light), var(--emerald)) !important;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            border: none;
            backdrop-filter: blur(10px);
        }

        .hamburger span {
            width: 24px;
            height: 2px;
            background: var(--white);
            border-radius: 2px;
            transition: var(--transition);
        }

        .navbar.scrolled .hamburger span {
            background: var(--dark);
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* ============================================
           HERO SECTION
        ============================================ */
        #home {
            min-height: 100vh;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 30%, #047857 60%, #1a4a8a 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        #home::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Floating blobs */
        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: blob 10s ease-in-out infinite;
        }

        .hero-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--emerald);
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .hero-blob-2 {
            width: 400px;
            height: 400px;
            background: var(--soft-blue);
            bottom: -100px;
            left: -100px;
            animation-delay: -5s;
        }

        .hero-blob-3 {
            width: 300px;
            height: 300px;
            background: #a7f3d0;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -3s;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            padding: 120px 0 80px;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #a7f3d0;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .hero-badge i {
            animation: spin-slow 4s linear infinite;
        }

        .hero-heading {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 24px;
            animation: fadeInUp 0.8s 0.2s ease both;
        }

        .hero-heading span {
            background: linear-gradient(135deg, #6ee7b7, #a7f3d0, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s 0.4s ease both;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s 0.6s ease both;
        }

        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 48px;
            animation: fadeInUp 0.8s 0.8s ease both;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #6ee7b7;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.2);
            height: 40px;
            align-self: center;
        }

        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            animation: fadeInRight 1s 0.4s ease both;
        }

        .hero-illustration-inner {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-svg-wrapper {
            width: 100%;
            max-width: 520px;
            animation: float 6s ease-in-out infinite;
        }

        /* Floating badges around illustration */
        .float-badge {
            position: absolute;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 16px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        .float-badge-1 {
            top: 10%;
            right: -20px;
            animation: float2 7s ease-in-out infinite;
        }

        .float-badge-2 {
            bottom: 20%;
            left: -20px;
            animation: float2 9s ease-in-out infinite reverse;
        }

        .float-badge-3 {
            top: 50%;
            right: -30px;
            animation: float 8s ease-in-out infinite 1s;
        }

        .float-badge i {
            font-size: 1.2rem;
        }

        /* ============================================
           FEATURES SECTION
        ============================================ */
        #features {
            padding: 120px 0;
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        #features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--emerald), var(--soft-blue), var(--emerald));
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid rgba(16,185,129,0.1);
            border-radius: var(--border-radius);
            padding: 32px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            group: true;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16,185,129,0.05), rgba(59,130,246,0.05));
            opacity: 0;
            transition: var(--transition);
        }

        .feature-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--border-radius);
            padding: 1px;
            background: linear-gradient(135deg, var(--emerald), var(--soft-blue));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            opacity: 0;
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-2xl);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover::after {
            opacity: 1;
        }

        .feature-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon-wrap {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon-1 { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
        .feature-icon-2 { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
        .feature-icon-3 { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #db2777; }
        .feature-icon-4 { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
        .feature-icon-5 { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
        .feature-icon-6 { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
        .feature-icon-7 { background: linear-gradient(135deg, #ecfeff, #cffafe); color: #0891b2; }
        .feature-icon-8 { background: linear-gradient(135deg, #fef9c3, #fef08a); color: #ca8a04; }
        .feature-icon-9 { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; }

        .feature-card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--gray-light);
            line-height: 1.7;
        }

        .feature-arrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--emerald);
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 16px;
            opacity: 0;
            transform: translateX(-10px);
            transition: var(--transition);
        }

        .feature-card:hover .feature-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* ============================================
           HOW IT WORKS SECTION
        ============================================ */
        #how-it-works {
            padding: 120px 0;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5, #f0f9ff);
            position: relative;
            overflow: hidden;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            position: relative;
        }

        .steps-connector {
            position: absolute;
            top: 60px;
            left: calc(12.5% + 32px);
            right: calc(12.5% + 32px);
            height: 2px;
            background: linear-gradient(90deg, var(--emerald), var(--soft-blue));
            z-index: 0;
        }

        .steps-connector::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, var(--emerald), var(--soft-blue));
            animation: shimmer 2s linear infinite;
            background-size: 200% 100%;
        }

        .step-card {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .step-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--white);
            border: 3px solid var(--emerald);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 8px rgba(16,185,129,0.08), var(--shadow-lg);
            transition: var(--transition);
            position: relative;
        }

        .step-card:hover .step-ring {
            transform: scale(1.15);
            box-shadow: 0 0 0 12px rgba(16,185,129,0.12), var(--shadow-xl);
            border-color: var(--emerald-dark);
        }

        .step-number {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--emerald), var(--soft-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .step-icon {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            box-shadow: 0 4px 10px rgba(16,185,129,0.4);
        }

        .step-card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .step-card p {
            font-size: 0.88rem;
            color: var(--gray-light);
            line-height: 1.7;
        }

        .step-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(59,130,246,0.1));
            border: 1px solid rgba(16,185,129,0.2);
            color: var(--emerald-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============================================
           ABOUT SECTION
        ============================================ */
        #about {
            padding: 120px 0;
            background: var(--white);
        }

        .about-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .about-visual {
            position: relative;
        }

        .about-main-card {
            background: linear-gradient(135deg, var(--forest), #047857);
            border-radius: var(--border-radius-xl);
            padding: 48px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .about-main-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .about-main-card h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .about-main-card p {
            font-size: 0.95rem;
            opacity: 0.85;
            line-height: 1.8;
            position: relative;
            z-index: 1;
        }

        .about-float-card {
            position: absolute;
            background: var(--white);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: var(--shadow-xl);
            display: flex;
            align-items: center;
            gap: 14px;
            animation: float 6s ease-in-out infinite;
        }

        .about-float-card-1 {
            bottom: -20px;
            right: -20px;
            animation-delay: 0s;
        }

        .about-float-card-2 {
            top: -20px;
            left: -20px;
            animation-delay: -3s;
        }

        .about-float-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .about-float-text strong {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .about-float-text span {
            font-size: 0.8rem;
            color: var(--gray-light);
        }

        .about-content h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .about-content p {
            font-size: 1rem;
            color: var(--gray-light);
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .about-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .about-card {
            background: var(--bg-light);
            border: 1px solid rgba(16,185,129,0.1);
            border-radius: var(--border-radius);
            padding: 24px;
            transition: var(--transition);
        }

        .about-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(16,185,129,0.3);
        }

        .about-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 12px;
            box-shadow: 0 4px 15px rgba(16,185,129,0.3);
        }

        .about-card h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .about-card p {
            font-size: 0.83rem;
            color: var(--gray-light);
            line-height: 1.6;
            margin: 0;
        }

        /* ============================================
           WHY CHOOSE US
        ============================================ */
        #why-us {
            padding: 120px 0;
            background: linear-gradient(135deg, #065f46, #064e3b, #1e3a5f);
            position: relative;
            overflow: hidden;
        }

        #why-us::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        #why-us .section-title {
            color: var(--white);
        }

        #why-us .section-subtitle {
            color: rgba(255,255,255,0.7);
        }

        #why-us .section-badge {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.2);
            color: #a7f3d0;
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .why-card {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: var(--border-radius);
            padding: 36px;
            text-align: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .why-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--border-radius);
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(59,130,246,0.05));
            opacity: 0;
            transition: var(--transition);
        }

        .why-card:hover {
            transform: translateY(-8px);
            border-color: rgba(110,231,183,0.3);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .why-card:hover::before {
            opacity: 1;
        }

        .why-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(110,231,183,0.2), rgba(110,231,183,0.1));
            border: 1px solid rgba(110,231,183,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 24px;
            transition: var(--transition);
        }

        .why-card:hover .why-icon {
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            border-color: transparent;
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(16,185,129,0.4);
        }

        .why-card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 12px;
        }

        .why-card p {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.7;
        }

        /* ============================================
           REPORTS SECTION
        ============================================ */
        #reports {
            padding: 120px 0;
            background: var(--white);
        }

        .reports-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 60px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-light), #ecfdf5);
            border: 1px solid rgba(16,185,129,0.15);
            border-radius: var(--border-radius);
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--emerald), var(--soft-blue));
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .stat-icon-1 { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .stat-icon-2 { background: linear-gradient(135deg, #fef3c7, #fde68a); }
        .stat-icon-3 { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
        .stat-icon-4 { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
        .stat-icon-5 { background: linear-gradient(135deg, #fce7f3, #fbcfe8); }
        .stat-icon-6 { background: linear-gradient(135deg, #ecfeff, #cffafe); }

        .stat-info h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
        }

        .stat-info p {
            font-size: 0.9rem;
            color: var(--gray-light);
            margin-top: 4px;
        }

        .stat-trend {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--emerald);
        }

        /* ============================================
           CHARTS SECTION
        ============================================ */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .chart-card {
            background: var(--white);
            border: 1px solid rgba(16,185,129,0.1);
            border-radius: var(--border-radius);
            padding: 32px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .chart-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-4px);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .chart-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chart-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .chart-title h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .chart-title p {
            font-size: 0.8rem;
            color: var(--gray-light);
        }

        .chart-badge {
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
            color: var(--emerald-dark);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid rgba(16,185,129,0.2);
        }

        .chart-canvas {
            position: relative;
            height: 220px;
        }

        /* ============================================
           PROJECT HIGHLIGHTS
        ============================================ */
        #highlights {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--emerald), #047857, var(--soft-blue));
            background-size: 400% 400%;
            animation: gradientShift 6s ease infinite;
            position: relative;
            overflow: hidden;
        }

        #highlights::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.1);
        }

        .highlights-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 32px;
            position: relative;
            z-index: 1;
        }

        .highlight-item {
            text-align: center;
            color: var(--white);
        }

        .highlight-number {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 4vw, 4rem);
            font-weight: 900;
            color: var(--white);
            display: block;
            line-height: 1;
            margin-bottom: 8px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .highlight-label {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
        }

        .highlight-divider {
            width: 1px;
            background: rgba(255,255,255,0.25);
            height: 80px;
            align-self: center;
            display: block;
        }

        /* ============================================
           TESTIMONIALS
        ============================================ */
        #testimonials {
            padding: 120px 0;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5, #f0f9ff);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .testimonial-card {
            background: var(--white);
            border: 1px solid rgba(16,185,129,0.1);
            border-radius: var(--border-radius-lg);
            padding: 36px;
            position: relative;
            transition: var(--transition);
            overflow: hidden;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 24px;
            font-size: 10rem;
            color: var(--emerald);
            opacity: 0.06;
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            line-height: 1;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-2xl);
            border-color: rgba(16,185,129,0.2);
        }

        .testimonial-stars {
            display: flex;
            gap: 4px;
            margin-bottom: 16px;
        }

        .testimonial-stars i {
            color: #f59e0b;
            font-size: 1rem;
        }

        .testimonial-text {
            font-size: 0.95rem;
            color: var(--gray);
            line-height: 1.8;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .testimonial-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .testimonial-avatar-1 { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .testimonial-avatar-2 { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
        .testimonial-avatar-3 { background: linear-gradient(135deg, #fce7f3, #fbcfe8); }

        .testimonial-author-info strong {
            display: block;
            font-weight: 700;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .testimonial-author-info span {
            font-size: 0.82rem;
            color: var(--emerald);
            font-weight: 500;
        }

        .testimonial-badge {
            position: absolute;
            top: 24px;
            right: 24px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ============================================
           FAQ SECTION
        ============================================ */
        #faq {
            padding: 120px 0;
            background: var(--white);
        }

        .faq-inner {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            border: 1px solid rgba(16,185,129,0.15);
            border-radius: var(--border-radius);
            margin-bottom: 16px;
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item:hover {
            border-color: rgba(16,185,129,0.3);
            box-shadow: var(--shadow-md);
        }

        .faq-question {
            width: 100%;
            padding: 24px 28px;
            background: transparent;
            border: none;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
            transition: var(--transition);
        }

        .faq-question:hover {
            background: rgba(16,185,129,0.04);
        }

        .faq-item.active .faq-question {
            color: var(--emerald-dark);
            background: rgba(16,185,129,0.06);
        }

        .faq-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.85rem;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .faq-item.active .faq-icon {
            transform: rotate(45deg);
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
        }

        .faq-answer-inner {
            padding: 0 28px 24px;
            font-size: 0.93rem;
            color: var(--gray-light);
            line-height: 1.8;
        }

        /* ============================================
           CTA SECTION
        ============================================ */
        #contact {
            padding: 120px 0;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #1a4a8a 100%);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        #contact::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: var(--emerald);
            opacity: 0.08;
            border-radius: 50%;
            filter: blur(100px);
            top: -200px;
            left: -200px;
            animation: pulse 4s ease-in-out infinite;
        }

        .cta-glow-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--soft-blue);
            opacity: 0.1;
            border-radius: 50%;
            filter: blur(80px);
            bottom: -100px;
            right: -100px;
            animation: pulse 6s ease-in-out infinite reverse;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #a7f3d0;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .cta-heading {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .cta-heading span {
            background: linear-gradient(135deg, #6ee7b7, #a7f3d0, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-sub {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.75);
            max-width: 550px;
            margin: 0 auto 48px;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .cta-features {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-top: 48px;
            flex-wrap: wrap;
        }

        .cta-feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
        }

        .cta-feature-item i {
            color: #6ee7b7;
        }

        /* ============================================
           FOOTER
        ============================================ */
        footer {
            background: #030712;
            color: rgba(255,255,255,0.7);
            padding: 80px 0 32px;
        }

        .footer-inner {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-brand p {
            font-size: 0.9rem;
            line-height: 1.8;
            color: rgba(255,255,255,0.55);
            margin: 20px 0 28px;
        }

        .footer-social {
            display: flex;
            gap: 12px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .social-btn:hover {
            background: var(--emerald);
            border-color: var(--emerald);
            color: white;
            transform: translateY(-3px);
        }

        .footer-col h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 12px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 32px;
            height: 2px;
            background: var(--emerald);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a i {
            font-size: 0.7rem;
            color: var(--emerald);
        }

        .footer-links a:hover {
            color: var(--emerald);
            transform: translateX(4px);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .footer-contact-item i {
            color: var(--emerald);
            font-size: 1rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .footer-contact-item span {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.6;
        }

        .footer-bottom {
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-bottom p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.35);
        }

        .footer-bottom p span {
            color: var(--emerald);
        }

        .footer-bottom-links {
            display: flex;
            gap: 20px;
        }

        .footer-bottom-links a {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-bottom-links a:hover {
            color: var(--emerald);
        }

        /* ============================================
           BACK TO TOP
        ============================================ */
        .back-to-top {
            position: fixed;
            bottom: 32px;
            right: 32px;
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 8px 25px rgba(16,185,129,0.4);
            transition: var(--transition);
            z-index: 999;
            opacity: 0;
            transform: translateY(20px);
        }

        .back-to-top.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(16,185,129,0.5);
        }

        /* ============================================
           LOADING SCREEN
        ============================================ */
        .loader {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #064e3b, #1a4a8a);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 24px;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-icon {
            font-size: 3rem;
            animation: spin-slow 2s linear infinite;
            color: var(--emerald);
        }

        .loader-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
        }

        .loader-bar {
            width: 200px;
            height: 3px;
            background: rgba(255,255,255,0.15);
            border-radius: 3px;
            overflow: hidden;
        }

        .loader-bar-fill {
            height: 100%;
            background: var(--emerald);
            border-radius: 3px;
            animation: loadingBar 1.5s ease forwards;
        }

        @keyframes loadingBar {
            from { width: 0%; }
            to { width: 100%; }
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1100px) {
            .hero-inner {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }

            .about-inner {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .about-visual {
                order: -1;
            }

            .highlights-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .footer-inner {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 900px) {
            .steps-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps-connector {
                display: none;
            }

            .why-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .reports-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .highlights-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(6, 78, 59, 0.97);
                backdrop-filter: blur(20px);
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 24px;
                z-index: 999;
                padding: 40px;
            }

            .navbar-menu.open {
                display: flex;
            }

            .navbar-menu a {
                font-size: 1.2rem;
                color: white !important;
                padding: 12px 24px;
            }

            .hamburger {
                display: flex;
                z-index: 1000;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 48px;
                text-align: center;
                padding: 100px 0 60px;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-illustration {
                order: -1;
            }

            .float-badge-1,
            .float-badge-2,
            .float-badge-3 {
                display: none;
            }

            .steps-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }

            .why-grid {
                grid-template-columns: 1fr;
            }

            .reports-stats-grid {
                grid-template-columns: 1fr;
            }

            .highlights-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }

            .about-cards-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .footer-inner {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-features {
                flex-direction: column;
                align-items: center;
                gap: 16px;
            }
        }

        @media (max-width: 480px) {
            .highlights-grid {
                grid-template-columns: 1fr 1fr;
            }

            .hero-stats {
                flex-direction: column;
                gap: 16px;
            }

            .hero-stat-divider {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================
         LOADING SCREEN
    ============================================ -->
    <div class="loader" id="loader">
        <i class="fas fa-recycle loader-icon"></i>
        <p class="loader-text">Smart Waste Management</p>
        <div class="loader-bar">
            <div class="loader-bar-fill"></div>
        </div>
    </div>

    <!-- ============================================
         BACK TO TOP BUTTON
    ============================================ -->
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- ============================================
         NAVIGATION BAR
    ============================================ -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="navbar-inner">
                <a href="#home" class="navbar-logo">
                    <div class="navbar-logo-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <div class="navbar-logo-text">
                        <span>SmartWaste</span>
                        <span>Management System</span>
                    </div>
                </a>

                <ul class="navbar-menu" id="navMenu">
                    <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="#features"><i class="fas fa-star"></i> Features</a></li>
                    <li><a href="#about"><i class="fas fa-info-circle"></i> About</a></li>
                    <li><a href="#reports"><i class="fas fa-chart-bar"></i> Reports</a></li>
                    <li><a href="#contact"><i class="fas fa-phone"></i> Contact</a></li>
                    <li><a href="main.php" class="navbar-btn"><i class="fas fa-rocket"></i> Get Started</a></li>
                </ul>

                <button class="hamburger" id="hamburger" onclick="toggleMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ============================================
         HERO SECTION
    ============================================ -->
    <section id="home">
        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>
        <div class="hero-blob hero-blob-3"></div>

        <div class="container">
            <div class="hero-inner">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-recycle"></i>
                        Next Generation Waste Management
                    </div>

                    <h1 class="hero-heading">
                        Smart <span>Waste Collection</span> Management System
                    </h1>

                    <p class="hero-sub">
                        Making cities cleaner through intelligent waste collection, complaint management and real-time monitoring. Join thousands of citizens building a greener tomorrow.
                    </p>

                    <div class="hero-buttons">
                        <a href="main.php" class="btn btn-primary">
                            <i class="fas fa-rocket"></i>
                            Get Started
                        </a>
                        <a href="#features" class="btn btn-secondary">
                            <i class="fas fa-play-circle"></i>
                            Learn More
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-number">1250+</div>
                            <div class="hero-stat-label">Complaints</div>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">98%</div>
                            <div class="hero-stat-label">Resolved</div>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">50+</div>
                            <div class="hero-stat-label">Collectors</div>
                        </div>
                    </div>
                </div>

                <div class="hero-illustration">
                    <div class="hero-illustration-inner">
                        <!-- Hero SVG Illustration -->
                        <div class="hero-svg-wrapper">
                            <svg viewBox="0 0 520 480" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <!-- Background Circle -->
                                <circle cx="260" cy="240" r="220" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                                <circle cx="260" cy="240" r="180" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>

                                <!-- City Buildings -->
                                <rect x="60" y="180" width="50" height="220" rx="4" fill="rgba(255,255,255,0.12)"/>
                                <rect x="65" y="190" width="12" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="83" y="190" width="12" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="65" y="215" width="12" height="15" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="83" y="215" width="12" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="65" y="240" width="12" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="83" y="240" width="12" height="15" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="65" y="265" width="12" height="15" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="83" y="265" width="12" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>

                                <rect x="120" y="140" width="70" height="260" rx="4" fill="rgba(255,255,255,0.1)"/>
                                <rect x="128" y="152" width="16" height="20" rx="2" fill="rgba(255,255,255,0.18)"/>
                                <rect x="152" y="152" width="16" height="20" rx="2" fill="rgba(255,255,255,0.25)"/>
                                <rect x="128" y="182" width="16" height="20" rx="2" fill="rgba(255,255,255,0.22)"/>
                                <rect x="152" y="182" width="16" height="20" rx="2" fill="rgba(255,255,255,0.18)"/>
                                <rect x="128" y="212" width="16" height="20" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="152" y="212" width="16" height="20" rx="2" fill="rgba(255,255,255,0.25)"/>
                                <rect x="128" y="242" width="16" height="20" rx="2" fill="rgba(255,255,255,0.22)"/>
                                <rect x="152" y="242" width="16" height="20" rx="2" fill="rgba(255,255,255,0.18)"/>
                                <rect x="128" y="272" width="16" height="20" rx="2" fill="rgba(255,255,255,0.25)"/>
                                <rect x="152" y="272" width="16" height="20" rx="2" fill="rgba(255,255,255,0.15)"/>

                                <rect x="380" y="160" width="60" height="240" rx="4" fill="rgba(255,255,255,0.1)"/>
                                <rect x="388" y="172" width="14" height="18" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="410" y="172" width="14" height="18" rx="2" fill="rgba(255,255,255,0.25)"/>
                                <rect x="388" y="200" width="14" height="18" rx="2" fill="rgba(255,255,255,0.18)"/>
                                <rect x="410" y="200" width="14" height="18" rx="2" fill="rgba(255,255,255,0.22)"/>
                                <rect x="388" y="228" width="14" height="18" rx="2" fill="rgba(255,255,255,0.25)"/>
                                <rect x="410" y="228" width="14" height="18" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="388" y="256" width="14" height="18" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="410" y="256" width="14" height="18" rx="2" fill="rgba(255,255,255,0.22)"/>

                                <rect x="450" y="200" width="45" height="200" rx="4" fill="rgba(255,255,255,0.08)"/>
                                <rect x="457" y="210" width="10" height="14" rx="2" fill="rgba(255,255,255,0.18)"/>
                                <rect x="473" y="210" width="10" height="14" rx="2" fill="rgba(255,255,255,0.22)"/>
                                <rect x="457" y="234" width="10" height="14" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="473" y="234" width="10" height="14" rx="2" fill="rgba(255,255,255,0.2)"/>
                                <rect x="457" y="258" width="10" height="14" rx="2" fill="rgba(255,255,255,0.22)"/>
                                <rect x="473" y="258" width="10" height="14" rx="2" fill="rgba(255,255,255,0.15)"/>

                                <!-- Ground Road -->
                                <rect x="30" y="395" width="460" height="25" rx="0" fill="rgba(255,255,255,0.06)"/>
                                <rect x="30" y="415" width="460" height="5" rx="0" fill="rgba(255,255,255,0.04)"/>
                                <!-- Road Markings -->
                                <rect x="100" y="403" width="40" height="4" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="180" y="403" width="40" height="4" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="260" y="403" width="40" height="4" rx="2" fill="rgba(255,255,255,0.15)"/>
                                <rect x="340" y="403" width="40" height="4" rx="2" fill="rgba(255,255,255,0.15)"/>

                                <!-- Garbage Truck Body -->
                                <rect x="200" y="330" width="160" height="65" rx="8" fill="#10b981"/>
                                <rect x="200" y="330" width="160" height="65" rx="8" fill="url(#truckGrad)"/>
                                <!-- Truck Cab -->
                                <rect x="320" y="310" width="60" height="85" rx="8" fill="#059669"/>
                                <rect x="325" y="318" width="48" height="32" rx="4" fill="rgba(255,255,255,0.25)"/>
                                <!-- Truck Wheels -->
                                <circle cx="240" cy="398" r="16" fill="#1f2937"/>
                                <circle cx="240" cy="398" r="8" fill="rgba(255,255,255,0.3)"/>
                                <circle cx="330" cy="398" r="16" fill="#1f2937"/>
                                <circle cx="330" cy="398" r="8" fill="rgba(255,255,255,0.3)"/>
                                <circle cx="360" cy="398" r="14" fill="#1f2937"/>
                                <circle cx="360" cy="398" r="7" fill="rgba(255,255,255,0.3)"/>
                                <!-- Waste Bin Icon on truck -->
                                <rect x="220" y="345" width="30" height="35" rx="3" fill="rgba(255,255,255,0.25)"/>
                                <rect x="218" y="340" width="34" height="8" rx="2" fill="rgba(255,255,255,0.3)"/>
                                <!-- Speed lines -->
                                <line x1="150" y1="355" x2="195" y2="355" stroke="rgba(255,255,255,0.2)" stroke-width="2" stroke-dasharray="5,5"/>
                                <line x1="130" y1="365" x2="195" y2="365" stroke="rgba(255,255,255,0.15)" stroke-width="2" stroke-dasharray="8,4"/>
                                <line x1="155" y1="375" x2="195" y2="375" stroke="rgba(255,255,255,0.1)" stroke-width="2" stroke-dasharray="5,6"/>

                                <!-- Recycle Symbol (center top) -->
                                <circle cx="260" cy="110" r="55" fill="rgba(16,185,129,0.15)" stroke="rgba(16,185,129,0.3)" stroke-width="2"/>
                                <text x="260" y="125" text-anchor="middle" font-size="44" fill="#10b981">♻</text>

                                <!-- Trees / Plants -->
                                <circle cx="85" cy="380" r="18" fill="rgba(16,185,129,0.6)"/>
                                <circle cx="72" cy="390" r="12" fill="rgba(16,185,129,0.5)"/>
                                <circle cx="98" cy="390" r="12" fill="rgba(4,120,87,0.5)"/>
                                <rect x="82" y="390" width="6" height="10" rx="2" fill="#6b7280"/>

                                <circle cx="440" cy="375" r="20" fill="rgba(16,185,129,0.6)"/>
                                <circle cx="426" cy="386" r="14" fill="rgba(16,185,129,0.5)"/>
                                <circle cx="454" cy="386" r="13" fill="rgba(4,120,87,0.5)"/>
                                <rect x="437" y="386" width="6" height="12" rx="2" fill="#6b7280"/>

                                <!-- Location Pins -->
                                <circle cx="180" cy="280" r="12" fill="#ef4444"/>
                                <circle cx="180" cy="280" r="6" fill="rgba(255,255,255,0.8)"/>
                                <line x1="180" y1="292" x2="180" y2="305" stroke="#ef4444" stroke-width="2"/>

                                <circle cx="340" cy="260" r="12" fill="#f59e0b"/>
                                <circle cx="340" cy="260" r="6" fill="rgba(255,255,255,0.8)"/>
                                <line x1="340" y1="272" x2="340" y2="285" stroke="#f59e0b" stroke-width="2"/>

                                <!-- Connection Lines -->
                                <path d="M180 280 Q260 220 340 260" stroke="rgba(255,255,255,0.2)" stroke-width="1" stroke-dasharray="5,5" fill="none"/>

                                <!-- WiFi/Signal Waves from truck -->
                                <path d="M370 318 Q380 310 390 318" stroke="rgba(255,255,255,0.4)" stroke-width="2" fill="none"/>
                                <path d="M365 312 Q380 300 395 312" stroke="rgba(255,255,255,0.3)" stroke-width="2" fill="none"/>
                                <path d="M360 306 Q380 290 400 306" stroke="rgba(255,255,255,0.2)" stroke-width="2" fill="none"/>

                                <!-- Stars/Sparkles -->
                                <circle cx="100" cy="160" r="3" fill="rgba(255,255,255,0.6)"/>
                                <circle cx="430" cy="140" r="2" fill="rgba(255,255,255,0.5)"/>
                                <circle cx="480" cy="200" r="3" fill="rgba(110,231,183,0.6)"/>
                                <circle cx="50" cy="250" r="2" fill="rgba(255,255,255,0.4)"/>
                                <circle cx="490" cy="320" r="2" fill="rgba(255,255,255,0.5)"/>

                                <!-- Gradient Defs -->
                                <defs>
                                    <linearGradient id="truckGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#34d399" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#059669" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <!-- Floating Badges -->
                        <div class="float-badge float-badge-1">
                            <i class="fas fa-check-circle" style="color:#6ee7b7;"></i>
                            Complaint Resolved!
                        </div>

                        <div class="float-badge float-badge-2">
                            <i class="fas fa-map-marker-alt" style="color:#fbbf24;"></i>
                            Live Tracking
                        </div>

                        <div class="float-badge float-badge-3">
                            <i class="fas fa-shield-alt" style="color:#60a5fa;"></i>
                            100% Secure
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         FEATURES SECTION
    ============================================ -->
    <section id="features">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-star"></i>
                    Premium Features
                </div>
                <h2 class="section-title reveal">
                    Everything You Need for <br><span>Smart Waste Management</span>
                </h2>
                <p class="section-subtitle reveal">
                    Our comprehensive platform provides all the tools needed for efficient, modern, and sustainable waste collection management.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card reveal delay-100">
                    <div class="feature-icon-wrap feature-icon-1">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Smart Complaint Registration</h3>
                    <p>Citizens can easily register waste complaints with location, photos and detailed descriptions through our intuitive interface.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-200">
                    <div class="feature-icon-wrap feature-icon-2">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Live Complaint Tracking</h3>
                    <p>Track your complaint status in real-time from registration to resolution with live updates and notifications.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-300">
                    <div class="feature-icon-wrap feature-icon-3">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Admin Dashboard</h3>
                    <p>Powerful administrator control panel with complete oversight of all complaints, collectors, and system performance.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-400">
                    <div class="feature-icon-wrap feature-icon-4">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Collector Assignment</h3>
                    <p>Intelligent system for assigning waste collectors to complaints based on location, availability and workload.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-500">
                    <div class="feature-icon-wrap feature-icon-5">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Reports & Analytics</h3>
                    <p>Comprehensive reports and visual analytics to monitor performance, trends and collection efficiency.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-600">
                    <div class="feature-icon-wrap feature-icon-6">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Friendly</h3>
                    <p>Fully responsive design optimized for smartphones and tablets, making waste reporting possible from anywhere.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-100">
                    <div class="feature-icon-wrap feature-icon-7">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Secure Login</h3>
                    <p>Multi-role secure authentication system for citizens, collectors and administrators with encrypted data protection.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-200">
                    <div class="feature-icon-wrap feature-icon-8">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Fast Performance</h3>
                    <p>Lightning-fast system with optimized database queries ensuring quick response times even with large data volumes.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>

                <div class="feature-card reveal delay-300">
                    <div class="feature-icon-wrap feature-icon-9">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3>Eco-Friendly Solution</h3>
                    <p>Promoting environmental sustainability through efficient waste management and reduction of collection redundancies.</p>
                    <span class="feature-arrow">Explore <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         HOW IT WORKS SECTION
    ============================================ -->
    <section id="how-it-works">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-cogs"></i>
                    Simple Process
                </div>
                <h2 class="section-title reveal">
                    How It <span>Works</span>
                </h2>
                <p class="section-subtitle reveal">
                    Our streamlined 4-step process ensures every waste complaint is handled efficiently and resolved promptly.
                </p>
            </div>

            <div class="steps-grid">
                <div class="steps-connector"></div>

                <div class="step-card reveal delay-100">
                    <div class="step-number-wrap">
                        <div class="step-ring">
                            <span class="step-number">01</span>
                            <div class="step-icon"><i class="fas fa-user"></i></div>
                        </div>
                    </div>
                    <div class="step-badge">Citizen</div>
                    <h3>User Registers Complaint</h3>
                    <p>Citizens report waste issues through the system, providing location details and complaint description for quick resolution.</p>
                </div>

                <div class="step-card reveal delay-200">
                    <div class="step-number-wrap">
                        <div class="step-ring">
                            <span class="step-number">02</span>
                            <div class="step-icon"><i class="fas fa-shield-alt"></i></div>
                        </div>
                    </div>
                    <div class="step-badge">Admin</div>
                    <h3>Admin Reviews Complaint</h3>
                    <p>Administrator reviews the complaint, verifies the details and prioritizes it based on urgency and location.</p>
                </div>

                <div class="step-card reveal delay-300">
                    <div class="step-number-wrap">
                        <div class="step-ring">
                            <span class="step-number">03</span>
                            <div class="step-icon"><i class="fas fa-hard-hat"></i></div>
                        </div>
                    </div>
                    <div class="step-badge">Collector</div>
                    <h3>Collector Assigned</h3>
                    <p>The nearest available waste collector is assigned to the complaint and notified immediately for dispatch.</p>
                </div>

                <div class="step-card reveal delay-400">
                    <div class="step-number-wrap">
                        <div class="step-ring">
                            <span class="step-number">04</span>
                            <div class="step-icon"><i class="fas fa-check-double"></i></div>
                        </div>
                    </div>
                    <div class="step-badge">Resolved</div>
                    <h3>Waste Collected Successfully</h3>
                    <p>Collector arrives at location, collects the waste and marks the complaint as resolved with confirmation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         ABOUT SECTION
    ============================================ -->
    <section id="about">
        <div class="container">
            <div class="about-inner">
                <div class="about-visual reveal-left">
                    <div class="about-main-card">
                        <h2>🌱 Building a Greener City Together</h2>
                        <p>Our Smart Waste Collection Management System bridges the gap between citizens and waste management authorities, creating a cleaner and more sustainable urban environment for everyone.</p>

                        <div style="display:flex;gap:24px;margin-top:32px;position:relative;z-index:1;">
                            <div style="text-align:center;">
                                <div style="font-size:2rem;font-weight:900;color:#6ee7b7;">95%</div>
                                <div style="font-size:0.8rem;opacity:0.7;">Efficiency</div>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                            <div style="text-align:center;">
                                <div style="font-size:2rem;font-weight:900;color:#6ee7b7;">24/7</div>
                                <div style="font-size:0.8rem;opacity:0.7;">Monitoring</div>
                            </div>
                            <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                            <div style="text-align:center;">
                                <div style="font-size:2rem;font-weight:900;color:#6ee7b7;">100+</div>
                                <div style="font-size:0.8rem;opacity:0.7;">Locations</div>
                            </div>
                        </div>
                    </div>

                    <div class="about-float-card about-float-card-1">
                        <div class="about-float-icon" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">🏆</div>
                        <div class="about-float-text">
                            <strong>Award Winning</strong>
                            <span>Best City Solution 2024</span>
                        </div>
                    </div>

                    <div class="about-float-card about-float-card-2">
                        <div class="about-float-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">⚡</div>
                        <div class="about-float-text">
                            <strong>Fast Response</strong>
                            <span>Avg. 2 hours resolution</span>
                        </div>
                    </div>
                </div>

                <div class="about-content reveal-right">
                    <div class="section-badge">
                        <i class="fas fa-info-circle"></i>
                        About Us
                    </div>
                    <h2 class="section-title">Smart Solution for <span>Cleaner Cities</span></h2>
                    <p>Our Smart Waste Collection Management System helps citizens report waste problems easily while enabling administrators to efficiently assign collectors and monitor the entire waste collection process in real time.</p>

                    <div class="about-cards-grid">
                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h4>Our Mission</h4>
                            <p>To provide an intelligent platform that makes waste management efficient, transparent and accessible for all citizens.</p>
                        </div>

                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h4>Our Vision</h4>
                            <p>To create cleaner, healthier and more sustainable cities through smart technology-driven waste management.</p>
                        </div>

                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <h4>Objectives</h4>
                            <p>Reduce complaint resolution time, improve collector efficiency and enhance overall city cleanliness metrics.</p>
                        </div>

                        <div class="about-card">
                            <div class="about-card-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h4>Eco Impact</h4>
                            <p>Minimize environmental pollution through proactive waste management and data-driven collection strategies.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         WHY CHOOSE US
    ============================================ -->
    <section id="why-us">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-trophy"></i>
                    Why Choose Us
                </div>
                <h2 class="section-title reveal" style="color:white;">
                    Why We Are The <span>Best Choice</span>
                </h2>
                <p class="section-subtitle reveal" style="color:rgba(255,255,255,0.7);">
                    We provide the most comprehensive, reliable and user-friendly waste management solution available today.
                </p>
            </div>

            <div class="why-grid">
                <div class="why-card reveal delay-100">
                    <div class="why-icon">⚡</div>
                    <h3>Faster Complaint Resolution</h3>
                    <p>Our smart assignment system reduces average complaint resolution time by 75% compared to traditional methods.</p>
                </div>

                <div class="why-card reveal delay-200">
                    <div class="why-icon">📡</div>
                    <h3>Real-Time Monitoring</h3>
                    <p>Live dashboard gives administrators instant visibility into all active complaints, collector status and performance metrics.</p>
                </div>

                <div class="why-card reveal delay-300">
                    <div class="why-icon">🧠</div>
                    <h3>Intelligent Management</h3>
                    <p>Data-driven insights and analytics help optimize waste collection routes and resource allocation automatically.</p>
                </div>

                <div class="why-card reveal delay-400">
                    <div class="why-icon">🔒</div>
                    <h3>Secure Platform</h3>
                    <p>Enterprise-grade security with encrypted data, secure authentication and complete audit trails for all activities.</p>
                </div>

                <div class="why-card reveal delay-500">
                    <div class="why-icon">😊</div>
                    <h3>User Friendly</h3>
                    <p>Intuitive interface designed for all users regardless of technical expertise, ensuring wide adoption across communities.</p>
                </div>

                <div class="why-card reveal delay-600">
                    <div class="why-icon">🏙️</div>
                    <h3>Better City Cleanliness</h3>
                    <p>Measurable improvement in city cleanliness scores with data-backed evidence of systematic waste management progress.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         REPORTS & ANALYTICS
    ============================================ -->
    <section id="reports">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-chart-bar"></i>
                    Reports & Analytics
                </div>
                <h2 class="section-title reveal">
                    Powerful <span>Dashboard Overview</span>
                </h2>
                <p class="section-subtitle reveal">
                    Comprehensive statistics and analytics to monitor system performance and waste collection efficiency.
                </p>
            </div>

            <div class="reports-stats-grid">
                <div class="stat-card reveal delay-100">
                    <div class="stat-icon stat-icon-1">📋</div>
                    <div class="stat-info">
                        <h3>1,250</h3>
                        <p>Total Complaints</p>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +12% this month</div>
                    </div>
                </div>

                <div class="stat-card reveal delay-200">
                    <div class="stat-icon stat-icon-2">⏳</div>
                    <div class="stat-info">
                        <h3>145</h3>
                        <p>Pending Complaints</p>
                        <div class="stat-trend" style="color:#f59e0b;"><i class="fas fa-clock"></i> Under review</div>
                    </div>
                </div>

                <div class="stat-card reveal delay-300">
                    <div class="stat-icon stat-icon-3">✅</div>
                    <div class="stat-info">
                        <h3>980</h3>
                        <p>Resolved Complaints</p>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> 98% success rate</div>
                    </div>
                </div>

                <div class="stat-card reveal delay-400">
                    <div class="stat-icon stat-icon-4">👷</div>
                    <div class="stat-info">
                        <h3>32</h3>
                        <p>Active Collectors</p>
                        <div class="stat-trend"><i class="fas fa-circle" style="font-size:0.6rem;"></i> Online now</div>
                    </div>
                </div>

                <div class="stat-card reveal delay-500">
                    <div class="stat-icon stat-icon-5">👥</div>
                    <div class="stat-info">
                        <h3>520</h3>
                        <p>Registered Users</p>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +45 new users</div>
                    </div>
                </div>

                <div class="stat-card reveal delay-600">
                    <div class="stat-icon stat-icon-6">📍</div>
                    <div class="stat-info">
                        <h3>45</h3>
                        <p>Areas Covered</p>
                        <div class="stat-trend"><i class="fas fa-map"></i> Expanding soon</div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid" style="margin-top:24px;">
                <div class="chart-card reveal delay-100">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-title-icon"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <h3>Monthly Complaints</h3>
                                <p>Trend over 6 months</p>
                            </div>
                        </div>
                        <span class="chart-badge">📈 Line Chart</span>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <div class="chart-card reveal delay-200">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-title-icon"><i class="fas fa-chart-bar"></i></div>
                            <div>
                                <h3>Resolved vs Pending</h3>
                                <p>Comparison by month</p>
                            </div>
                        </div>
                        <span class="chart-badge">📊 Bar Chart</span>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <div class="chart-card reveal delay-300">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-title-icon"><i class="fas fa-chart-pie"></i></div>
                            <div>
                                <h3>Complaint Categories</h3>
                                <p>Distribution by type</p>
                            </div>
                        </div>
                        <span class="chart-badge">🥧 Pie Chart</span>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                <div class="chart-card reveal delay-400">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-title-icon"><i class="fas fa-chart-area"></i></div>
                            <div>
                                <h3>Collection Performance</h3>
                                <p>Efficiency over time</p>
                            </div>
                        </div>
                        <span class="chart-badge">📉 Area Chart</span>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="areaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         PROJECT HIGHLIGHTS
    ============================================ -->
    <section id="highlights">
        <div class="container">
            <div class="highlights-grid">
                <div class="highlight-item reveal delay-100">
                    <span class="highlight-number" data-target="1000">0</span>
                    <span class="highlight-label">Complaints Solved</span>
                </div>

                <div class="highlight-item reveal delay-200">
                    <span class="highlight-number" data-target="95" data-suffix="%">0%</span>
                    <span class="highlight-label">Efficiency Rate</span>
                </div>

                <div class="highlight-item reveal delay-300">
                    <span class="highlight-number" style="font-size:3rem;">24/7</span>
                    <span class="highlight-label">System Monitoring</span>
                </div>

                <div class="highlight-item reveal delay-400">
                    <span class="highlight-number" data-target="50" data-suffix="+">0</span>
                    <span class="highlight-label">Active Collectors</span>
                </div>

                <div class="highlight-item reveal delay-500">
                    <span class="highlight-number" data-target="100" data-suffix="+">0</span>
                    <span class="highlight-label">Locations Covered</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         TESTIMONIALS
    ============================================ -->
    <section id="testimonials">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-heart"></i>
                    Testimonials
                </div>
                <h2 class="section-title reveal">
                    What Our Users <span>Say</span>
                </h2>
                <p class="section-subtitle reveal">
                    Real feedback from citizens, collectors and administrators who use our system daily.
                </p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card reveal delay-100">
                    <div class="testimonial-badge">Citizen ⭐</div>
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "This system has completely changed how our neighbourhood handles waste problems. I registered a complaint in the morning and it was resolved by afternoon! The tracking feature is incredible — I could see exactly what was happening in real time."
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar testimonial-avatar-1">👩</div>
                        <div class="testimonial-author-info">
                            <strong>Sarah Malik</strong>
                            <span>Resident, Green Valley</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal delay-200">
                    <div class="testimonial-badge">Collector 🚛</div>
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Before this system, we had no organized way to receive or manage complaints. Now I get clear assignments with precise locations, and updating complaint status is simple. It has made my daily work much more efficient and organized."
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar testimonial-avatar-2">👨</div>
                        <div class="testimonial-author-info">
                            <strong>Ahmed Khan</strong>
                            <span>Waste Collector, Zone B</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal delay-300">
                    <div class="testimonial-badge">Admin 🛡️</div>
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="testimonial-text">
                        "The admin dashboard gives me complete visibility over all operations. The analytics and reports help us make better decisions about resource allocation. Our complaint resolution rate has improved by over 80% since implementing this system."
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar testimonial-avatar-3">👩‍💼</div>
                        <div class="testimonial-author-info">
                            <strong>Ms. Fatima Zaidi</strong>
                            <span>City Administrator</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         FAQ SECTION
    ============================================ -->
    <section id="faq" style="padding:120px 0;background:white;">
        <div class="container">
            <div class="section-header">
                <div class="section-badge reveal">
                    <i class="fas fa-question-circle"></i>
                    FAQ
                </div>
                <h2 class="section-title reveal">
                    Frequently Asked <span>Questions</span>
                </h2>
                <p class="section-subtitle reveal">
                    Got questions? We have answers. Find everything you need to know about our Smart Waste Management System.
                </p>
            </div>

            <div class="faq-inner">
                <div class="faq-item reveal delay-100">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        How do I register a complaint?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Simply login to your citizen account, navigate to the complaint section and click "Register New Complaint". Fill in the details including your location, waste type and description. Your complaint will be submitted instantly and you will receive a confirmation with a tracking ID.
                        </div>
                    </div>
                </div>

                <div class="faq-item reveal delay-200">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Can I track my complaint status in real time?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Yes! Our system provides live complaint tracking. Once your complaint is registered, you can monitor its status from your dashboard. You will see updates including when it is reviewed by admin, when a collector is assigned, and when the waste is successfully collected.
                        </div>
                    </div>
                </div>

                <div class="faq-item reveal delay-300">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        How are waste collectors assigned to complaints?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            The admin reviews incoming complaints and assigns the most suitable available collector based on proximity to the complaint location, current workload and collector expertise. The assigned collector receives an immediate notification with all complaint details.
                        </div>
                    </div>
                </div>

                <div class="faq-item reveal delay-400">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        What types of waste complaints can I register?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            You can register complaints for various waste types including household garbage overflow, illegal dumping, blocked drains with waste, overflowing public bins, construction waste dumping and other environmental waste issues in your area.
                        </div>
                    </div>
                </div>

                <div class="faq-item reveal delay-500">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        How long does complaint resolution take?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Our average resolution time is 2-4 hours for standard complaints. Urgent complaints are prioritized and resolved within 1-2 hours. Complex issues may require additional time, but you will always be kept informed of progress through real-time status updates.
                        </div>
                    </div>
                </div>

                <div class="faq-item reveal delay-600">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        Is my personal data secure on this platform?
                        <span class="faq-icon"><i class="fas fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Absolutely. We use industry-standard encryption for all data transmission and storage. Your personal information is never shared with unauthorized parties. Our system has secure login with password encryption and complete access control for all user roles.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         CALL TO ACTION SECTION
    ============================================ -->
    <section id="contact">
        <div class="cta-glow-1"></div>
        <div class="cta-glow-2"></div>

        <div class="container">
            <div class="cta-content">
                <div class="cta-badge reveal">
                    <i class="fas fa-leaf"></i>
                    Join The Movement
                </div>

                <h2 class="cta-heading reveal">
                    Join Us in Making Our <br><span>City Cleaner</span>
                </h2>

                <p class="cta-sub reveal">
                    Be part of the smart waste management revolution. Register today and help build a cleaner, greener and more sustainable city for everyone.
                </p>

                <div class="cta-buttons reveal">
                    <a href="main.php" class="btn btn-primary" style="padding:18px 48px;font-size:1.1rem;">
                        <i class="fas fa-rocket"></i>
                        Get Started Now
                    </a>
                    <a href="#features" class="btn btn-secondary" style="padding:18px 48px;font-size:1.1rem;">
                        <i class="fas fa-play"></i>
                        Learn More
                    </a>
                </div>

                <div class="cta-features reveal">
                    <div class="cta-feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Free to Register</span>
                    </div>
                    <div class="cta-feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>No Setup Required</span>
                    </div>
                    <div class="cta-feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Real-Time Tracking</span>
                    </div>
                    <div class="cta-feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>24/7 Monitoring</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         FOOTER
    ============================================ -->
    <footer>
        <div class="container">
            <div class="footer-inner">
                <!-- Brand -->
                <div class="footer-col footer-brand">
                    <a href="#home" class="navbar-logo" style="margin-bottom:16px;display:inline-flex;">
                        <div class="navbar-logo-icon">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <div class="navbar-logo-text">
                            <span style="color:white;">SmartWaste</span>
                            <span>Management System</span>
                        </div>
                    </a>
                    <p>Building smarter, cleaner and more sustainable cities through intelligent waste collection management technology.</p>
                    <div class="footer-social">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#home"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Features</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="#how-it-works"><i class="fas fa-chevron-right"></i> How It Works</a></li>
                        <li><a href="#reports"><i class="fas fa-chevron-right"></i> Reports</a></li>
                        <li><a href="main.php"><i class="fas fa-chevron-right"></i> Get Started</a></li>
                    </ul>
                </div>

                <!-- Features -->
                <div class="footer-col">
                    <h4>Features</h4>
                    <ul class="footer-links">
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Complaint Registration</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Live Tracking</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Admin Dashboard</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Collector Management</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Analytics Reports</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Secure Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-col">
                    <h4>Contact Us</h4>
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>City Municipal Corporation,<br>Smart City Zone, Pakistan</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+92-300-0000000</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@smartwaste.gov.pk</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Mon - Sat: 8:00 AM – 6:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2024 <span>SmartWaste Management System</span>. All Rights Reserved. Made with <span>♥</span> for a Greener City.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================
         JAVASCRIPT
    ============================================ -->
    <script>
        // ============================================
        // LOADING SCREEN
        // ============================================
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1800);
        });

        // ============================================
        // NAVBAR SCROLL EFFECT
        // ============================================
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Back to top button
            const backToTop = document.getElementById('backToTop');
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        // ============================================
        // MOBILE MENU TOGGLE
        // ============================================
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            const hamburger = document.getElementById('hamburger');
            menu.classList.toggle('open');
            hamburger.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
        }

        // Close menu when clicking a link
        document.querySelectorAll('#navMenu a').forEach(function(link) {
            link.addEventListener('click', function() {
                const menu = document.getElementById('navMenu');
                const hamburger = document.getElementById('hamburger');
                menu.classList.remove('open');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // ============================================
        // SCROLL REVEAL
        // ============================================
        function revealOnScroll() {
            var reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
            reveals.forEach(function(el) {
                var windowHeight = window.innerHeight;
                var elementTop = el.getBoundingClientRect().top;
                var elementVisible = 100;

                if (elementTop < windowHeight - elementVisible) {
                    el.classList.add('visible');
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);
        revealOnScroll();

        // ============================================
        // FAQ ACCORDION
        // ============================================
        function toggleFAQ(btn) {
            var item = btn.closest('.faq-item');
            var isActive = item.classList.contains('active');

            // Close all
            document.querySelectorAll('.faq-item').forEach(function(faqItem) {
                faqItem.classList.remove('active');
            });

            // Open clicked if wasn't active
            if (!isActive) {
                item.classList.add('active');
            }
        }

        // ============================================
        // COUNTER ANIMATION
        // ============================================
        function animateCounter(el, target, suffix) {
            var start = 0;
            var duration = 2000;
            var startTime = null;
            suffix = suffix || '';

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                var current = Math.floor(eased * target);

                if (target >= 1000) {
                    el.textContent = (current >= 1000 ? (current / 1000).toFixed(0) + 'K+' : current + suffix);
                } else {
                    el.textContent = current + suffix;
                }

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    if (target >= 1000) {
                        el.textContent = Math.floor(target / 1000) + 'K+';
                    } else {
                        el.textContent = target + suffix;
                    }
                }
            }
            requestAnimationFrame(step);
        }

        var countersDone = false;
        function checkCounters() {
            if (countersDone) return;
            var highlightsSection = document.getElementById('highlights');
            if (!highlightsSection) return;
            var rect = highlightsSection.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                countersDone = true;
                document.querySelectorAll('.highlight-number[data-target]').forEach(function(el) {
                    var target = parseInt(el.getAttribute('data-target'));
                    var suffix = el.getAttribute('data-suffix') || '';
                    animateCounter(el, target, suffix);
                });
            }
        }
        window.addEventListener('scroll', checkCounters);
        checkCounters();

        // ============================================
        // CHART.JS CHARTS
        // ============================================
        var chartDefaults = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: { family: 'Inter', size: 11 },
                        color: '#6b7280',
                        boxWidth: 12,
                        padding: 12
                    }
                }
            }
        };

        // LINE CHART
        var lineCtx = document.getElementById('lineChart');
        if (lineCtx) {
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Complaints',
                        data: [185, 210, 240, 195, 280, 260],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.08)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: Object.assign({}, chartDefaults, {
                    scales: {
                        x: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#9ca3af', font: { size: 11 } }
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#9ca3af', font: { size: 11 } },
                            beginAtZero: true
                        }
                    }
                })
            });
        }

        // BAR CHART
        var barCtx = document.getElementById('barChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'Resolved',
                            data: [160, 190, 220, 175, 255, 240],
                            backgroundColor: 'rgba(16,185,129,0.8)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: 'Pending',
                            data: [25, 20, 20, 20, 25, 20],
                            backgroundColor: 'rgba(245,158,11,0.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    ]
                },
                options: Object.assign({}, chartDefaults, {
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af', font: { size: 11 } }
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#9ca3af', font: { size: 11 } },
                            beginAtZero: true
                        }
                    }
                })
            });
        }

        // PIE CHART
        var pieCtx = document.getElementById('pieChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Garbage Overflow', 'Illegal Dumping', 'Blocked Drains', 'Public Bins', 'Construction'],
                    datasets: [{
                        data: [35, 25, 18, 14, 8],
                        backgroundColor: [
                            '#10b981',
                            '#3b82f6',
                            '#f59e0b',
                            '#8b5cf6',
                            '#ef4444'
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: Object.assign({}, chartDefaults, {
                    cutout: '65%'
                })
            });
        }

        // AREA CHART
        var areaCtx = document.getElementById('areaChart');
        if (areaCtx) {
            new Chart(areaCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'Collection Efficiency %',
                            data: [78, 82, 85, 88, 92, 95],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.12)',
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Response Rate %',
                            data: [70, 75, 80, 82, 88, 91],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.08)',
                            borderWidth: 3,
                            pointBackgroundColor: '#3b82f6',
                            pointRadius: 4,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: Object.assign({}, chartDefaults, {
                    scales: {
                        x: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#9ca3af', font: { size: 11 } }
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11 },
                                callback: function(val) { return val + '%'; }
                            },
                            min: 60,
                            max: 100
                        }
                    }
                })
            });
        }

        // ============================================
        // SMOOTH SCROLL FOR ANCHOR LINKS
        // ============================================
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    var offset = 80;
                    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            });
        });

        // ============================================
        // BUTTON RIPPLE EFFECT
        // ============================================
        document.querySelectorAll('.btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var ripple = document.createElement('span');
                var rect = this.getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);
                var x = e.clientX - rect.left - size / 2;
                var y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = 'position:absolute;width:' + size + 'px;height:' + size + 'px;left:' + x + 'px;top:' + y + 'px;background:rgba(255,255,255,0.3);border-radius:50%;transform:scale(0);animation:ripple 0.6s linear;pointer-events:none;';

                this.appendChild(ripple);
                setTimeout(function() { ripple.remove(); }, 700);
            });
        });

        // Add ripple keyframe
        var style = document.createElement('style');
        style.textContent = '@keyframes ripple{to{transform:scale(4);opacity:0;}}';
        document.head.appendChild(style);
    </script>

</body>
</html>