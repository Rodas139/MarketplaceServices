<div class="imagem-container" >
    <img src="{{ asset('assets/img/rules_horizontal.png') }}" alt="Avatar" style="max-width: 100%;" class="imagem-para-ampliar">
    <div class="icone-ampliar">&#128269;</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function abrirJanelaAmpliada(imagemSrc) {
        // Crie um elemento <img> para exibir a imagem em tamanho real
        var imagemAmpliada = document.createElement("img");
        imagemAmpliada.setAttribute("src", imagemSrc);

        // Crie um elemento <div> para a janela de visualização
        var janelaAmpliada = document.createElement("div");
        janelaAmpliada.classList.add("janela-ampliada");
        janelaAmpliada.appendChild(imagemAmpliada);

        // Adicione a janela ampliada ao corpo do documento
        document.body.appendChild(janelaAmpliada);

        // Configure um evento de clique para fechar a janela ampliada
        janelaAmpliada.addEventListener("click", function () {
            document.body.removeChild(janelaAmpliada);
        });
    }

    var imagensParaAmpliar = document.querySelectorAll(".imagem-para-ampliar");
    imagensParaAmpliar.forEach(function (imagem) {
        imagem.addEventListener("click", function () {
            var imagemOriginalSrc = this.getAttribute("src");
            abrirJanelaAmpliada(imagemOriginalSrc);
        });
    });

    var iconesAmpliar = document.querySelectorAll(".icone-ampliar");
    iconesAmpliar.forEach(function (icone) {
        icone.addEventListener("click", function () {
            var imagemOriginalSrc = this.previousElementSibling.getAttribute("src");
            abrirJanelaAmpliada(imagemOriginalSrc);
        });
    });
});
</script>

