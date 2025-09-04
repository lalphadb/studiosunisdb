<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Info - StudiosDB v6 Pro</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #581c87 100%);
            min-height: 100vh;
            color: white;
            padding: 20px;
        }
        .header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .title { 
            font-size: 36px; 
            margin-bottom: 10px;
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .content {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        table { 
            background: rgba(255,255,255,0.9); 
            color: #333; 
            width: 100%; 
            border-collapse: collapse; 
            border-radius: 10px; 
            overflow: hidden;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid rgba(0,0,0,0.1); 
        }
        th { 
            background: rgba(59, 130, 246, 0.8); 
            color: white; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">🔧 PHP Information</div>
    <div>StudiosDB v6 Pro - Configuration PHP</div>
        <a href="/dashboard" class="btn">🏠 Retour Dashboard</a>
    </div>

    <div class="content">
        <?php phpinfo(); ?>
    </div>
</body>
</html>
