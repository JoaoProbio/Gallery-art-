# 🎵 Integração SoundCloud - Guia Completo

## 📌 O Que É?

**SoundCloud** é uma plataforma de streaming de áudio que permite:
- ✅ Hospedar arquivos de áudio gratuitamente
- ✅ Incorporar players em sites
- ✅ Obter URLs diretas para áudio
- ✅ Sem limite de upload (plano gratuito)
- ✅ Suporte a múltiplos formatos (MP3, WAV, AIFF, etc)

---

## 🚀 Passo 1: Criar Conta SoundCloud

1. Acesse https://soundcloud.com
2. Clique em **Sign Up** (Inscrever-se)
3. Registre-se com email ou Google/Apple
4. Confirme seu email
5. Complete seu perfil

**URL do seu perfil:** `https://soundcloud.com/seu-usuario`

---

## 📤 Passo 2: Upload de Áudio

### Via Website:

1. Clique em **Upload** (canto superior direito)
2. Selecione seu arquivo MP3 (Outra Vida.mp3)
3. Preencha:
   - **Title:** Outra Vida
   - **Description:** Descrição da música
   - **Tags:** música, spotify
4. Escolha **Public** (Público)
5. Clique em **Save and continue**
6. Aguarde o processamento (2-5 minutos)

### Via Mobile:

1. Abra o app SoundCloud
2. Toque no **+ (Mais)**
3. Selecione **Upload Track**
4. Escolha o arquivo
5. Configure as opções
6. Publique

---

## 🔗 Passo 3: Obter o Link da Música

Depois que o upload terminar:

1. Acesse sua track (música)
2. URL: `https://soundcloud.com/seu-usuario/outra-vida`
3. Copie este link

---

## 🎵 Passo 4: Métodos de Integração

### Método 1: Embed Player (Mais Fácil) ⭐

```html
<!-- Adicione ao seu HTML -->
<iframe 
    width="100%" 
    height="166" 
    scrolling="no" 
    frameborder="no" 
    allow="autoplay" 
    src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/TRACK_ID&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true">
</iframe>
```

**Como obter o TRACK_ID:**

1. Acesse: https://soundcloud.com/oembed?format=json&url=https://soundcloud.com/seu-usuario/outra-vida
2. Copie o valor de `"id"` da resposta
3. Substitua `TRACK_ID` no código acima

### Método 2: Widget HTML5 Customizado

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SoundCloud Player</title>
    <style>
        .soundcloud-player {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: linear-gradient(180deg, rgba(255, 141, 161, 0.6) 30%, transparent 100%);
            border-radius: 10px;
        }
        
        .player-container {
            background: #121212;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="soundcloud-player">
        <div class="player-container">
            <h2>🎵 Outra Vida</h2>
            
            <!-- Player Embed -->
            <iframe 
                width="100%" 
                height="166" 
                scrolling="no" 
                frameborder="no" 
                allow="autoplay" 
                src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/SEU_TRACK_ID&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true">
            </iframe>
        </div>
    </div>
</body>
</html>
```

### Método 3: Usar SoundCloud API

```html
<script src="https://w.soundcloud.com/player/api.js"></script>

<div id="sc-player"></div>

<script>
    // Widget API do SoundCloud
    SC.Widget.onReady(function() {
        const widget = SC.Widget(document.getElementById('sc-player'));
        
        // Carregar a música
        widget.load('https://soundcloud.com/seu-usuario/outra-vida', {
            color: 'ff5500',
            autoPlay: false
        });
    });
</script>
```

---

## 🎯 Integração no Seu Spotify Player

### No index.html:

```html
<!-- Adicione ao seu player -->
<div class="music-section">
    <h2>🎵 Streaming SoundCloud</h2>
    
    <!-- Player do SoundCloud -->
    <div class="soundcloud-container">
        <iframe 
            width="100%" 
            height="166" 
            scrolling="no" 
            frameborder="no" 
            allow="autoplay" 
            src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/SEU_TRACK_ID&color=%231db954&auto_play=false&hide_related=false&show_comments=false&show_user=true&show_reposts=false&show_teaser=true">
        </iframe>
    </div>
</div>
```

### No estilo.css:

```css
/* SoundCloud Player Container */
.soundcloud-container {
    background: rgba(255, 255, 255, 0.05);
    padding: 15px;
    border-radius: 10px;
    margin: 20px 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.soundcloud-container iframe {
    border-radius: 8px;
}
```

---

## 🔍 Obter TRACK_ID (Métodos)

### Método A: Via API JSON

```javascript
// Substitua o URL pelo seu
fetch('https://soundcloud.com/oembed?format=json&url=https://soundcloud.com/seu-usuario/outra-vida')
    .then(response => response.json())
    .then(data => {
        console.log('TRACK_ID:', data.id);
        console.log('Title:', data.title);
        console.log('Image:', data.thumbnail_url);
    });
```

### Método B: Via Web

1. Acesse: https://soundcloud.com/seu-usuario/outra-vida
2. Abra DevTools (F12)
3. Console (Aba Console)
4. Cole:
```javascript
console.log(window.SC.Widget(document.querySelector('iframe')).id);
```

### Método C: Via URL Direta

```
https://soundcloud.com/oembed?format=json&url=https://soundcloud.com/seu-usuario/sua-musica
```

Procure por `"id"` na resposta JSON.

---

## 🎨 Personalizar o Player

### Cores Disponíveis:

```html
<!-- Verde Spotify -->
<iframe src="https://w.soundcloud.com/player/?url=...&color=%231db954...">

<!-- Rosa -->
<iframe src="https://w.soundcloud.com/player/?url=...&color=%23ff8da1...">

<!-- Roxo -->
<iframe src="https://w.soundcloud.com/player/?url=...&color=%23ba55d3...">

<!-- Azul -->
<iframe src="https://w.soundcloud.com/player/?url=...&color=%236495ed...">

<!-- Branco -->
<iframe src="https://w.soundcloud.com/player/?url=...&color=%23ffffff...">
```

### Parâmetros do URL:

```
url              = URL da track (obrigatório)
color            = Cor em HEX (sem #)
auto_play        = true/false
hide_related     = true/false
show_comments    = true/false
show_user        = true/false
show_reposts     = true/false
show_teaser      = true/false
visual           = true/false (visual ou compacto)
```

---

## 📋 Exemplo Completo

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Player - SoundCloud Integration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0a0a0a;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(
                180deg,
                rgba(29, 185, 84, 0.3) 0%,
                transparent 100%
            );
            border-radius: 10px;
        }

        .header h1 {
            font-size: 2em;
            color: #1db954;
            margin-bottom: 10px;
        }

        .player-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .player-section h2 {
            margin-bottom: 15px;
            color: #1db954;
        }

        .player-info {
            background: rgba(255, 255, 255, 0.08);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
        }

        .track-title {
            font-size: 1.3em;
            font-weight: bold;
            color: white;
            margin-bottom: 5px;
        }

        .track-artist {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95em;
        }

        iframe {
            border-radius: 8px !important;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            background: #1db954;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #1ed760;
            transform: scale(1.05);
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn.secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎵 Spotify Player</h1>
            <p>Integrado com SoundCloud</p>
        </div>

        <!-- Player Principal -->
        <div class="player-section">
            <h2>🎧 Agora Tocando</h2>
            
            <div class="player-info">
                <div class="track-title">Outra Vida</div>
                <div class="track-artist">Seu Artista</div>
            </div>

            <!-- SoundCloud Player - SUBSTITUA SEU_TRACK_ID -->
            <iframe 
                width="100%" 
                height="166" 
                scrolling="no" 
                frameborder="no" 
                allow="autoplay" 
                src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/SEU_TRACK_ID&color=%231db954&auto_play=false&hide_related=false&show_comments=false&show_user=true&show_reposts=false&show_teaser=true">
            </iframe>

            <div class="button-group">
                <button class="btn" onclick="abrirSoundCloud()">
                    🔗 Abrir no SoundCloud
                </button>
                <button class="btn secondary" onclick="compartilhar()">
                    📤 Compartilhar
                </button>
            </div>
        </div>

        <!-- Playlist Exemplo -->
        <div class="player-section">
            <h2>📊 Mais Músicas</h2>
            
            <div class="player-info">
                <div class="track-title">Música 1</div>
                <div class="track-artist">Artista 1</div>
            </div>

            <!-- Adicione mais players aqui -->
            <iframe 
                width="100%" 
                height="120" 
                scrolling="no" 
                frameborder="no" 
                allow="autoplay" 
                src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/OUTRO_TRACK_ID&color=%231db954&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false">
            </iframe>
        </div>
    </div>

    <script>
        // Abrir no SoundCloud
        function abrirSoundCloud() {
            window.open('https://soundcloud.com/seu-usuario/outra-vida', '_blank');
        }

        // Compartilhar
        function compartilhar() {
            const url = 'https://soundcloud.com/seu-usuario/outra-vida';
            if (navigator.share) {
                navigator.share({
                    title: 'Outra Vida',
                    text: 'Veja esta música no SoundCloud',
                    url: url
                });
            } else {
                // Fallback: copiar para clipboard
                navigator.clipboard.writeText(url);
                alert('Link copiado!');
            }
        }
    </script>
</body>
</html>
```

---

## 🔐 Checklist de Integração

```
☑ Criar conta SoundCloud
☑ Upload do arquivo MP3
☑ Música publicada e processada
☑ Obter TRACK_ID
☑ Substituir SEU_TRACK_ID no HTML
☑ Testar player embed
☑ Personalizar cor (opcional)
☑ Verificar responsividade
☑ Testar em múltiplos navegadores
☑ Compartilhar link público
```

---

## 🆘 Troubleshooting

### Player não carrega

**Causa:** TRACK_ID incorreto

**Solução:**
```javascript
// Verificar no console
fetch('https://soundcloud.com/oembed?format=json&url=https://soundcloud.com/seu-usuario/sua-musica')
    .then(r => r.json())
    .then(d => console.log('ID:', d.id));
```

### Som não toca

**Causa:** Música não está pública ou deactivada

**Solução:**
1. Acesse sua música no SoundCloud
2. Verifique se está marcada como "Public"
3. Verifique permissões de compartilhamento

### CORS / Erro de Cross-Origin

**Causa:** Navegador bloqueando requisição

**Solução:** SoundCloud players não devem ter esse problema, mas se tiver:
- Use HTTPS em vez de HTTP
- Verifique whitelist do SoundCloud

---

## 📱 Responsividade

```html
<!-- Player Responsivo -->
<div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
    <iframe 
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
        scrolling="no" 
        frameborder="no" 
        allow="autoplay" 
        src="https://w.soundcloud.com/player/?url=...">
    </iframe>
</div>
```

---

## 🎵 Adicionar Múltiplas Músicas

```html
<!-- Playlist -->
<div class="playlist">
    <h2>Minhas Músicas</h2>
    
    <!-- Música 1 -->
    <div class="song">
        <h3>Outra Vida</h3>
        <iframe width="100%" height="120" ...></iframe>
    </div>
    
    <!-- Música 2 -->
    <div class="song">
        <h3>Outra Música</h3>
        <iframe width="100%" height="120" ...></iframe>
    </div>
</div>
```

---

## 🔗 Links Úteis

- **SoundCloud:** https://soundcloud.com
- **Documentação:** https://developers.soundcloud.com
- **Widget API:** https://soundcloud.com/pages/developers
- **Gerador de Embed:** https://soundcloud.com/pages/developers

---

## ✅ Status

- ✅ Sem custos
- ✅ Sem limites de upload
- ✅ Multiplataforma
- ✅ Fácil de integrar
- ✅ Suporta múltiplos formatos

**Aproveite sua integração com SoundCloud!** 🎵✨