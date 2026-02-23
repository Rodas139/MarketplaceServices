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

<style>
/* Estilo para a janela ampliada */
.janela-ampliada {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.janela-ampliada img {
    max-width: 100%;
    max-height: 100%;
}

.imagem-container {
    position: relative;
    display: inline-block;
}

.icone-ampliar {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    background-color: rgba(255, 255, 255, 0.7);
    border-radius: 50%;
    padding: 5px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}

.imagem-container:hover .icone-ampliar {
    opacity: 1;
}
</style>
