<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background:#f8f9fa;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background:#243e5d;color:#fff;padding:16px;border-radius:8px 8px 0 0;">
            <div style="font-size:18px;font-weight:700;">ivoireindustrie<span style="color:#ff7800;">mag</span></div>
        </div>
        <div style="background:#fff;padding:20px;border-radius:0 0 8px 8px;">
            <h2 style="margin:0 0 14px;font-size:20px;">{{ $subjectLine }}</h2>
            <div style="white-space:pre-wrap;color:#333;line-height:1.5;font-size:14px;">
                {{ $bodyLine }}
            </div>
            <div style="margin-top:18px;color:#777;font-size:12px;">
                Vous recevez ce message car vous êtes inscrit à notre newsletter.
            </div>
        </div>
    </div>
</body>
</html>

