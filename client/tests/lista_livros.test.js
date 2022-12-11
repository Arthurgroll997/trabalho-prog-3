import { describe, it, beforeEach, expect } from "vitest";
import { ListaLivros } from "../lista_livros";
import { Livro } from "../livro";
import { DateMock } from "../mock/date_mock";
import { StorageMock } from "../mock/storage_mock";

describe("Lista de livros", () => {
    let dateMgr = new DateMock(1337);
    let listaLivros = new ListaLivros(new StorageMock());

    beforeEach(() => {
        listaLivros = new ListaLivros(new StorageMock());
    });

    it("Pode ter livros adicionados", () => {
        let livro = new Livro();

        listaLivros.addBook(livro, false);

        expect(listaLivros.getAllBooks()).toStrictEqual([livro]);
    });

    it("Deve ter o primeiro livro não lido adicionado como o livro atual", () => {
        let livro = new Livro(dateMgr, {
            titulo: "XGH vs TDD: Qual escolher para um projeto escalável importante?",
            genero: "Científico/Técnico",
            autor: "Arthur",
            lido: false,
            dataFinalizacao: null,
        });

        listaLivros.addBook(livro, false);

        expect(listaLivros.getCurrentBook()).toStrictEqual(livro);
    });

    it("Deve conter a lista correta de livros adicionados", () => {
        let listaLivrosEsperada = [
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "XGH vs TDD: Qual escolher para um projeto escalável importante?",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "As raivas de passar fazendo teste e não saber o porquê de falhar",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "O viajante intergaláctico",
                autor: "Milena",
                genero: "Ficção Científica",
                lido: true,
                dataFinalizacao: dateMgr.getTime()
            }),
        ];

        listaLivrosEsperada.forEach(book => listaLivros.addBook(book, book.bookInfo.lido));

        expect(listaLivros.getAllBooks()).toStrictEqual(listaLivrosEsperada);

        listaLivros.finishBook();

        expect(listaLivros.getBooksReadCount()).toBe(1);
        expect(listaLivros.getCurrentBook()).toStrictEqual(listaLivrosEsperada[1]);
        expect(listaLivros.getBooksRead()).toStrictEqual([listaLivrosEsperada[3],
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: true,
                dataFinalizacao: 1337,
            })
        ]);
        expect(listaLivros.getBooksToRead()).toStrictEqual([listaLivrosEsperada[2]]);
        expect(listaLivros.getLastReadBook()).toStrictEqual(
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: true,
                dataFinalizacao: 1337,
            })
        );
    });

    it("Deve saber operar com livros já existentes previamente no localStorage", () => {
        let listaLivrosEsperada = [
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "XGH vs TDD: Qual escolher para um projeto escalável importante?",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "As raivas de passar fazendo teste e não saber o porquê de falhar",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "O viajante intergaláctico",
                autor: "Milena",
                genero: "Ficção Científica",
                lido: true,
                dataFinalizacao: dateMgr.getTime()
            }),
        ];

        listaLivros.nativeStorage.setItem("curBook",
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: false,
                dataFinalizacao: false,
            }),
        );
        listaLivros.nativeStorage.setItem("booksRead", [
            new Livro(dateMgr, {
                titulo: "O viajante intergaláctico",
                autor: "Milena",
                genero: "Ficção Científica",
                lido: true,
                dataFinalizacao: dateMgr.getTime()
            }),
        ]);
        listaLivros.nativeStorage.setItem("booksToRead", [
            new Livro(dateMgr, {
                titulo: "XGH vs TDD: Qual escolher para um projeto escalável importante?",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
            new Livro(dateMgr, {
                titulo: "As raivas de passar fazendo teste e não saber o porquê de falhar",
                genero: "Científico/Técnico",
                autor: "Arthur",
                lido: false,
                dataFinalizacao: null,
            }),
        ]);

        listaLivros.finishBook();

        expect(listaLivros.getBooksReadCount()).toBe(1);
        expect(listaLivros.getCurrentBook()).toStrictEqual(listaLivrosEsperada[1]);
        expect(listaLivros.getBooksRead()).toStrictEqual([listaLivrosEsperada[3], 
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: true,
                dataFinalizacao: 1337,
            })
        ]);
        expect(listaLivros.getBooksToRead()).toStrictEqual([listaLivrosEsperada[2]]);
        expect(listaLivros.getLastReadBook()).toStrictEqual(
            new Livro(dateMgr, {
                titulo: "Ser ou não ser: Eis a questão",
                autor: "Pablo",
                genero: "Filosofia",
                lido: true,
                dataFinalizacao: 1337,
            })
        );
    });
});