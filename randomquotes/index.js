const express = require('express');
const app = express();
const os = require("os");
const port = 80;

const phrases = [
  "Get ready to be inspired…", 
  "See rejection as redirection.",
  "There is beauty in simplicity.",
  "You can’t be late until you show up.",
  "Maybe life is testing you. Don’t give up.",
  "Impossible is just an opinion.",
  "Alone or not you gonna walk forward.",
]

app.get('/', (req, res) => {
  const number = Math.floor(Math.random() * phrases.length);
  const phrase = phrases[number];
  const containerId = os.hostname();

  res.send(`${phrase} - Container ID: ${containerId}`);
})

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`);
})
