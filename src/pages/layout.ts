export function layout(title: string, body: string, extraHead: string = ''): string {
  return `<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title} - FJU Smart Hub</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'angel-white': '#FFFFFF',
            'mary-blue': '#003153',
            'vatican-gold': '#DAA520',
            'fju-gold': '#FDB913',
            'harvest-green': '#008000',
            'warning-red': '#FF0000',
          },
          fontFamily: {
            sans: ['"Noto Sans TC"', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    * { font-family: 'Noto Sans TC', system-ui, sans-serif; }
    .glassmorphism {
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255,255,255,0.3);
    }
    .glassmorphism-dark {
      background: rgba(0,49,83,0.85);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255,255,255,0.15);
    }
    .text-gradient {
      background: linear-gradient(135deg, #003153, #DAA520);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .gold-gradient {
      background: linear-gradient(135deg, #DAA520, #FDB913);
    }
    .blue-gradient {
      background: linear-gradient(135deg, #003153, #004a7c);
    }
    .btn-gold {
      background: linear-gradient(135deg, #DAA520, #FDB913);
      color: #003153;
      font-weight: 700;
      transition: all 0.3s;
    }
    .btn-gold:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(218,165,32,0.4);
    }
    .btn-blue {
      background: linear-gradient(135deg, #003153, #004a7c);
      color: #FFFFFF;
      font-weight: 700;
      transition: all 0.3s;
    }
    .btn-blue:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,49,83,0.4);
    }
    .card-hover {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 60px rgba(0,49,83,0.15);
    }
    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .animate-pulse-gold {
      animation: pulseGold 2s ease-in-out infinite;
    }
    @keyframes pulseGold {
      0%, 100% { box-shadow: 0 0 0 0 rgba(218,165,32,0.4); }
      50% { box-shadow: 0 0 0 15px rgba(218,165,32,0); }
    }
    .credit-gauge { transition: width 1s ease-in-out; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    ::-webkit-scrollbar-thumb { background: #003153; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #004a7c; }
  </style>
  ${extraHead}
</head>
<body class="bg-white text-gray-800 font-sans">
${body}
</body>
</html>`
}
