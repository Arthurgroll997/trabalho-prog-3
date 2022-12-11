import express from "express";
import path from "path";

const port = 3000;
const app = express();

app.use(express.json());

app.get("/", (req, res) => {
    res.send("Requisitos para rodar o programa: Node.JS 16\nInstruções para rodar:\n\n1)npm install\n\n2)npm run test");
});

app.listen(port, () => console.log(`Ouvindo na porta ${port}...`));