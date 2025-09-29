document.addEventListener("DOMContentLoaded", function () {
  // Seleciona todos os links da navbar. querySelectorAll retorna uma NodeList,
  // que permite o uso do forEach para iterar sobre os elementos.
  const navlinks = document.querySelectorAll(".navlink");

  // Seleciona todos os containers dos links
  const navItems = document.querySelectorAll(".nav-item");

  navItems.forEach((item) => {
    const underline = item.querySelector(".underline");
    // Define a transição para a propriedade 'width'
    underline.style.transition = "width 0.3s ease-in-out";

    item.addEventListener("mouseover", function () {
      // Anima a largura para 100%
      underline.style.width = "100%";
    });
    item.addEventListener("mouseout", function () {
      // Retorna a largura para 0%
      underline.style.width = "0%";
    });
  });

  // Corrige o método para getElementById (singular)
  const titulo = document.getElementById("titulo");
  titulo.addEventListener("mouseover", function () {
    this.style.transform = "scale(1.1)"; //Aumenta o tamanho do título em 10%
    this.style.transition = "transform 0.5s ease"; // Aplica a transição na entrada
  });
  titulo.addEventListener("mouseout", function () {
    this.style.transform = "scale(1)"; // Retorna o título para o tamanho original
    this.style.transition = "transform 0.5s ease"; // Aplica a mesma transição na saída
  });
});
