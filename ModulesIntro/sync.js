const {readdirSync: readDirSync, readFileSync, writeFileSync} = require('fs');

const names = readDirSync('./files');
console.log(names);

const output = [];
for (const name of names) {
    const file = readFileSync(`./files/${name}`, 'utf8')
    //const file = readFileSync("./files/" + name, 'utf8') //they are the same

    console.log(file);
    output.push(file);
}

console.log(output);

writeFileSync('./concat.txt', output.join('\n'));