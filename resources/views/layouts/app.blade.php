<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'FJU Smart Hub') - FJU Smart Hub</title>
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'fju-blue': '#003153', 'fju-blue-light': '#004070', 'fju-dark': '#333333',
            'fju-bg': '#F4F6F8', 'fju-yellow': '#DAA520', 'fju-yellow-light': '#FDB913',
            'fju-green': '#008000', 'fju-red': '#FF0000', 'fju-gold': '#DAA520',
            'fju-sidebar': '#333333', 'fju-card': '#FFFFFF',
          },
          fontFamily: { sans: ['"Microsoft JhengHei"', '"微軟正黑體"', 'system-ui', 'sans-serif'] },
          borderRadius: { 'fju': '12px', 'fju-lg': '15px' }
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/css/evo-calendar.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/js/evo-calendar.min.js"></script>
  <style>
    * { font-family: 'Microsoft JhengHei', '微軟正黑體', system-ui, sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
    .glassmorphism { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.3); }
    .glassmorphism-dark { background: rgba(0,43,91,0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.15); }
    .btn-yellow { background: #DAA520; color: #003153; font-weight: 700; border-radius: 12px; transition: all 0.3s; border: none; cursor: pointer; }
    .btn-yellow:hover { background: #FDB913; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(218,165,32,0.4); }
    .btn-blue { background: #003153; color: #FFFFFF; font-weight: 700; border-radius: 12px; transition: all 0.3s; border: none; cursor: pointer; }
    .btn-blue:hover { background: #004070; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,49,83,0.4); }
    .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,49,83,0.12); }
    ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; } ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    .animate-float { animation: float 3s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    .animate-pulse-yellow { animation: pulseYellow 2s ease-in-out infinite; }
    @keyframes pulseYellow { 0%, 100% { box-shadow: 0 0 0 0 rgba(218,165,32,0.4); } 50% { box-shadow: 0 0 0 15px rgba(218,165,32,0); } }
    .fju-popup .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
    .fju-popup .leaflet-popup-content { margin: 0; min-width: 220px; }
    .fju-popup .leaflet-popup-tip { background: #003153; }
    .popup-header { background: #003153; color: white; padding: 10px 14px; font-weight: 700; font-size: 14px; }
    .popup-body { padding: 12px 14px; font-size: 13px; color: #333; }
    .popup-body .popup-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .popup-body .popup-row i { color: #DAA520; width: 16px; text-align: center; }
    .popup-btn { display: inline-block; margin-top: 8px; background: #DAA520; color: #003153; padding: 6px 16px; border-radius: 12px; font-weight: 700; font-size: 12px; cursor: pointer; border: none; text-decoration: none; }
    .popup-btn:hover { background: #FDB913; }
    .evo-calendar { border-radius: 12px !important; overflow: hidden; box-shadow: none !important; border: 1px solid #e5e7eb !important; }
    .calendar-sidebar { background: #003153 !important; }
    .calendar-sidebar > .month-list > li.active-month { background: #DAA520 !important; color: #003153 !important; }
    .calendar-sidebar > span#sidebarToggler { background: #003153 !important; }
    .calendar-day > .day.calendar-today { background: #DAA520 !important; color: #003153 !important; }
    .calendar-day > .day.calendar-active { border-color: #003153 !important; }
    .event-indicator > .type-bullet > div { background: #DAA520 !important; }
    th[colspan] { background: #003153 !important; color: white !important; }
    .chatbot-fab { position: fixed; bottom: 24px; right: 24px; width: 60px; height: 60px; border-radius: 50%; background: #DAA520; display: flex; align-items: center; justify-content: center; font-size: 28px; cursor: pointer; box-shadow: 0 4px 20px rgba(218,165,32,0.4); z-index: 9999; transition: transform 0.3s; border: 3px solid white; }
    .chatbot-fab:hover { transform: scale(1.1); }
    .chatbot-panel { position: fixed; bottom: 96px; right: 24px; width: 360px; max-height: 480px; background: white; border-radius: 15px; box-shadow: 0 8px 40px rgba(0,0,0,0.2); z-index: 9998; overflow: hidden; display: none; flex-direction: column; }
    .chatbot-panel.active { display: flex; }
    .chatbot-header { background: #003153; color: white; padding: 14px 16px; display: flex; align-items: center; gap: 10px; }
    .chatbot-messages { flex: 1; overflow-y: auto; padding: 16px; background: #F4F6F8; max-height: 300px; }
    .chatbot-input-area { padding: 12px; border-top: 1px solid #e5e7eb; display: flex; gap: 8px; }
  </style>
  @yield('head')
</head>
<body>
@yield('body')
</body>
</html>
