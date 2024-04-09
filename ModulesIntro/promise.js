const {readdir : readDir, readFile, writeFile} = require('fs/promises');

readDir("./files")
.then(names => Promise.all(names.map(name => readFile('./files/' + name, {encoding: 'utf-8'}))))
.then(files => writeFile('./concat.txt', files.join('\n')))
.then(() => console.log('finished'))