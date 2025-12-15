function abrirModal(card) {
  const modal = document.getElementById("modalObra");

  const titulo = card.querySelector(".obra-modal-titulo").textContent;
  const tecnica = card.querySelector(".obra-modal-tecnica").textContent;
  const ano = card.querySelector(".obra-modal-ano").textContent;
  const descricao = card.querySelector(".obra-modal-descricao").textContent;
  const imagem = card.querySelector(".obra-modal-imagem").textContent;
  const id = card.querySelector(".obra-modal-id").textContent;

  document.getElementById("modalTitulo").textContent = titulo;
  document.getElementById("modalTecnica").textContent = tecnica;
  document.getElementById("modalAno").textContent = ano;
  document.getElementById("modalDescricao").textContent = descricao;
  document.getElementById("modalImagem").src = imagem;

  modal.classList.add("active");
}

function fecharModal() {
  const modal = document.getElementById("modalObra");
  modal.classList.remove("active");
}

// Fechar modal ao clicar fora
document.getElementById("modalObra").addEventListener("click", function (e) {
  if (e.target === this) {
    fecharModal();
  }
});
