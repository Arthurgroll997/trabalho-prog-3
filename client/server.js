import express from "express";
import path from "path";

const port = process.env.APP_PORT || 5173;
const app = express();

app.use(express.json());
app.use(express.static("dist"));

app.get("/", (req, res) => {
    res.sendFile(path.join(path.resolve("."), "dist", "index.html"));
});

app.listen(port, () => console.log(`Ouvindo na porta ${port}...`));