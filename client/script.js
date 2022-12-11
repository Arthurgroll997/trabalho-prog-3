let server = "http://localhost:" + (process.env.SERVER_PORT || 9000) + "/";

const app = Vue.createApp({
    data() {
        return {
            titulo: "",
            genero: "",
            autor: "",
            livroAtual: { titulo: "asdfgh", genero: "bbbbbb", autor: "Milena"}, //null,
            proxLivro: null,
            ultimoLivroLido: null,
            livrosNaoLidos: null,
            livrosLidos: null,
            numLivrosLidos: 0,
            numLivrosNaoLidos: 0,
        }
    },
    methods: {
        getInfo() {
            $.ajax({
                type: 'POST',
                url: server,
                headers: {
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Headers' : '*'
                },
                data: {
                    action: 'getInfo',
                },
                success: (resStr) => {
                    let res = JSON.parse(resStr);

                    console.log(res);

                    this.livroAtual = res.info.livroAtual;
                    this.proxLivro = res.info.proxLivroParaLer;
                    this.ultimoLivroLido = res.info.ultimoLivroLido;
                    this.livrosNaoLidos = res.info.livrosNaoLidos;
                    this.livrosLidos = res.info.livrosLidos;
                    this.numLivrosLidos = res.info.numLivrosLidos;
                    this.numLivrosNaoLidos = res.info.numLivrosNaoLidos;
                }
            });
        },
        lerLivro() {
            $.ajax({
                type: 'POST',
                url: server,
                headers: {
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Headers' : '*'
                },
                data: {
                    action: 'lerLivro',
                },
                success: () => {
                    this.getInfo();
                }
            });
        },
        addLivro() {
            $.ajax({
                type: 'POST',
                url: server,
                headers: {
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Headers' : '*'
                },
                data: {
                    action: 'addLivro',
                    titulo: this.titulo,
                    genero: this.genero,
                    autor: this.autor,
                },
                success: () => {
                    this.getInfo();
                }
            }).bind(this);
        },
    },
    mounted() {
        this.getInfo();
    }
});

app.mount("#app");