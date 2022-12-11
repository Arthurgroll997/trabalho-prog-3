export class Livro
{
    constructor(dateManager, book)
    {
        this.dateManager = dateManager;
        
        if (book)
        {
            this.bookInfo = {...book};
            return;
        }

        this.bookInfo = {};

        this.bookInfo.titulo = "";
        this.bookInfo.genero = "";
        this.bookInfo.autor = "";
        this.bookInfo.lido = false;
        this.bookInfo.dataFinalizacao = null;
    }

    read() {
        this.bookInfo.lido = true;
        this.bookInfo.dataFinalizacao = this.dateManager.getTime(); // DATA SERÁ SALVA EM TIMESTAMP COM OS MILLISEGUNDOS!
    }
}