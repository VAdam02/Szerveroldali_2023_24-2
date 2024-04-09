const { readdir: readDir, readFile, writeFile } = require('fs');

readDir('./files', (err, names) => {
    console.log(names);

    const output = [];

    for (const name of names) {
        readFile('./files/' + name, { encoding: 'utf-8'}, (err, file) => {
            console.log(file);
            output.push(file);
        })
    }

    writeFile('./concat.txt', output.join('\n'), (err) => {
        console.log('finished');
    })
})