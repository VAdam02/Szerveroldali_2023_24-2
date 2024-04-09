const {readdir : readDir, readFile, writeFile} = require('fs/promises');

readDir("./files")
.then(names => {
    console.log(names);

    let output = [];
    let promises = [];    

    for (const name of names) {
        promises.push(
            readFile("./files/" + name, {encoding: 'utf-8'})
        )
    }

    Promise.all(promises)
    .then(files => {
        writeFile("./concat.txt", files.join("\n"))
    })
})