const { readdir: readDir, readFile, writeFile } = require('fs/promises');

//IIFE - Immediately Invoked Function Expression
(
    async () => {
        const names = await readDir('./files');

        const output = [];
        for(const name of names) {
            const file = await readFile("./files/" + name, {encoding: 'utf-8'});
            output.push(file);
            console.log(file)
        }

        await writeFile('./concat.txt', output.join('\n'));

        console.log('finished');
    }
)();