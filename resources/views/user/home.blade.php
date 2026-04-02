@extends('layouts.user_master')

@section('user_content')
<style>
    :root {
        --mmt-blue: #008cff;
        --mmt-dark: #4a4a4a;
        --mmt-light-grey: #e7e7e7;
        --mmt-bg: #f2f2f2;
        --brand-accent: #ff4e00;
    }

    body {
        background-color: var(--mmt-bg);
        font-family: 'Inter', sans-serif;
    }

    /* =========================================
       🔥 NAVBAR OVERRIDES (DARK/LIGHT FIXES) 🔥
       ========================================= */
    .navbar .nav-link {
        color: #ffffff !important;
        font-weight: 600;
        padding: 8px 18px !important;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .navbar .nav-link:hover {
        background: #ffffff !important;
        color: var(--mmt-blue) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .navbar.scrolled .nav-link {
        color: var(--mmt-blue) !important;
    }

    .navbar.scrolled .nav-link:hover {
        background: var(--mmt-blue) !important;
        color: #ffffff !important;
    }

    .navbar-toggler {
        background-color: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(5px);
        padding: 8px 12px;
        border-radius: 8px;
    }

    .navbar-toggler-icon {
        filter: brightness(0) invert(1);
    }

    .navbar.scrolled .navbar-toggler {
        border: 1px solid var(--mmt-blue);
    }

    .navbar.scrolled .navbar-toggler-icon {
        filter: brightness(0);
    }

    @media (max-width: 991px) {
        .navbar-collapse {
            background: #fff;
            border-radius: 16px;
            padding: 15px;
            margin-top: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            position: absolute;
            width: 100%;
            left: 0;
            top: 100%;
            border: 1px solid #eee;
        }

        .navbar-collapse .nav-link {
            color: var(--mmt-blue) !important;
            border-radius: 12px;
            padding: 12px 20px !important;
            margin-bottom: 5px;
        }

        .navbar-collapse .nav-link:hover {
            background: rgba(0, 140, 255, 0.05) !important;
            color: var(--mmt-blue) !important;
            box-shadow: none;
        }

        .main-footer .text-end {
            text-align: left !important;
            margin-top: 15px;
        }
    }

    /* --- 1. HERO SECTION (MMT STYLE DARK BACKGROUND) --- */
    .hero-mmt {
        position: relative;
        height: 100vh;
        min-height: 700px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding-top: 50px;
    }

    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://loremflickr.com/1920/1080/city,night') no-repeat center center;
        background-size: cover;
        z-index: -2;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: -1;
    }

    /* --- 2. EXACT MMT WIDGET CONTAINER --- */
    .mmt-widget-container {
        position: relative;
        width: 95%;
        max-width: 1200px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        z-index: 100;
        margin-bottom: -50px;
    }

    /* --- 3. MMT TABS (ALL 12 TABS) --- */
    .mmt-tabs-wrapper {
        background: #ffffff;
        border-radius: 10px 10px 0 0;
        padding: 5px 15px 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        display: flex;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .mmt-tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    .mmt-tab {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        color: #4a4a4a;
        cursor: pointer;
        transition: 0.2s;
        border-bottom: 3px solid transparent;
        min-width: 85px;
        text-align: center;
        opacity: 0.8;
    }

    .mmt-tab i {
        font-size: 26px;
        color: #888;
        margin-bottom: 5px;
        transition: 0.2s;
    }

    .mmt-tab span {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .mmt-tab:hover {
        opacity: 1;
        color: var(--mmt-blue);
    }

    .mmt-tab:hover i {
        color: var(--mmt-blue);
    }

    .mmt-tab.active {
        border-bottom-color: var(--mmt-blue);
        color: var(--mmt-blue);
        opacity: 1;
    }

    .mmt-tab.active i {
        color: var(--mmt-blue);
    }

    /* --- 4. FORM BODY & RADIOS --- */
    .mmt-widget-body {
        padding: 30px 20px 45px 20px;
    }

    .mmt-radios {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        margin-left: 10px;
    }

    .mmt-radio-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        font-weight: 600;
        color: #4a4a4a;
        cursor: pointer;
    }

    .mmt-radio-item input[type="radio"] {
        accent-color: var(--mmt-blue);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    /* --- 5. THE MAIN INPUT GRID --- */
    .mmt-input-grid {
        display: flex;
        border: 1px solid var(--mmt-light-grey);
        border-radius: 10px;
        position: relative;
    }

    .mmt-input-box {
        padding: 15px 20px;
        border-right: 1px solid var(--mmt-light-grey);
        cursor: pointer;
        transition: 0.2s;
    }

    .mmt-input-box:hover {
        background-color: #eaf5ff;
    }

    .mmt-input-box:last-child {
        border-right: none;
    }

    .box-large { flex: 2; }
    .box-medium { flex: 1.5; }

    .mmt-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #4a4a4a;
        margin-bottom: 5px;
    }

    .mmt-value-input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        padding: 0;
        font-size: 32px;
        font-weight: 900;
        color: #000;
        line-height: 1.1;
        margin-bottom: 5px;
        cursor: pointer;
    }

    .mmt-sub-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #777;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .swap-icon {
        position: absolute;
        left: 31%;
        top: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        border: 1px solid var(--mmt-light-grey);
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--mmt-blue);
        font-size: 20px;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 5;
        cursor: pointer;
    }

    /* --- 6. SPECIAL FARES SECTION --- */
    .special-fares {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .fare-title {
        font-size: 12px;
        font-weight: 800;
        color: #000;
    }

    .fare-badge {
        padding: 5px 10px;
        border: 1px solid var(--mmt-light-grey);
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        color: #4a4a4a;
        cursor: pointer;
        background: #f9f9f9;
    }

    .fare-badge.active {
        background: #eaf5ff;
        border-color: var(--mmt-blue);
        color: var(--mmt-blue);
    }

    /* --- 7. THE BIG SEARCH BUTTON --- */
    .mmt-search-btn-wrapper {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        text-align: center;
    }

    .mmt-search-btn {
        background: linear-gradient(90deg, #53b2fe, #065af3);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 65px;
        font-size: 24px;
        font-weight: 800;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 140, 255, 0.4);
        transition: 0.3s;
    }

    .mmt-search-btn:hover {
        box-shadow: 0 6px 15px rgba(0, 140, 255, 0.6);
        transform: scale(1.02);
    }

    .explore-more {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* --- MOBILE RESPONSIVENESS --- */
    @media (max-width: 991px) {
        .mmt-input-grid { flex-direction: column; }
        .mmt-input-box { border-right: none; border-bottom: 1px solid var(--mmt-light-grey); }
        .swap-icon { left: auto; right: 20px; top: 15%; transform: translateY(-50%); }
        .mmt-radios { flex-wrap: wrap; }
        .hero-mmt { height: auto; padding: 100px 10px 100px 10px; min-height: 100vh; }
        .mmt-widget-container { margin-bottom: 0; }
        .explore-more { display: none; }
    }

    /* =========================================
       🔥 PREMIUM OFFERS SECTION STYLES 🔥
       ========================================= */
    .offers-section-bg {
        background-color: #f8fafc; 
        padding: 100px 0 60px 0; /* Increased top padding so Hero doesn't overlap it */
    }

    .offer-tabs {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 5px;
        border: none;
    }
    .offer-tabs::-webkit-scrollbar { display: none; }
    
    .offer-tabs .nav-link {
        background: #ffffff;
        color: #64748b;
        border-radius: 50px;
        padding: 8px 24px;
        font-weight: 600;
        font-size: 14px;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .offer-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #0A2239;
    }

    .offer-tabs .nav-link.active {
        background: rgba(255, 78, 0, 0.1);
        color: #FF4E00 !important;
        border-color: #FF4E00;
    }

    .offer-card {
        border-radius: 16px;
        border: 1px solid #f0f0f0;
        background: #ffffff;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        height: 100%;
    }

    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: transparent;
    }

    .offer-img-box {
        overflow: hidden;
        position: relative;
        height: 100%;
        min-height: 180px;
    }

    .offer-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .offer-card:hover .offer-img-box img {
        transform: scale(1.1);
    }

    .offer-title {
        font-weight: 800;
        font-size: 1.15rem;
        color: #0A2239;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .offer-btn {
        color: #FF4E00;
        font-weight: 800;
        text-decoration: none;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        transition: 0.3s;
    }

    .offer-btn i {
        margin-left: 5px;
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .offer-btn:hover { color: #d35400; }
    .offer-btn:hover i { transform: translateX(5px); }

    .section-heading {
        font-family: 'Poppins', sans-serif;
        font-weight: 900;
        font-size: clamp(1.8rem, 4vw, 2.2rem);
        color: #000;
        margin-bottom: 5px;
    }

    /* =========================================
       🔥 ACCOMMODATION SECTION STYLES 🔥
       ========================================= */
    .custom-stay-tabs {
        border-bottom: 2px solid #eaeaea;
        gap: 15px;
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 5px;
    }
    .custom-stay-tabs::-webkit-scrollbar { display: none; } 
    
    .custom-stay-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 10px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: transparent;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .custom-stay-tabs .nav-link i {
        font-size: 28px;
        margin-bottom: 5px;
        color: #888;
        transition: 0.3s;
    }

    .custom-stay-tabs .nav-link:hover { color: #0A2239; }
    .custom-stay-tabs .nav-link.active {
        color: #FF4E00;
        border-bottom: 3px solid #FF4E00;
        background: transparent;
    }
    .custom-stay-tabs .nav-link.active i { color: #FF4E00; }

    .stay-card {
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stay-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .stay-card-img { height: 220px; object-fit: cover; }
    .star-rating i { color: #f39c12; font-size: 14px; }
    
    .btn-view-details {
        background-color: #FF4E00;
        color: white !important;
        border-radius: 50px;
        font-weight: 600;
        padding: 8px 20px;
        font-size: 14px;
        transition: 0.3s;
    }
    .btn-view-details:hover {
        background-color: #e64600;
        transform: scale(1.05);
    }

    /* =========================================
       🔥 RESTAURANT SECTION STYLES 🔥
       ========================================= */
    .food-section-bg {
        background-color: #ffffff;
        padding: 80px 0;
        position: relative;
    }

    .food-card {
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
    }

    .food-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        border-color: transparent;
    }

    .food-img-wrapper { position: relative; height: 200px; overflow: hidden; }
    .food-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .food-card:hover .food-img-wrapper img { transform: scale(1.1); }

    .food-badge {
        position: absolute; top: 15px; left: 15px; padding: 5px 12px; border-radius: 5px;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; z-index: 2;
    }

    .badge-top { background: #1cc88a; color: #fff; }
    .badge-famous { background: #FF4E00; color: #fff; }
    .badge-nearby { background: #008cff; color: #fff; }

    .food-distance {
        position: absolute; bottom: -15px; right: 20px; background: #fff; padding: 5px 15px;
        border-radius: 20px; font-size: 12px; font-weight: 700; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: #FF4E00; z-index: 2;
    }

    .food-rating i { color: #f39c12; font-size: 15px; }
    .food-rating span { font-weight: 700; color: #333; margin-left: 5px; font-size: 14px;}

    .btn-reserve {
        background: transparent; color: #FF4E00 !important; border: 2px solid #FF4E00;
        border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 13px;
        transition: 0.3s; width: 100%; display: block; text-align: center; text-decoration: none;
    }

    .btn-reserve:hover { background: #FF4E00; color: #fff !important; }



    /* =========================================
           🔥 CAB / CAR RENTAL SECTION 🔥
           ========================================= */
        /* Update this in your <style> section */
.car-card { 
    border-radius: 16px; 
    overflow: hidden; 
    background: #fff; 
    border: 1px solid #f0f0f0; 
    transition: all 0.3s ease; 
    height: 100%;
    display: flex; /* 🔥 Ye add karna zaroori hai */
    flex-direction: column; /* 🔥 Isse content vertically align hoga */
}
.car-card:hover { 
    transform: translateY(-8px); 
    box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
    border-color: #FF4E00; 
}
.car-img-wrapper { 
    height: 200px; /* 🔥 Height fix ki taaki image standard rahe */
    width: 100%;
    overflow: hidden;
}
.car-img-wrapper img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.5s ease; 
}
        /* =========================================
           🔥 STATS / DATA COUNTER SECTION 🔥
           ========================================= */
        .stats-section { 
            background: linear-gradient(135deg, #0A2239, #051424); 
            padding: 60px 0; color: white; position: relative; 
        }
        .stat-item { text-align: center; }
        .stat-icon { font-size: 50px; color: #FF4E00; margin-bottom: 15px; display: inline-block; }
        .stat-number { 
            font-size: 40px; font-weight: 900; font-family: 'Poppins', sans-serif; 
            margin-bottom: 5px; line-height: 1;
        }
        .stat-text { 
            font-size: 14px; color: #a0aec0; text-transform: uppercase; 
            letter-spacing: 1.5px; font-weight: 600; 
        }

        /* =========================================
           🔥 CUSTOMER REVIEWS SECTION 🔥
           ========================================= */
        .review-card { 
            background: #fff; border-radius: 16px; padding: 30px 25px; 
            border: 1px solid #f0f0f0; transition: 0.3s; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); height: 100%; position: relative;
        }
        .review-card:hover { 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); transform: translateY(-5px); 
        }
        .review-text { 
            font-size: 14px; color: #4a4a4a; font-style: italic; 
            line-height: 1.7; margin-bottom: 25px; position: relative; z-index: 1;
        }
        .reviewer-info { display: flex; align-items: center; gap: 15px; }
        .reviewer-img { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; }
        .reviewer-name { font-weight: 800; color: #0A2239; font-size: 15px; margin-bottom: 2px; }
        .reviewer-loc { font-size: 12px; color: #64748b; font-weight: 500; }
        .review-quote { 
            font-size: 60px; color: rgba(0, 140, 255, 0.05); 
            position: absolute; top: 15px; right: 20px; z-index: 0; 
        }

    /* ==========================================
       🔥 AI CHATBOT STYLES 🔥
       ========================================== */
    .ai-chat-container { position: fixed; bottom: 30px; right: 30px; z-index: 1050; font-family: 'Inter', sans-serif; }
    .ai-chat-toggle {
        background: linear-gradient(135deg, var(--brand-accent), #e64600); color: white !important;
        border: none; width: 60px; height: 60px; border-radius: 50%; font-size: 28px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 10px 25px rgba(255, 78, 0, 0.4);
    }
    .ai-chat-toggle:hover { transform: scale(1.1); box-shadow: 0 15px 30px rgba(255, 78, 0, 0.5); }

    .ai-chat-window {
        position: absolute; bottom: 80px; right: 0; width: 350px; height: 500px;
        background: #ffffff; border-radius: 20px; display: none; flex-direction: column; overflow: hidden;
        border: 1px solid rgba(0,0,0,0.1); transform-origin: bottom right;
        animation: chatPopUp 0.3s ease forwards; box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        color: #0A2239 !important; text-align: left !important;
    }
    
    @keyframes chatPopUp { 0% { opacity: 0; transform: scale(0.5); } 100% { opacity: 1; transform: scale(1); } }

    .ai-chat-header { background: #0A2239; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
    .ai-avatar {
        background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 24px;
    }
    .chat-title-text { margin: 0; color: #ffffff !important; font-weight: 700; font-size: 16px; }
    .chat-status-text { color: rgba(255,255,255,0.8) !important; font-size: 11px; }
    .btn-close-chat { background: transparent; border: none; color: #ffffff; font-size: 24px; cursor: pointer; opacity: 0.7; transition: 0.2s; }
    .btn-close-chat:hover { opacity: 1; transform: rotate(90deg); }

    .ai-chat-body {
        flex: 1; padding: 20px; overflow-y: auto; background: #f8fafc;
        display: flex; flex-direction: column; gap: 15px; scrollbar-width: thin;
    }
    .ai-chat-body::-webkit-scrollbar { width: 5px; }
    .ai-chat-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; }

    .chat-message { display: flex; flex-direction: column; max-width: 85%; }
    .msg-bubble { padding: 12px 16px; border-radius: 15px; font-size: 13.5px; line-height: 1.5; font-weight: 500; }
    
    .ai-message { align-self: flex-start; }
    .ai-message .msg-bubble { 
        background: #ffffff !important; color: #0A2239 !important; border: 1px solid #e2e8f0; 
        border-bottom-left-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .user-message { align-self: flex-end; }
    .user-message .msg-bubble { 
        background: #0A2239 !important; color: #ffffff !important; border-bottom-right-radius: 4px; 
        box-shadow: 0 2px 5px rgba(10,34,57,0.2);
    }

    .ai-chat-footer { padding: 15px; background: #ffffff; border-top: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: center; }
    .ai-chat-footer input {
        flex: 1; border: none; background: #f1f5f9; padding: 12px 15px; border-radius: 20px; 
        font-size: 14px; outline: none; transition: 0.2s; color: #0A2239 !important; font-weight: 500;
    }
    .ai-chat-footer input::placeholder { color: #64748b !important; font-weight: 400; }
    .ai-chat-footer input:focus { box-shadow: inset 0 0 0 1px #0A2239; }
    
    .btn-send {
        background: #FF4E00; color: white !important; border: none; width: 40px; height: 40px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 18px;
    }
    .btn-send:hover { transform: scale(1.1); }

    @media (max-width: 768px) { .ai-chat-window { width: 90vw; height: 75vh; right: -15px; bottom: 70px; } }

    /* =========================================
   🔥 TOUR PACKAGES SECTION STYLES 🔥
   ========================================= */
.tour-section-bg {
    background-color: #ffffff;
    padding: 80px 0;
    position: relative;
}

.tour-card {
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    background: #ffffff;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tour-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    border-color: #FF4E00;
}

.tour-img-wrapper {
    height: 220px;
    overflow: hidden;
    position: relative;
}

.tour-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.tour-card:hover .tour-img-wrapper img {
    transform: scale(1.08);
}

.tour-duration-badge {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
    backdrop-filter: blur(5px);
    z-index: 2;
}

.tour-discount-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #FF4E00;
    color: white;
    padding: 5px 10px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 800;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(255, 78, 0, 0.3);
}

.tour-body {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.tour-location {
    font-size: 12px;
    color: #0A2239;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.tour-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #000;
    margin-bottom: 12px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tour-price-section {
    margin-top: auto;
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.original-price {
    text-decoration: line-through;
    color: #888;
    font-size: 13px;
    display: block;
    line-height: 1;
}

.final-price {
    font-size: 22px;
    font-weight: 900;
    color: #0A2239;
    line-height: 1.2;
}

.per-person {
    font-size: 11px;
    color: #64748b;
    font-weight: 500;
}
</style>

<div class="modal fade" id="welcomeModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-block" style="background: url('https://loremflickr.com/500/800/vacation') center; background-size: cover;"></div>
                <div class="col-md-7 p-5 bg-white position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"></button>
                    <h6 class="text-uppercase fw-bold mb-2 text-primary">Sangam Tours</h6>
                    <h2 class="fw-bold mb-2" style="font-size: 2.2rem; color: #000;">Get <span class="text-primary">₹500 OFF</span></h2>
                    <p class="text-muted mb-4 small">Sign in to unlock exclusive app-only deals and manage your bookings.</p>
                    <form>
                        <input type="text" class="form-control py-3 mb-3 bg-light border-0" placeholder="Full Name" required style="border-radius: 8px; font-weight: 600;">
                        <input type="number" class="form-control py-3 mb-4 bg-light border-0" placeholder="Mobile Number" required style="border-radius: 8px; font-weight: 600;">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-white" style="border-radius: 8px; font-size: 1.1rem;">CLAIM REWARD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="hero-mmt">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="mmt-widget-container">
        <div class="mmt-tabs-wrapper" id="mmtTabs">
            <div class="mmt-tab active" data-type="flight"><i class='bx bxs-plane-alt'></i> <span>Flights</span></div>
            <div class="mmt-tab" data-type="hotel"><i class='bx bxs-building-house'></i> <span>Hotels</span></div>
            <div class="mmt-tab" data-type="homestay"><i class='bx bx-home-heart'></i> <span>Villas & Homestays</span></div>
            <div class="mmt-tab" data-type="tour"><i class='bx bxs-briefcase-alt-2'></i> <span>Holiday Packages</span></div>
            <div class="mmt-tab" data-type="train"><i class='bx bxs-train'></i> <span>Trains</span></div>
            <div class="mmt-tab" data-type="bus"><i class='bx bxs-bus'></i> <span>Buses</span></div>
            <div class="mmt-tab" data-type="car"><i class='bx bxs-car'></i> <span>Cabs</span></div>
            <div class="mmt-tab" data-type="attraction"><i class='bx bxs-map-alt'></i> <span>Tours & Attractions</span></div>
            <div class="mmt-tab" data-type="visa"><i class='bx bx-id-card'></i> <span>Visa</span></div>
            <div class="mmt-tab" data-type="cruise"><i class='bx bx-water'></i> <span>Cruise</span></div>
            <div class="mmt-tab" data-type="forex"><i class='bx bx-credit-card'></i> <span>Forex Card</span></div>
            <div class="mmt-tab" data-type="insurance"><i class='bx bx-shield-quarter'></i> <span>Travel Insurance</span></div>
        </div>

        <div class="mmt-widget-body">
            <form action="{{ route('bus.search') }}" method="GET">
                <div class="mmt-radios">
                    <label class="mmt-radio-item"><input type="radio" name="trip_type" checked> One Way</label>
                    <label class="mmt-radio-item"><input type="radio" name="trip_type"> Round Trip</label>
                    <label class="mmt-radio-item"><input type="radio" name="trip_type"> Multi City</label>
                </div>

                <div id="dynamicMmtInputs">
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">From</span>
                            <input type="text" class="mmt-value-input" value="Delhi" name="from">
                            <span class="mmt-sub-label">DEL, Delhi Airport India</span>
                        </div>
                        <div class="swap-icon"><i class='bx bx-transfer-alt'></i></div>
                        <div class="mmt-input-box box-large" style="padding-left: 40px;">
                            <span class="mmt-label">To</span>
                            <input type="text" class="mmt-value-input" value="Bengaluru" name="to">
                            <span class="mmt-sub-label">BLR, Bengaluru International Airport...</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Departure <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="25 Mar'26" readonly>
                            <span class="mmt-sub-label">Wednesday</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Return <i class='bx bx-chevron-down text-primary'></i></span>
                            <span class="mmt-sub-label" style="margin-top: 10px;">Tap to add a return date for bigger discounts</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Travellers & Class <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Traveller" readonly>
                            <span class="mmt-sub-label">Economy/Premium Economy</span>
                        </div>
                    </div>
                </div>

                <div class="special-fares d-none d-md-flex">
                    <span class="fare-title">SPECIAL FARES</span>
                    <span class="fare-badge active">Regular<br><small class="fw-normal">Regular fares</small></span>
                    <span class="fare-badge">Student<br><small class="fw-normal">Extra discounts</small></span>
                    <span class="fare-badge">Senior Citizen<br><small class="fw-normal">Up to ₹600 off</small></span>
                    <span class="fare-badge">Armed Forces<br><small class="fw-normal">Up to ₹600 off</small></span>
                </div>

                <div class="mmt-search-btn-wrapper">
                    <button type="button" class="mmt-search-btn">SEARCH</button> </div>
            </form>
        </div>

        <div class="explore-more">
            <i class='bx bx-chevron-down fs-4'></i> Explore More <i class='bx bx-chevron-down fs-4'></i>
        </div>
    </div>
</section>

{{-- HERO SECTION (Aapka Same MMT Header) --}}
<section class="offers-section-bg" style="padding: 100px 0 60px 0; background-color: #f8fafc;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <h2 class="section-heading mb-3" style="color: #0A2239;">EXCLUSIVE OFFERS</h2>
                
                {{-- Dynamic Offer Tabs --}}
                @php
                    // Offer table se unique 'apply_to' (Categories) nikalna
                    $offerCategories = $offers->pluck('apply_to')->unique()->filter();
                @endphp
                <ul class="nav nav-pills offer-tabs" id="offerPills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-offers-tab" data-bs-toggle="pill" data-bs-target="#all-offers" type="button" role="tab">All Offers</button>
                    </li>
                    @foreach($offerCategories as $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cat-{{ Str::slug($category) }}-tab" data-bs-toggle="pill" data-bs-target="#cat-{{ Str::slug($category) }}" type="button" role="tab">{{ $category }}</button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="tab-content" id="offerPillsContent">
            {{-- All Offers Tab --}}
            <div class="tab-pane fade show active" id="all-offers" role="tabpanel">
                <div class="row g-4">
                    @forelse($offers as $offer)
                        <div class="col-lg-6">
                            <div class="offer-card">
                                <div class="row g-0 h-100">
                                    <div class="col-4 col-md-4">
                                        <div class="offer-img-box">
                                            {{-- Default image kyunki Offer table mein image column nahi tha --}}
                                            <img src="https://loremflickr.com/400/400/discount,travel?random={{ $offer->id }}" alt="Offer Image">
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-8 p-4 d-flex flex-column justify-content-center">
                                        <p class="small text-muted mb-2 text-end fw-bold" style="font-size: 11px; letter-spacing: 1px;">
                                            VALID TILL {{ \Carbon\Carbon::parse($offer->valid_until)->format('d M') }}
                                        </p>
                                        <h4 class="offer-title">{{ $offer->offer_name }}</h4>
                                        <p class="small text-muted border-bottom pb-3 mb-3">
                                            @if($offer->discount_type == 'Percentage')
                                                Get {{ (int)$offer->discount_value }}% OFF. 
                                            @else
                                                Flat ₹{{ (int)$offer->discount_value }} OFF. 
                                            @endif
                                            Min booking: ₹{{ (int)$offer->min_booking_amount }}.
                                            <br><strong class="text-dark">Code: {{ $offer->offer_code }}</strong>
                                        </p>
                                        <div class="text-end mt-auto">
                                            <a href="#" class="offer-btn">CLAIM OFFER <i class='bx bx-right-arrow-circle'></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4"><i class='bx bx-info-circle'></i> No active offers at the moment.</p>
                    @endforelse
                </div>
            </div>

            {{-- Category Specific Tabs --}}
            @foreach($offerCategories as $category)
                <div class="tab-pane fade" id="cat-{{ Str::slug($category) }}" role="tabpanel">
                    <div class="row g-4">
                        @forelse($offers->where('apply_to', $category) as $offer)
                            <div class="col-lg-6">
                                <div class="offer-card">
                                    <div class="row g-0 h-100">
                                        <div class="col-4 col-md-4">
                                            <div class="offer-img-box">
                                                <img src="https://loremflickr.com/400/400/{{ Str::slug($category) }},travel?random={{ $offer->id }}" alt="Offer Image">
                                            </div>
                                        </div>
                                        <div class="col-8 col-md-8 p-4 d-flex flex-column justify-content-center">
                                            <p class="small text-muted mb-2 text-end fw-bold" style="font-size: 11px; letter-spacing: 1px;">CODE: {{ $offer->offer_code }}</p>
                                            <h4 class="offer-title">{{ $offer->offer_name }}</h4>
                                            <p class="small text-muted border-bottom pb-3 mb-3">
                                                @if($offer->discount_type == 'Percentage')
                                                    Enjoy {{ (int)$offer->discount_value }}% savings on your next {{ $category }} booking.
                                                @else
                                                    Save ₹{{ (int)$offer->discount_value }} instantly.
                                                @endif
                                            </p>
                                            <div class="text-end mt-auto">
                                                <a href="#" class="offer-btn">BOOK NOW <i class='bx bx-right-arrow-circle'></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">No offers available for {{ $category }}.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="tour-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="section-heading mb-1" style="color: #0A2239;">BEST SELLING HOLIDAYS</h2>
                <p class="text-muted fs-5 mb-0">Curated packages for your perfect getaway.</p>
            </div>
            <a href="{{ route('tours.index') }}" class="text-decoration-none fw-bold d-none d-md-flex align-items-center gap-1" style="color: #FF4E00;">
                View All Packages <i class='bx bx-right-arrow-alt fs-5'></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($tourPackages as $package)
                @php
                    // Main Image Setup
                    $mainImg = $package->images->where('image_type', 'main')->first();
                    $imagePath = ($mainImg && file_exists(public_path('uploads/packages/'.$mainImg->filename))) 
                                 ? asset('uploads/packages/'.$mainImg->filename) 
                                 : asset('no-image.png'); // Fallback image

                    // Duration Calculation from stays
                    $totalDays = $package->stays->sum('days');
                    $totalNights = $package->stays->sum('nights');
                    $durationText = ($totalDays > 0 || $totalNights > 0) 
                                    ? "{$totalDays}D / {$totalNights}N" 
                                    : "Custom Duration";

                    // Discount Calculation
                    $hasDiscount = !empty($package->discount_price) && $package->discount_price < $package->price;
                    if($hasDiscount) {
                        $discountPercentage = round((($package->price - $package->discount_price) / $package->price) * 100);
                    }
                @endphp

                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('tours.detail', $package->package_id) }}" class="text-decoration-none">
                        <div class="tour-card shadow-sm">
                            
                            {{-- Image & Badges --}}
                            <div class="tour-img-wrapper">
                                @if($hasDiscount)
                                    <span class="tour-discount-badge">{{ $discountPercentage }}% OFF</span>
                                @endif
                                
                                <img src="{{ $imagePath }}" alt="{{ $package->title }}">
                                
                                <span class="tour-duration-badge">
                                    <i class='bx bx-time-five me-1'></i>{{ $durationText }}
                                </span>
                            </div>

                            {{-- Card Body --}}
                            <div class="tour-body">
                                <div class="tour-location">
                                    <i class='bx bxs-map text-primary fs-6'></i> 
                                    {{ $package->location->state_name ?? 'India' }}, {{ $package->location->country_name ?? '' }}
                                </div>
                                
                                <h3 class="tour-title">{{ $package->title }}</h3>
                                
                                <p class="text-muted small mb-0 line-clamp-2">
                                    {{ Str::limit(strip_tags($package->details), 80, '...') ?: 'Experience the best of this destination with our exclusive package.' }}
                                </p>

                                {{-- Price Section --}}
                                <div class="tour-price-section mt-4">
                                    <div>
                                        <span class="per-person d-block mb-1">Starting price per adult</span>
                                        @if($hasDiscount)
                                            <span class="original-price">₹{{ number_format($package->price) }}</span>
                                            <span class="final-price">₹{{ number_format($package->discount_price) }}</span>
                                        @else
                                            <span class="final-price">₹{{ number_format($package->price) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3 py-1">View Details</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class='bx bx-briefcase-alt-2 fs-1 mb-3'></i>
                    <h4>No Packages Available</h4>
                    <p>We are currently updating our holiday packages. Check back soon!</p>
                </div>
            @endforelse
        </div>
        
        {{-- Mobile View All Button --}}
        <div class="text-center mt-4 d-block d-md-none">
            <a href="{{ route('tours.index') }}" class="btn btn-outline-primary rounded-pill fw-bold px-4 py-2">
                View All Packages
            </a>
        </div>

    </div>
</section>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container bg-white p-4 p-md-5 shadow-sm" style="border-radius: 20px;">
        <div class="text-center mb-5">
            <h2 class="section-heading mb-2" style="color: #d35400;">BOOK YOUR IDEAL STAY</h2>
            <p class="text-muted fs-5">Choose from a vast selection of accommodations across Bihar & Beyond.</p>
        </div>

        {{-- Dynamic Accommodation Type Tabs --}}
        <ul class="nav nav-tabs custom-stay-tabs justify-content-lg-center" id="stayTabs" role="tablist">
            @foreach($accommodationTypes as $index => $type)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="type-{{ $type->id }}-tab" data-bs-toggle="tab" data-bs-target="#type-{{ $type->id }}" type="button" role="tab">
                        <i class='bx {{ $type->icon ?? "bx-building-house" }}'></i> {{ $type->name }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Dynamic Tab Content for Accommodations --}}
        <div class="tab-content mt-4" id="stayTabsContent">
            @foreach($accommodationTypes as $index => $type)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="type-{{ $type->id }}" role="tabpanel">
                    
                    @php
                        // Is specific type ke hotels filter karna
                        $filteredAccs = $accommodations->where('accommodation_type_id', $type->id);
                    @endphp

                    @if($filteredAccs->count() > 0)
                        <div class="row g-4">
                            @foreach($filteredAccs as $acc)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card stay-card border-0 shadow-sm h-100 p-2">
                                        {{-- Image Logic --}}
                                        @php
                                            $mainImg = $acc->images->where('image_type', 'main')->first();
                                            $imagePath = ($mainImg && file_exists(public_path('uploads/accommodations/'.$mainImg->filename))) 
                                                         ? asset('uploads/accommodations/'.$mainImg->filename) 
                                                         : asset('no-image.png'); // Add a default no-image in public folder
                                        @endphp
                                        <img src="{{ $imagePath }}" class="card-img-top stay-card-img rounded-4" alt="{{ $acc->name }}">
                                        
                                        <div class="card-body px-2 pb-0">
                                            {{-- Star Rating Render Logic --}}
                                            @php
                                                $starCount = (int) filter_var($acc->star_rating, FILTER_SANITIZE_NUMBER_INT);
                                                $starCount = $starCount > 5 ? 5 : ($starCount < 0 ? 0 : $starCount);
                                            @endphp
                                            <div class="star-rating mb-1">
                                                @if($starCount > 0)
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $starCount)
                                                            <i class='bx bxs-star text-warning'></i>
                                                        @else
                                                            <i class='bx bx-star text-muted'></i>
                                                        @endif
                                                    @endfor
                                                @else
                                                    <span class="badge bg-secondary" style="font-size:10px;">Budget Stay</span>
                                                @endif
                                            </div>

                                            <h5 class="card-title fw-bold mb-1 text-truncate" title="{{ $acc->name }}">{{ $acc->name }}</h5>
                                            <p class="text-muted small mb-2"><i class='bx bx-map'></i> {{ $acc->location->city_location ?? 'N/A' }}, {{ $acc->location->state_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-end px-2 pb-3 mt-auto">
                                            <div>
                                                <span class="d-block small fw-bold text-muted" style="line-height: 1;">FROM</span>
                                                <span class="fw-bold fs-5">₹{{ number_format($acc->price_per_night) }}<span class="small text-muted fs-6 fw-normal">/NIGHT</span></span>
                                            </div>
                                            <a href="#" class="btn btn-view-details text-decoration-none">VIEW DETAILS</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center mt-5 text-muted">
                            <i class='bx bx-ghost fs-1 mb-2'></i>
                            <p>No {{ $type->name }} available at the moment. Coming soon!</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="food-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-heading mb-1" style="color: #0A2239;">FAMOUS EATERIES NEAR YOU</h2>
                <p class="text-muted fs-5 mb-0">Discover top-rated restaurants, cafes, and local flavors.</p>
            </div>
            <a href="#" class="text-decoration-none fw-bold d-none d-md-block" style="color: #FF4E00;">View All <i class='bx bx-right-arrow-alt'></i></a>
        </div>

        <ul class="nav nav-tabs custom-stay-tabs justify-content-lg-start mb-5" id="foodTabs" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="all-food-tab" data-bs-toggle="tab" data-bs-target="#all-food" type="button" role="tab"><i class='bx bx-restaurant'></i> Top Rated</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="cafes-tab" data-bs-toggle="tab" data-bs-target="#cafes" type="button" role="tab"><i class='bx bx-coffee-togo'></i> Cafes & Bakery</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="fine-dining-tab" data-bs-toggle="tab" data-bs-target="#fine-dining" type="button" role="tab"><i class='bx bx-dish'></i> Fine Dining</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="street-food-tab" data-bs-toggle="tab" data-bs-target="#street-food" type="button" role="tab"><i class='bx bx-bowl-hot'></i> Local / Street</button></li>
        </ul>

        <div class="tab-content" id="foodTabsContent">
            <div class="tab-pane fade show active" id="all-food" role="tabpanel" aria-labelledby="all-food-tab">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="food-card h-100 d-flex flex-column">
                            <div class="food-img-wrapper">
                                <span class="food-badge badge-top">Top Rated</span>
                                <img src="https://loremflickr.com/500/300/restaurant,biryani?random=1" alt="Barkaas Patna">
                                <div class="food-distance"><i class='bx bx-map'></i> 850m</div>
                            </div>
                            <div class="card-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">ARABIAN / MUGHLAI</span>
                                    <div class="food-rating"><i class='bx bxs-star'></i><span>4.7</span></div>
                                </div>
                                <h5 class="fw-bold mb-2">Barkaas Patna</h5>
                                <p class="text-muted small mb-0">Experience authentic Arabian flavors and the famous Mandi biryani.</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="#" class="btn btn-reserve text-decoration-none">View Menu</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="food-card h-100 d-flex flex-column">
                            <div class="food-img-wrapper">
                                <span class="food-badge badge-famous">Famous Rooftop</span>
                                <img src="https://loremflickr.com/500/300/restaurant,dinner?random=2" alt="Foresto Paradise">
                                <div class="food-distance"><i class='bx bx-map'></i> 950m</div>
                            </div>
                            <div class="card-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">NORTH INDIAN / CHINESE</span>
                                    <div class="food-rating"><i class='bx bxs-star'></i><span>4.2</span></div>
                                </div>
                                <h5 class="fw-bold mb-2">Foresto Paradise</h5>
                                <p class="text-muted small mb-0">Beautiful rooftop restaurant offering a great city view with delicious food.</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="#" class="btn btn-reserve text-decoration-none">Book Table</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="food-card h-100 d-flex flex-column">
                            <div class="food-img-wrapper">
                                <span class="food-badge badge-top">Highest Rated</span>
                                <img src="https://loremflickr.com/500/300/cafe,coffee?random=3" alt="Hello Cafe">
                                <div class="food-distance"><i class='bx bx-map'></i> 1.2 km</div>
                            </div>
                            <div class="card-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">CAFE / FAST FOOD</span>
                                    <div class="food-rating"><i class='bx bxs-star'></i><span>4.8</span></div>
                                </div>
                                <h5 class="fw-bold mb-2">Hello Restaurant & Cafe</h5>
                                <p class="text-muted small mb-0">A highly-rated pocket-friendly cafe. Perfect for evening snacks.</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="#" class="btn btn-reserve text-decoration-none">View Menu</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="food-card h-100 d-flex flex-column">
                            <div class="food-img-wrapper">
                                <span class="food-badge badge-nearby">Village Theme</span>
                                <img src="https://loremflickr.com/500/300/dining,restaurant?random=4" alt="Swaddesh">
                                <div class="food-distance"><i class='bx bx-map'></i> 1.9 km</div>
                            </div>
                            <div class="card-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">DESI / TRADITIONAL</span>
                                    <div class="food-rating"><i class='bx bxs-star'></i><span>4.1</span></div>
                                </div>
                                <h5 class="fw-bold mb-2">Swaddesh Restaurant</h5>
                                <p class="text-muted small mb-0">Experience the charm of a village theme setup with authentic desi food.</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="#" class="btn btn-reserve text-decoration-none">Reserve Table</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="cafes" role="tabpanel" aria-labelledby="cafes-tab">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="food-card h-100 d-flex flex-column">
                            <div class="food-img-wrapper">
                                <span class="food-badge badge-top">Must Try</span>
                                <img src="https://loremflickr.com/500/300/bakery,cake?random=5" alt="Barista">
                                <div class="food-distance"><i class='bx bx-map'></i> 500m</div>
                            </div>
                            <div class="card-body p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">COFFEE / DESSERTS</span>
                                    <div class="food-rating"><i class='bx bxs-star'></i><span>4.5</span></div>
                                </div>
                                <h5 class="fw-bold mb-2">The Chocolate Room</h5>
                                <p class="text-muted small mb-0">Best place for chocolate lovers, premium shakes, and fresh bakes.</p>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                <a href="#" class="btn btn-reserve text-decoration-none">View Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="fine-dining" role="tabpanel" aria-labelledby="fine-dining-tab"><p class="text-muted mt-4"><i class='bx bx-info-circle'></i> Premium Fine Dining options loading soon...</p></div>
            <div class="tab-pane fade" id="street-food" role="tabpanel" aria-labelledby="street-food-tab"><p class="text-muted mt-4"><i class='bx bx-info-circle'></i> Famous street food stalls and local joints loading soon...</p></div>
        </div>
    </div>
</section>


<section class="py-5" style="background-color: #ffffff;">
    <div class="container">
        
        {{-- Section Header & Tabs --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <h2 class="section-heading mb-2" style="color: #0A2239;">BOOK A RIDE</h2>
                <p class="text-muted fs-5 mb-0">From premium cabs to budget-friendly bikes.</p>
            </div>
            
            {{-- 🔥 The Navigation Tabs --}}
            <ul class="nav nav-pills offer-tabs mt-3 mt-md-0" id="rideTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="cabs-tab" data-bs-toggle="pill" data-bs-target="#cabs-content" type="button" role="tab">
                        <i class='bx bxs-car me-1'></i> Cabs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="auto-tab" data-bs-toggle="pill" data-bs-target="#auto-content" type="button" role="tab">
                        <i class='bx bxs-taxi me-1'></i> Auto
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="erickshaw-tab" data-bs-toggle="pill" data-bs-target="#erickshaw-content" type="button" role="tab">
                        <i class='bx bx-bus me-1'></i> E-Rickshaw
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bike-tab" data-bs-toggle="pill" data-bs-target="#bike-content" type="button" role="tab">
                        <i class='bx bx-cycling me-1'></i> Bikes
                    </button>
                </li>
            </ul>
        </div>

        {{-- Tab Contents --}}
        <div class="tab-content" id="rideTabsContent">
            
            {{-- 🔥 1. CABS TAB --}}
            <div class="tab-pane fade show active" id="cabs-content" role="tabpanel">
                <div class="row g-4">
                    @include('partials.ride_cards', ['rides' => $cabs, 'emptyMsg' => 'No cabs available right now.'])
                </div>
            </div>

            {{-- 🔥 2. AUTO TAB --}}
            <div class="tab-pane fade" id="auto-content" role="tabpanel">
                <div class="row g-4">
                    @include('partials.ride_cards', ['rides' => $autos, 'emptyMsg' => 'No auto rickshaws available right now.'])
                </div>
            </div>

            {{-- 🔥 3. E-RICKSHAW TAB --}}
            <div class="tab-pane fade" id="erickshaw-content" role="tabpanel">
                <div class="row g-4">
                    @include('partials.ride_cards', ['rides' => $eRickshaws, 'emptyMsg' => 'No E-Rickshaws available right now.'])
                </div>
            </div>

            {{-- 🔥 4. BIKES TAB --}}
            <div class="tab-pane fade" id="bike-content" role="tabpanel">
                <div class="row g-4">
                    @include('partials.ride_cards', ['rides' => $bikes, 'emptyMsg' => 'No bikes available right now.'])
                </div>
            </div>

        </div>

    </div>
</section>


    <section class="stats-section" id="statsSection">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-lg-3 col-6 stat-item">
                    <i class='bx bx-happy-beaming stat-icon'></i>
                    <div class="stat-number"><span class="counter" data-target="50">0</span>K+</div>
                    <div class="stat-text">Happy Travelers</div>
                </div>
                <div class="col-lg-3 col-6 stat-item">
                    <i class='bx bx-map-pin stat-icon'></i>
                    <div class="stat-number"><span class="counter" data-target="120">0</span>+</div>
                    <div class="stat-text">Cities Covered</div>
                </div>
                <div class="col-lg-3 col-6 stat-item">
                    <i class='bx bxs-bus stat-icon'></i>
                    <div class="stat-number"><span class="counter" data-target="850">0</span>+</div>
                    <div class="stat-text">Premium Fleet</div>
                </div>
                <div class="col-lg-3 col-6 stat-item">
                    <i class='bx bxs-star stat-icon'></i>
                    <div class="stat-number"><span class="counter" data-target="4.8">0</span>/5</div>
                    <div class="stat-text">Average User Rating</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #f8fafc;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-heading mb-1" style="color: #0A2239;">WHAT OUR TRAVELERS SAY</h2>
                <p class="text-muted fs-5 mb-0">Real experiences from our lovely customers across the country.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="review-card">
                        <i class='bx bxs-quote-right review-quote'></i>
                        <div class="star-rating mb-3">
                            <i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                        </div>
                        <p class="review-text">"Sangam Tours made our Bihar Darshan absolutely seamless. The buses were on time, extremely clean, and the staff was very polite. Will definitely book my next trip with them!"</p>
                        <div class="reviewer-info border-top pt-3 mt-auto">
                            <img src="https://loremflickr.com/100/100/face,man?random=21" alt="User" class="reviewer-img">
                            <div>
                                <h6 class="reviewer-name">Rahul Sharma</h6>
                                <span class="reviewer-loc">Delhi, India</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="review-card">
                        <i class='bx bxs-quote-right review-quote'></i>
                        <div class="star-rating mb-3">
                            <i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star-half'></i>
                        </div>
                        <p class="review-text">"I booked a luxury SUV for my family trip from Patna to Bodhgaya. The driver was highly professional and acted like a guide too. Amazing service and very transparent pricing."</p>
                        <div class="reviewer-info border-top pt-3 mt-auto">
                            <img src="https://loremflickr.com/100/100/face,woman?random=22" alt="User" class="reviewer-img">
                            <div>
                                <h6 class="reviewer-name">Priya Singh</h6>
                                <span class="reviewer-loc">Bengaluru, India</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="review-card">
                        <i class='bx bxs-quote-right review-quote'></i>
                        <div class="star-rating mb-3">
                            <i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i>
                        </div>
                        <p class="review-text">"Using Sangam Tours for our hotel bookings saved us a lot of money. Their 'App Exclusive' offers are genuine. Customer support is also very quick via their AI Chatbot!"</p>
                        <div class="reviewer-info border-top pt-3 mt-auto">
                            <img src="https://loremflickr.com/100/100/face,man?random=23" alt="User" class="reviewer-img">
                            <div>
                                <h6 class="reviewer-name">Amit Verma</h6>
                                <span class="reviewer-loc">Mumbai, India</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div class="ai-chat-container">
    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-2">
                <div class="ai-avatar"><i class='bx bx-bot'></i></div>
                <div>
                    <h6 class="chat-title-text">Sangam AI</h6>
                    <span class="chat-status-text"><i class='bx bxs-circle text-success' style="font-size: 8px; margin-right: 3px;"></i> Online & Ready</span>
                </div>
            </div>
            <button class="btn-close-chat" onclick="toggleChat()"><i class='bx bx-x'></i></button>
        </div>

        <div class="ai-chat-body" id="chatBody">
            <div class="chat-message ai-message">
                <div class="msg-bubble">Namaste! 🙏 Main Sangam AI hoon. Kya main aapki Bus, Hotel ya Tour book karne mein madad karun?</div>
            </div>
        </div>

        <div class="ai-chat-footer">
            <input type="text" id="chatInput" placeholder="Ask me anything..." autocomplete="off" onkeypress="handleEnter(event)">
            <button class="btn-send" onclick="sendDummyMessage()"><i class='bx bxs-send'></i></button>
        </div>
    </div>

    <button class="ai-chat-toggle" id="aiChatToggleBtn" onclick="toggleChat()">
        <i class='bx bx-message-rounded-dots'></i>
    </button>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#welcomeModal').modal('show');
        }, 600);

        // MMT Tab Switching
        $('.mmt-tab').on('click', function() {
            $('.mmt-tab').removeClass('active');
            $(this).addClass('active');
            let type = $(this).data('type');

            let html = '';

            if (type === 'flight' || type === 'bus' || type === 'train' || type === 'car') {
                html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">From</span>
                            <input type="text" class="mmt-value-input" value="Patna">
                            <span class="mmt-sub-label">Bihar, India</span>
                        </div>
                        <div class="swap-icon"><i class='bx bx-transfer-alt'></i></div>
                        <div class="mmt-input-box box-large" style="padding-left: 40px;">
                            <span class="mmt-label">To</span>
                            <input type="text" class="mmt-value-input" placeholder="Destination">
                            <span class="mmt-sub-label">Enter arrival city</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Departure <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y') }}" readonly>
                            <span class="mmt-sub-label">{{ date('l') }}</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Travellers <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Adult" readonly>
                        </div>
                    </div>`;
            } else if (type === 'hotel' || type === 'homestay') {
                html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">City, Property Name Or Location</span>
                            <input type="text" class="mmt-value-input" placeholder="Where to?">
                            <span class="mmt-sub-label">India or International</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Check-In <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y') }}" readonly>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Check-Out <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y', strtotime('+1 day')) }}" readonly>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Rooms & Guests <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Room, 2 Adults" readonly>
                        </div>
                    </div>`;
            } else {
                html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">Search</span>
                            <input type="text" class="mmt-value-input" placeholder="Search Services...">
                        </div>
                    </div>`;
            }

            $('#dynamicMmtInputs').fadeOut(100, function() {
                $(this).html(html).fadeIn(200);
            });
        });




        // ==========================================
        // 🔥 COUNTER ANIMATION LOGIC 🔥
        // ==========================================
        const counters = document.querySelectorAll('.counter');
        const speed = 50; // Speed control: Number jitna bada, ginti utni slow hogi

        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;

                    // Increment step
                    const inc = target / speed;

                    if (count < target) {
                        // Agar number mein decimal (.) hai (jaise 4.8)
                        if (target % 1 !== 0) {
                            counter.innerText = (count + inc).toFixed(1);
                        } else {
                            counter.innerText = Math.ceil(count + inc);
                        }
                        // 20ms delay ke baad wapas run karega
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        }

        // Ye check karega ki user scroll karke wahan pahuncha ya nahi
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target); // Ek baar chalne ke baad ruk jayega
                }
            });
        }, { threshold: 0.5 }); // Jab 50% section screen par dikhega tab trigger hoga

        const statsSection = document.getElementById('statsSection');
        if (statsSection) {
            observer.observe(statsSection);
        }
    });

    // --- AI CHATBOT LOGIC (UI LEVEL) ---
    function toggleChat() {
        const chatWindow = document.getElementById('aiChatWindow');
        const toggleBtnIcon = document.querySelector('#aiChatToggleBtn i');
        
        if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
            chatWindow.style.display = 'flex';
            toggleBtnIcon.classList.remove('bx-message-rounded-dots');
            toggleBtnIcon.classList.add('bx-x');
        } else {
            chatWindow.style.display = 'none';
            toggleBtnIcon.classList.remove('bx-x');
            toggleBtnIcon.classList.add('bx-message-rounded-dots');
        }
    }

    function handleEnter(e) {
        if(e.key === 'Enter') { sendDummyMessage(); }
    }

    function sendDummyMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        const chatBody = document.getElementById('chatBody');

        if(message !== "") {
            chatBody.innerHTML += `
                <div class="chat-message user-message">
                    <div class="msg-bubble">${message}</div>
                </div>
            `;
            input.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                chatBody.innerHTML += `
                    <div class="chat-message ai-message">
                        <div class="msg-bubble">Main abhi backend se connect nahi hoon, par jaldi hi aapke saare travel sawalon ke jawab dunga! 🚀</div>
                    </div>
                `;
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 1000);
        }
    }
</script>
@endpush