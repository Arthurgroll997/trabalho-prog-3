import { json } from "express";

export class ListaLivros
{
    constructor(nativeStorage) {
        this.nativeStorage = nativeStorage;

        this.nativeStorage.setItem("curBook", this.nativeStorage.getItem("curBook") ? this.nativeStorage.getItem("curBook") : null);
        this.nativeStorage.setItem("booksToRead", this.nativeStorage.getItem("booksToRead") ? this.nativeStorage.getItem("booksToRead") : []);
        this.nativeStorage.setItem("booksReadCount", this.nativeStorage.getItem("booksReadCount") ? this.nativeStorage.getItem("booksReadCount") : 0);
        this.nativeStorage.setItem("booksRead", this.nativeStorage.getItem("booksRead") ? this.nativeStorage.getItem("booksRead") : []);
        this.nativeStorage.setItem("lastBookRead", this.nativeStorage.getItem("lastBookRead") ? this.nativeStorage.getItem("lastBookRead") : null);
    }

    addBook(book, alreadyRead = true) {
        if (!this.nativeStorage.getItem("curBook") && !alreadyRead)
        {
            this.nativeStorage.setItem("curBook", book);
            return;
        }

        let booksToRead = this.nativeStorage.getItem("booksToRead") ? this.nativeStorage.getItem("booksToRead") : [];
        let booksRead = this.nativeStorage.getItem("booksRead") ? this.nativeStorage.getItem("booksRead") : [];

        if (alreadyRead)
            this.nativeStorage.setItem("booksRead", [...booksRead, book]);
        else
            this.nativeStorage.setItem("booksToRead", [...booksToRead, book]);
    }

    getBooksRead() {
        return this.nativeStorage.getItem("booksRead");
    }

    getBooksToRead() {
        return this.nativeStorage.getItem("booksToRead");
    }

    getBooksReadCount() {
        return this.nativeStorage.getItem("booksReadCount");
    }

    incBooksRead() {
        this.nativeStorage.setItem("booksReadCount", this.nativeStorage.getItem("booksReadCount") + 1);
    }

    updateList() {
        let booksToRead = this.nativeStorage.getItem("booksToRead") ? this.nativeStorage.getItem("booksToRead") : [];
        let booksRead = this.nativeStorage.getItem("booksRead") ? this.nativeStorage.getItem("booksRead") : [];

        let items = booksToRead.filter(book => book.lido);

        if (items.length != 0)
            this.nativeStorage.setItem("booksRead", [...booksToRead, ...items, this.nativeStorage.getItem("curBook")]);
        else
            this.nativeStorage.setItem("booksRead", [...booksRead, this.nativeStorage.getItem("curBook")]);

        this.nativeStorage.setItem("booksToRead", booksToRead.filter((book) => !book.lido));

        if (this.nativeStorage.getItem("booksToRead").length > 0)
        {
            let targetBook = this.nativeStorage.getItem("booksToRead")[0];
            this.nativeStorage.setItem("curBook", this.nativeStorage.getItem("booksToRead")[0]);
            this.nativeStorage.setItem("booksToRead", this.nativeStorage.getItem("booksToRead").filter((book) => book != targetBook));
        }
        else
        {
            this.nativeStorage.setItem("curBook", null);
        }

        this.incBooksRead();
    }

    getAllBooks() {
        let booksToRead = this.nativeStorage.getItem("booksToRead") ? this.nativeStorage.getItem("booksToRead") : [];
        let booksRead = this.nativeStorage.getItem("booksRead") ? this.nativeStorage.getItem("booksRead") : [];
        
        return this.nativeStorage.getItem("curBook") ?
            [this.nativeStorage.getItem("curBook"), ...booksToRead, ...booksRead] :
            [...booksToRead, ...booksRead];
    }

    finishBook() {
        if (!this.nativeStorage.getItem("curBook"))
            return;

        let curBook = this.nativeStorage.getItem("curBook");
        
        curBook.read();

        this.nativeStorage.setItem("curBook", curBook);

        this.nativeStorage.setItem("lastBookRead", this.nativeStorage.getItem("curBook"));

        this.updateList();
    }

    getCurrentBook() {
        return this.nativeStorage.getItem("curBook");
    }

    getLastReadBook() {
        return this.nativeStorage.getItem("lastBookRead");
    }
}