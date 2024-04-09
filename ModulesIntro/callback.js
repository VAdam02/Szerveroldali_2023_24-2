const { readdir: readDir, readFile, writeFile } = require('fs');

readDir('./files', (err, names) => {
    //callack 1
    console.log(names);

    const output = [];
    const finished = [];

    for (const name of names) {
        readFile('./files/' + name, { encoding: 'utf-8'}, (err, file) => {
            //callback 2
            console.log(file);
            output.push(file);
            finished.push(name);

            if (finished.length == names.length) {
                writeFile('./concat.txt', output.join('\n'), (err) => {
                    //callback 3
                    console.log('finished');

                    //callback hell
                })
            }
        })
    }
})