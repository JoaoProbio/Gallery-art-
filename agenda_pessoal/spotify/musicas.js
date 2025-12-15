// ===== DADOS DAS MÚSICAS =====
// Este arquivo centraliza todas as informações das músicas
// Usa URLs de stream direto do Spotify (sem precisar fazer upload de MP3)

export const musicas = [
  {
    id: 1,
    songName: "Outra Vida",
    songDes: "Uma música envolvente e relaxante",
    songImage: "Images/capaOutraVida.jpg",

    // URL de preview do Spotify (30 segundos - gratuito e funciona no Neocities!)
    // Obtém a preview URL da API do Spotify
    spotifyPreviewUrl: "https://p.scdn.co/mp3-preview/3eb938268459c1414e7f46a384d970fbe0218f5d",

    // Link do Spotify (para abrir se desejar)
    spotifyUrl: "https://open.spotify.com/intl-pt/track/1tqNjCuAIoG1kyBfs8DfZE?si=cd3cea5aba204b74",

    // Para uso local em desenvolvimento (não será usado no Neocities)
    localPath: "Audio/Outra Vida.mp3",
  },

  // Exemplo de como adicionar mais músicas:
  // {
  //   id: 2,
  //   songName: "Nome da Música",
  //   songDes: "Descrição da música",
  //   songImage: "Images/capa2.jpg",
  //   spotifyPreviewUrl: "https://p.scdn.co/mp3-preview/...",
  //   spotifyUrl: "https://open.spotify.com/track/...",
  // },
];

// ===== FUNÇÃO PARA OBTER O CAMINHO CORRETO =====
export function obterCaminhoMusica(musica) {
  // Prioridade 1: Spotify Preview (stream direto, sem upload necessário)
  if (musica.spotifyPreviewUrl) {
    return musica.spotifyPreviewUrl;
  }

  // Prioridade 2: Arquivo local (apenas em desenvolvimento)
  if (musica.localPath && estaEmDesenvolvimentoLocal()) {
    return musica.localPath;
  }

  // Fallback: Spotify URL (abre em nova aba)
  return musica.spotifyUrl;
}

// ===== FUNÇÃO PARA VERIFICAR SE É SPOTIFY PREVIEW =====
export function isSpotifyPreviewUrl(url) {
  return url && url.includes("p.scdn.co/mp3-preview");
}

// ===== FUNÇÃO PARA VERIFICAR SE É SPOTIFY =====
export function isSpotifyUrl(url) {
  return url && url.includes("open.spotify.com");
}

// ===== FUNÇÃO PARA VERIFICAR AMBIENTE =====
export function estaEmDesenvolvimentoLocal() {
  return (
    window.location.hostname === "localhost" ||
    window.location.hostname === "127.0.0.1" ||
    window.location.protocol === "file:"
  );
}

// ===== FUNÇÃO PARA OBTER TIPO DE URL =====
export function obterTipoUrl(url) {
  if (!url) return "desconhecido";

  if (url.includes("p.scdn.co/mp3-preview")) return "spotify-preview";
  if (url.includes("open.spotify.com")) return "spotify";
  if (url.includes(".mp3")) return "mp3";

  return "desconhecido";
}

// ===== FUNÇÕES PARA GERENCIAR PLAYLIST =====

export function adicionarMusica(novaMusica) {
  const novoId = Math.max(...musicas.map((m) => m.id), 0) + 1;
  novaMusica.id = novoId;
  musicas.push(novaMusica);
  return novaMusica;
}

export function obterTodasAsMusicas() {
  return musicas;
}

export function obterMusicaPorId(id) {
  return musicas.find((musica) => musica.id === id);
}

export function obterMusicaPorNome(nome) {
  return musicas.find((musica) =>
    musica.songName.toLowerCase().includes(nome.toLowerCase())
  );
}

export function removerMusica(id) {
  const index = musicas.findIndex((m) => m.id === id);
  if (index > -1) {
    musicas.splice(index, 1);
    return true;
  }
  return false;
}

// ===== FUNÇÃO PARA EMBARALHAR PLAYLIST =====
export function embaralharPlaylist() {
  const shuffled = [...musicas];
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
  }
  return shuffled;
}

// ===== FUNÇÃO PARA PESQUISAR MÚSICAS =====
export function pesquisarMusicas(termo) {
  const termoLower = termo.toLowerCase();
  return musicas.filter(
    (musica) =>
      musica.songName.toLowerCase().includes(termoLower) ||
      musica.songDes.toLowerCase().includes(termoLower)
  );
}

// ===== TOTAL DE MÚSICAS =====
export function obterTotalMusicas() {
  return musicas.length;
}
