(async function oneko() {
  const nekoEl = document.createElement("div");
  let nekoPosX = 32,
    nekoPosY = 32,
    mousePosX = 0,
    mousePosY = 0,
    frameCount = 0,
    idleTime = 0,
    idleAnimation = null,
    idleAnimationFrame = 0,
    forceSleep = false,
    grabbing = false,
    grabStop = true,
    nudge = false,
    kuroNeko = false,
    variant = "classic";

  // Configurações
  const config = {
    speed: 10,
    baseAssetPath: "./assets/oneko/",
  };

  // Variantes disponíveis
  const variants = [
    ["classic", "Classic"],
    ["dog", "Dog"],
    ["tora", "Tora"],
    ["maia", "Maia"],
    ["vaporwave", "Vaporwave"],
  ];

  // Mapa de sprites para animações
  const spriteSets = {
    idle: [[-3, -3]],
    alert: [[-7, -3]],
    scratchSelf: [
      [-5, 0],
      [-6, 0],
      [-7, 0],
    ],
    scratchWallN: [
      [0, 0],
      [0, -1],
    ],
    scratchWallS: [
      [-7, -1],
      [-6, -2],
    ],
    scratchWallE: [
      [-2, -2],
      [-2, -3],
    ],
    scratchWallW: [
      [-4, 0],
      [-4, -1],
    ],
    tired: [[-3, -2]],
    sleeping: [
      [-2, 0],
      [-2, -1],
    ],
    N: [
      [-1, -2],
      [-1, -3],
    ],
    NE: [
      [0, -2],
      [0, -3],
    ],
    E: [
      [-3, 0],
      [-3, -1],
    ],
    SE: [
      [-5, -1],
      [-5, -2],
    ],
    S: [
      [-6, -3],
      [-7, -2],
    ],
    SW: [
      [-5, -3],
      [-6, -1],
    ],
    W: [
      [-4, -2],
      [-4, -3],
    ],
    NW: [
      [-1, 0],
      [-1, -1],
    ],
  };

  // Funções auxiliares
  function parseLocalStorage(key, fallback) {
    try {
      const value = JSON.parse(localStorage.getItem(`oneko:${key}`));
      return typeof value === typeof fallback ? value : fallback;
    } catch (e) {
      console.log("oneko: localStorage parse error", e);
      return fallback;
    }
  }

  function create() {
    variant = parseLocalStorage("variant", "classic");
    kuroNeko = parseLocalStorage("kuroneko", false);

    if (!variants.some((v) => v[0] === variant)) {
      variant = "classic";
    }

    nekoEl.id = "oneko";
    nekoEl.style.width = "32px";
    nekoEl.style.height = "32px";
    nekoEl.style.position = "fixed";
    nekoEl.style.backgroundImage = `url('${config.baseAssetPath}oneko-${variant}.gif')`;
    nekoEl.style.imageRendering = "pixelated";
    nekoEl.style.left = `${nekoPosX - 16}px`;
    nekoEl.style.top = `${nekoPosY - 16}px`;
    nekoEl.style.filter = kuroNeko ? "invert(100%)" : "none";
    nekoEl.style.zIndex = "9999";
    nekoEl.style.cursor = "grab";
    nekoEl.style.userSelect = "none";

    // Adicionar CSS ao documento
    if (!document.getElementById("oneko-styles")) {
      const style = document.createElement("style");
      style.id = "oneko-styles";
      style.innerHTML = `
        #oneko {
          transition: none !important;
        }
        #oneko:active {
          cursor: grabbing;
        }
        .oneko-menu {
          position: fixed;
          background: white;
          color: black;
          border: 2px solid #333;
          border-radius: 8px;
          padding: 8px;
          z-index: 10000;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .oneko-menu-item {
          padding: 8px 12px;
          color: black;
          cursor: pointer;
          border-radius: 4px;
          font-size: 14px;
          white-space: nowrap;
        }
        .oneko-menu-item:hover {
          background: #f0f0f0;
        }
      `;
      document.head.appendChild(style);
    }

    document.body.appendChild(nekoEl);

    // Event listeners
    window.addEventListener("mousemove", (e) => {
      if (forceSleep) return;
      mousePosX = e.clientX;
      mousePosY = e.clientY;
    });

    window.addEventListener("resize", () => {
      if (forceSleep) {
        forceSleep = false;
        sleep();
      }
    });

    // Drag handler
    nekoEl.addEventListener("mousedown", handleDragStart);
    nekoEl.addEventListener("contextmenu", handleRightClick);
    nekoEl.addEventListener("dblclick", sleep);

    // Start animation loop
    window.onekoInterval = setInterval(frame, 100);
  }

  function handleDragStart(e) {
    if (e.button !== 0) return;
    grabbing = true;
    let startX = e.clientX;
    let startY = e.clientY;
    let startNekoX = nekoPosX;
    let startNekoY = nekoPosY;
    let grabInterval;

    const mousemove = (e) => {
      const deltaX = e.clientX - startX;
      const deltaY = e.clientY - startY;
      const absDeltaX = Math.abs(deltaX);
      const absDeltaY = Math.abs(deltaY);

      // Scratch animation
      if (absDeltaX > absDeltaY && absDeltaX > 10) {
        setSprite(deltaX > 0 ? "scratchWallW" : "scratchWallE", frameCount);
      } else if (absDeltaY > absDeltaX && absDeltaY > 10) {
        setSprite(deltaY > 0 ? "scratchWallN" : "scratchWallS", frameCount);
      }

      if (
        grabStop ||
        absDeltaX > 10 ||
        absDeltaY > 10 ||
        Math.sqrt(deltaX ** 2 + deltaY ** 2) > 10
      ) {
        grabStop = false;
        clearTimeout(grabInterval);
        grabInterval = setTimeout(() => {
          grabStop = true;
          nudge = false;
          startX = e.clientX;
          startY = e.clientY;
          startNekoX = nekoPosX;
          startNekoY = nekoPosY;
        }, 150);
      }

      nekoPosX = startNekoX + e.clientX - startX;
      nekoPosY = startNekoY + e.clientY - startY;
      nekoEl.style.left = `${nekoPosX - 16}px`;
      nekoEl.style.top = `${nekoPosY - 16}px`;
    };

    const mouseup = () => {
      grabbing = false;
      nudge = true;
      resetIdleAnimation();
      window.removeEventListener("mousemove", mousemove);
      window.removeEventListener("mouseup", mouseup);
    };

    window.addEventListener("mousemove", mousemove);
    window.addEventListener("mouseup", mouseup);
  }

  function handleRightClick(e) {
    e.preventDefault();
    showContextMenu(e.clientX, e.clientY);
  }

  function showContextMenu(x, y) {
    // Remove menu anterior se existir
    const existingMenu = document.getElementById("oneko-context-menu");
    if (existingMenu) existingMenu.remove();

    const menu = document.createElement("div");
    menu.id = "oneko-context-menu";
    menu.className = "oneko-menu";
    menu.style.left = `${x}px`;
    menu.style.top = `${y}px`;

    const items = [
      {
        label: "Mudar variante",
        onClick: showVariantPicker,
      },
      {
        label: kuroNeko ? "Normal" : "Inverter cores",
        onClick: toggleKuroNeko,
      },
      {
        label: forceSleep ? "Acordar" : "Dormir",
        onClick: sleep,
      },
      {
        label: "Esconder",
        onClick: hideOneko,
      },
    ];

    items.forEach((item) => {
      const div = document.createElement("div");
      div.className = "oneko-menu-item";
      div.textContent = item.label;
      div.onclick = () => {
        item.onClick();
        menu.remove();
      };
      menu.appendChild(div);
    });

    document.body.appendChild(menu);

    // Fechar menu ao clicar fora
    setTimeout(() => {
      document.addEventListener(
        "click",
        (e) => {
          if (!menu.contains(e.target) && e.target !== nekoEl) {
            menu.remove();
          }
        },
        { once: true },
      );
    }, 0);
  }

  function toggleKuroNeko() {
    kuroNeko = !kuroNeko;
    localStorage.setItem("oneko:kuroneko", kuroNeko);
    nekoEl.style.filter = kuroNeko ? "invert(100%)" : "none";
  }

  function sleep() {
    forceSleep = !forceSleep;
    nudge = false;
    localStorage.setItem("oneko:forceSleep", forceSleep);
    if (!forceSleep) {
      resetIdleAnimation();
    }
  }

  function hideOneko() {
    nekoEl.style.display = "none";
    localStorage.setItem("oneko:hidden", true);
    showShowButton();
  }

  function showShowButton() {
    if (document.getElementById("oneko-show-button")) return;

    const btn = document.createElement("button");
    btn.id = "oneko-show-button";
    btn.textContent = "🐱";
    btn.style.position = "fixed";
    btn.style.bottom = "20px";
    btn.style.right = "20px";
    btn.style.width = "50px";
    btn.style.height = "50px";
    btn.style.borderRadius = "50%";
    btn.style.border = "none";
    btn.style.background = "#1DB954";
    btn.style.color = "white";
    btn.style.fontSize = "24px";
    btn.style.cursor = "pointer";
    btn.style.zIndex = "9998";
    btn.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.2)";

    btn.onclick = () => {
      nekoEl.style.display = "block";
      localStorage.setItem("oneko:hidden", false);
      btn.remove();
    };

    document.body.appendChild(btn);
  }

  function showVariantPicker() {
    // Remover picker anterior se existir
    const existingPicker = document.getElementById("oneko-variant-picker");
    if (existingPicker) existingPicker.remove();

    const picker = document.createElement("div");
    picker.id = "oneko-variant-picker";
    picker.className = "oneko-menu";
    picker.style.left = `${window.innerWidth / 2 - 150}px`;
    picker.style.top = `${window.innerHeight / 2 - 100}px`;

    const title = document.createElement("div");
    title.style.fontWeight = "bold";
    title.style.marginBottom = "8px";
    title.textContent = "Escolha uma variante:";
    picker.appendChild(title);

    variants.forEach((v) => {
      const item = document.createElement("div");
      item.className = "oneko-menu-item";
      item.textContent = v[1];
      item.style.background = v[0] === variant ? "#e8f5e9" : "";
      item.onclick = () => {
        setVariant(v[0]);
        picker.remove();
      };
      picker.appendChild(item);
    });

    document.body.appendChild(picker);
  }

  function setVariant(newVariant) {
    variant = newVariant;
    localStorage.setItem("oneko:variant", `"${variant}"`);
    nekoEl.style.backgroundImage = `url('${config.baseAssetPath}oneko-${variant}.gif')`;
  }

  function getSprite(name, frame) {
    return spriteSets[name][frame % spriteSets[name].length];
  }

  function setSprite(name, frame) {
    const sprite = getSprite(name, frame);
    nekoEl.style.backgroundPosition = `${sprite[0] * 32}px ${sprite[1] * 32}px`;
  }

  function resetIdleAnimation() {
    idleAnimation = null;
    idleAnimationFrame = 0;
  }

  function idle() {
    idleTime += 1;

    if (
      idleTime > 10 &&
      Math.floor(Math.random() * 200) == 0 &&
      idleAnimation == null
    ) {
      let availableIdleAnimations = ["sleeping", "scratchSelf"];
      if (nekoPosX < 32) availableIdleAnimations.push("scratchWallW");
      if (nekoPosY < 32) availableIdleAnimations.push("scratchWallN");
      if (nekoPosX > window.innerWidth - 32)
        availableIdleAnimations.push("scratchWallE");
      if (nekoPosY > window.innerHeight - 32)
        availableIdleAnimations.push("scratchWallS");
      idleAnimation =
        availableIdleAnimations[
          Math.floor(Math.random() * availableIdleAnimations.length)
        ];
    }

    if (forceSleep) {
      idleAnimation = "sleeping";
    }

    switch (idleAnimation) {
      case "sleeping":
        if (idleAnimationFrame < 8 && nudge && forceSleep) {
          setSprite("idle", 0);
          break;
        } else if (nudge) {
          nudge = false;
          resetIdleAnimation();
        }
        if (idleAnimationFrame < 8) {
          setSprite("tired", 0);
          break;
        }
        setSprite("sleeping", Math.floor(idleAnimationFrame / 4));
        if (idleAnimationFrame > 192 && !forceSleep) {
          resetIdleAnimation();
        }
        break;
      case "scratchWallN":
      case "scratchWallS":
      case "scratchWallE":
      case "scratchWallW":
      case "scratchSelf":
        setSprite(idleAnimation, idleAnimationFrame);
        if (idleAnimationFrame > 9) {
          resetIdleAnimation();
        }
        break;
      default:
        setSprite("idle", 0);
        return;
    }
    idleAnimationFrame += 1;
  }

  function frame() {
    frameCount += 1;

    if (grabbing) {
      grabStop && setSprite("alert", 0);
      return;
    }

    const diffX = nekoPosX - mousePosX;
    const diffY = nekoPosY - mousePosY;
    const distance = Math.sqrt(diffX ** 2 + diffY ** 2);

    if (
      forceSleep &&
      Math.abs(diffY) < config.speed &&
      Math.abs(diffX) < config.speed
    ) {
      nekoPosX = mousePosX;
      nekoPosY = mousePosY;
      nekoEl.style.left = `${nekoPosX - 16}px`;
      nekoEl.style.top = `${nekoPosY - 16}px`;
      idle();
      return;
    }

    if ((distance < config.speed || distance < 48) && !forceSleep) {
      idle();
      return;
    }

    idleAnimation = null;
    idleAnimationFrame = 0;

    if (idleTime > 1) {
      setSprite("alert", 0);
      idleTime = Math.min(idleTime, 7);
      idleTime -= 1;
      return;
    }

    let direction = diffY / distance > 0.5 ? "N" : "";
    direction += diffY / distance < -0.5 ? "S" : "";
    direction += diffX / distance > 0.5 ? "W" : "";
    direction += diffX / distance < -0.5 ? "E" : "";
    setSprite(direction, frameCount);

    nekoPosX -= (diffX / distance) * config.speed;
    nekoPosY -= (diffY / distance) * config.speed;

    nekoPosX = Math.min(Math.max(16, nekoPosX), window.innerWidth - 16);
    nekoPosY = Math.min(Math.max(16, nekoPosY), window.innerHeight - 16);

    nekoEl.style.left = `${nekoPosX - 16}px`;
    nekoEl.style.top = `${nekoPosY - 16}px`;
  }

  // Inicializar
  function init() {
    create();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Restaurar estado salvo
  if (parseLocalStorage("hidden", false)) {
    nekoEl.style.display = "none";
    showShowButton();
  }

  if (parseLocalStorage("forceSleep", false)) {
    setTimeout(() => {
      sleep();
      mousePosX = window.innerWidth / 2;
      mousePosY = window.innerHeight / 2;
    }, 500);
  }
})();
