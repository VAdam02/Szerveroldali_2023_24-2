const {readdir : readDir, readFile, writeFile} = require('fs/promises');

readDir("./files")
.then(names => {
    console.log(names);

    let output = [];
    let finished = [];

    for (const name of names) {
        readFile('./files/' + name, {encoding: 'utf-8'})
        .then(file => {
            output.push(file);

            finished.push(name);
            if (finished.length == names.length) {
                writeFile('./concat.txt', output.join('\n'))
                .then(() => {
                    console.log('finished');
                })
            }
        })
    }
})