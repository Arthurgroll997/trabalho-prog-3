export class StorageMock
{
    constructor()
    {
        this.info = [];
    }

    setItem(itemName, itemValue) {
        let item = {name: itemName, value: itemValue};

        let itemIdx = this.getItemIndex(itemName);

        if (itemIdx != -1)
            this.info[itemIdx] = item;
        else
            this.info.push(item);
    }

    removeItem(itemName) {
        this.info = this.info.filter((item) => item.name != itemName);
    }

    getItem(itemName) {
        let item = this.info.find((item) => item.name == itemName);
        return item ? item.value : null;
    }

    getItemIndex(itemName) {
        return this.info.findIndex((item) => item.name == itemName);
    }
}