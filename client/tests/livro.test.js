import { describe, it, expect, beforeEach } from "vitest";
import { Livro } from "../livro";
import { DateMock } from "../mock/date_mock";

describe("Livro", () => {
    let dateMgr = new DateMock(1337);
    let livro = new Livro(dateMgr);

    beforeEach(() => {
        livro = new Livro(dateMgr);
    });

    it("Deve começar vazio", () => {
        let livroLimpo = new Livro(dateMgr);

        livroLimpo.bookInfo.titulo = "";
        livroLimpo.bookInfo.genero = "";
        livroLimpo.bookInfo.autor = "";
        livroLimpo.bookInfo.lido = false;
        livroLimpo.bookInfo.dataFinalizacao = null;
        livroLimpo.dateManager = dateMgr;

        expect(livro).toStrictEqual(livroLimpo);
    });

    it("Pode ser lido", () => {
        livro.read();

        expect(livro.bookInfo.lido).toBe(true);
        expect(livro.bookInfo.dataFinalizacao).toBe(1337);
    });
});